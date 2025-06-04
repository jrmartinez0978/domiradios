<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRadioSeoRequest;
use App\Models\Radio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class RadioSeoController extends Controller
{
    /**
     * Obtener datos SEO de todas las radios activas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $radios = Radio::select([
                'id', 'slug', 'name', 'meta_title', 'meta_description',
                'og_title', 'og_description', 'og_image',
                'h1', 'canonical_url', 'seo_checksum', 'seo_last_checked_at'
            ])->where('isActive', true)->get();

            return response()->json($radios);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener datos SEO de una emisora específica
     * 
     * @param \App\Models\Radio $radio
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Radio $radio): JsonResponse
    {
        try {
            return response()->json($radio->only([
                'id', 'slug', 'name', 'meta_title', 'meta_description',
                'og_title', 'og_description', 'og_image',
                'h1', 'canonical_url', 'seo_checksum', 'seo_last_checked_at'
            ]));
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Actualizar datos SEO de una emisora específica
     * 
     * @param \App\Http\Requests\UpdateRadioSeoRequest $request
     * @param \App\Models\Radio $radio
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRadioSeoRequest $request, Radio $radio): JsonResponse
    {
        try {
            $payload = $request->validated();
            $checksum = sha1(json_encode($payload));

            // Evita re‑escritos innecesarios
            if ($checksum === $radio->seo_checksum) {
                return response()->json([
                    'status' => 'unchanged',
                    'message' => 'No se detectaron cambios en los datos SEO'
                ]);
            }

            DB::transaction(function () use ($radio, $payload, $checksum) {
                $radio->update(array_merge($payload, [
                    'seo_checksum' => $checksum,
                    'seo_last_checked_at' => now(),
                ]));
            });

            return response()->json([
                'status' => 'updated',
                'message' => 'Datos SEO actualizados correctamente',
                'radio' => $radio->only(['id', 'slug', 'name', 'seo_last_checked_at'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear una copia de respaldo de los datos SEO de una emisora
     * 
     * @param \App\Models\Radio $radio
     * @return \Illuminate\Http\JsonResponse
     */
    public function backup(Radio $radio): JsonResponse
    {
        try {
            // Seleccionamos directamente solo los campos SEO que necesitamos
            $seoData = [
                'meta_title' => $radio->meta_title,
                'meta_description' => $radio->meta_description,
                'og_title' => $radio->og_title,
                'og_description' => $radio->og_description,
                'og_image' => $radio->og_image,
                'h1' => $radio->h1,
                'canonical_url' => $radio->canonical_url,
                'seo_checksum' => $radio->seo_checksum
            ];
            
            // Crear backup con una operación optimizada
            $backup = DB::table('radio_seo_backups')->insertGetId([
                'radio_id' => $radio->id,
                'meta_title' => $radio->meta_title,
                'meta_description' => $radio->meta_description,
                'og_title' => $radio->og_title,
                'og_description' => $radio->og_description,
                'og_image' => $radio->og_image,
                'h1' => $radio->h1,
                'canonical_url' => $radio->canonical_url,
                'backup_reason' => request('backup_reason', 'manual'),
                'created_by' => 'API',
                'created_at' => now()
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Backup de datos SEO creado correctamente',
                'backup_id' => $backup,
                'radio_id' => $radio->id,
                'created_at' => now()->toIso8601String()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verificar duplicados de título y descripción SEO entre emisoras
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkDuplicates(Request $request): JsonResponse
    {
        try {
            // Inicializar el resultado
            $result = ['duplicates' => []];
            $isUnique = true;
            
            // Obtener parámetros con enfoque simplificado que acepta tanto JSON como query string
            $title = $request->input('title') ?? $request->query('title');
            $description = $request->input('description') ?? $request->query('description');
            $radioId = $request->has('radio_id') ? intval($request->input('radio_id')) : null;
            
            // Convertir valores numéricos a string si es necesario
            if ($title !== null && !is_string($title) && (is_numeric($title) || is_scalar($title))) {
                $title = (string) $title;
            }
            
            if ($description !== null && !is_string($description) && (is_numeric($description) || is_scalar($description))) {
                $description = (string) $description;
            }
            
            // Si no hay ni título ni descripción válidos, devolver error
            if (empty($title) && empty($description)) {
                return response()->json([
                    'error' => 'Debe proporcionar al menos un campo válido: title o description',
                    'received' => [
                        'title' => $title,
                        'description' => $description,
                        'radio_id' => $radioId
                    ]
                ], 400);
            }
            
            // Comprobar duplicados de título
            if ($title !== null) {
                $titleQuery = Radio::where('meta_title', $title)
                    ->where('isActive', true);
                    
                if ($radioId !== null) {
                    $titleQuery->where('id', '!=', $radioId);
                }
                
                $titleDuplicates = $titleQuery->select('id', 'name', 'meta_title')->get();
                
                if ($titleDuplicates->isNotEmpty()) {
                    $result['duplicates']['title'] = $titleDuplicates;
                    $isUnique = false;
                } else {
                    $result['duplicates']['title'] = [];
                }
            }
            
            // Comprobar duplicados de descripción
            if ($description !== null) {
                $descQuery = Radio::where('meta_description', $description)
                    ->where('isActive', true);
                    
                if ($radioId !== null) {
                    $descQuery->where('id', '!=', $radioId);
                }
                
                $descDuplicates = $descQuery->select('id', 'name', 'meta_description')->get();
                
                if ($descDuplicates->isNotEmpty()) {
                    $result['duplicates']['description'] = $descDuplicates;
                    $isUnique = false;
                } else {
                    $result['duplicates']['description'] = [];
                }
            }
            
            $result['is_unique'] = $isUnique;
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar caché relacionada con las radios y regenerar sitemap si es necesario
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearCache(Request $request): JsonResponse
    {
        try {
            $tags = $request->input('tags', ['radios']);
            
            foreach ($tags as $tag) {
                if (method_exists(Cache::class, 'tags')) {
                    Cache::tags($tag)->flush();
                }
            }
            
            // Limpiar vistas cacheadas
            Artisan::call('view:clear');
            
            // Regenerar sitemap si existe la clase
            if (class_exists('Spatie\\Sitemap\\Sitemap')) {
                $this->generateSitemap();
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Caché limpiada correctamente',
                'tags_cleared' => $tags
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera un nuevo sitemap
     *
     * @return void
     */
    private function generateSitemap(): void
    {
        // Implementación básica usando el paquete Spatie si está disponible
        if (class_exists('Spatie\\Sitemap\\Sitemap')) {
            $sitemap = app('Spatie\\Sitemap\\Sitemap');
            
            // Añadir radios activas al sitemap
            Radio::where('isActive', true)->get()->each(function($radio) use ($sitemap) {
                // Crear URL con el slug
                $url = url('/radio/' . $radio->slug);
                $sitemap->add($url);
            });
            
            // Guardar el sitemap
            $sitemap->writeToFile(public_path('sitemap.xml'));
        }
    }
}
