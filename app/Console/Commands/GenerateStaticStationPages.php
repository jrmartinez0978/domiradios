<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Radio;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log; // Añadido Log para errores
use App\Http\Controllers\RadioController; // Para usar su lógica SEO

class GenerateStaticStationPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-static-station-pages'; // Mantenemos 'app:'

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera páginas HTML estáticas para cada emisora activa para mejorar el SEO.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(RadioController $radioController)
    {
        $this->info('Iniciando la generación de páginas estáticas para emisoras...');

        // Asegurar que APP_URL esté configurado para 'asset()'
        if (!config('app.url') || config('app.url') === 'http://localhost') {
            $this->warn("Advertencia: APP_URL no está configurado o es 'http://localhost'. Las URLs de assets (como logos) podrían no generarse correctamente en el HTML estático.");
            $this->warn("Por favor, establece APP_URL en tu archivo .env a la URL de producción/pública de tu sitio.");
            if (!$this->confirm('¿Continuar de todas formas?', true)) {
                $this->info('Generación cancelada por el usuario.');
                return Command::FAILURE;
            }
        }
        
        $radios = Radio::where('isActive', true)->get();
        $staticPagesPath = public_path('s');

        if (!File::isDirectory($staticPagesPath)) {
            File::makeDirectory($staticPagesPath, 0755, true, true);
            $this->info("Directorio '{$staticPagesPath}' creado.");
        }

        if ($radios->isEmpty()) {
            $this->info('No hay emisoras activas para generar páginas.');
            return Command::SUCCESS;
        }

        $count = 0;
        $errors = 0;

        foreach ($radios as $radio) {
            $this->line("Procesando emisora: {$radio->name} (Slug: {$radio->slug})");

            try {
                // Obtener emisoras relacionadas
                $related = Radio::whereHas('genres', function ($query) use ($radio) {
                    $query->whereIn('genres.id', $radio->genres->pluck('id'));
                })
                ->where('id', '!=', $radio->id)
                ->where('isActive', true)
                ->limit(4)
                ->get();
                
                // Establecer datos SEO usando el RadioController
                // Esto asegura que se use la misma lógica que en la vista dinámica.
                $description = strip_tags($radio->description);
                $metaDescription = mb_substr($description, 0, 160);
                if (mb_strlen($description) > 160) {
                    $metaDescription .= '...';
                }

                // Establecer la URL para OpenGraph usando route()
                $stationUrl = route('emisoras.show', ['slug' => $radio->slug]);
                \Artesaos\SEOTools\Facades\OpenGraph::setUrl($stationUrl);

                $logo_url = $radio->logo ? asset($radio->logo) : (config('app.url') ? rtrim(config('app.url'), '/') . '/img/domiradios-logo-og.png' : '/img/domiradios-logo-og.png');

                // El quinto argumento es la URL explícita para el trait HasSeo
                $radioController->setSeoData(
                    $radio->name . ' - Escucha en Vivo | Domiradios',
                    $metaDescription,
                    $logo_url,
                    [], // Keywords, array vacío por ahora
                    $stationUrl 
                );
                
                // Es importante que la URL canónica se establezca correctamente.
                \Artesaos\SEOTools\Facades\SEOTools::setCanonical($stationUrl); // Reutilizar la URL de la estación

                // Asegurémonos de que el nombre del sitio esté explícitamente configurado
                // Es importante que la URL canónica se establezca correctamente.
                // SEOTools::setCanonical($stationUrl);
                // OpenGraph::setUrl($stationUrl);

                // ---- INICIO: AISLAMIENTO DE PROBLEMA ----
                // Comentamos temporalmente las configuraciones de OG y Twitter aquí
                // para ver si el problema está en la configuración base o en estas sobreescrituras.
                // \Artesaos\SEOTools\Facades\OpenGraph::setTitle($radio->name . ' - Escucha en Vivo | Domiradios');
                // \Artesaos\SEOTools\Facades\OpenGraph::setDescription($metaDescription);
                // \Artesaos\SEOTools\Facades\SEOTools::twitter()->setSite(config('seotools.twitter.defaults.site', '@domiradios')); // O tu handle de Twitter
                // ---- FIN: AISLAMIENTO DE PROBLEMA ----

                // ---- INICIO: Depuración Extrema OpenGraph ----
                \Artesaos\SEOTools\Facades\OpenGraph::setType('website'); // Tipo básico
                if (config('app.name')) {
                    \Artesaos\SEOTools\Facades\OpenGraph::setSiteName(config('app.name'));
                } else {
                    \Artesaos\SEOTools\Facades\OpenGraph::setSiteName('Domiradios'); // Fallback extremo
                }
                // Asegurarse de que la URL de la imagen OG sea absoluta o una ruta válida si APP_URL no está completo
                $ogImageUrl = $logo_url;
                if (!str_starts_with($ogImageUrl, 'http') && config('app.url')) {
                    $ogImageUrl = rtrim(config('app.url'), '/') . (str_starts_with($ogImageUrl, '/') ? $ogImageUrl : '/' . $ogImageUrl);
                }
                if (filter_var($ogImageUrl, FILTER_VALIDATE_URL)) {
                    \Artesaos\SEOTools\Facades\OpenGraph::addImage($ogImageUrl, ['secure_url' => str_replace('http://', 'https://', $ogImageUrl)]);
                } else {
                     // Añadir una imagen de fallback absoluta si todo lo demás falla
                    \Artesaos\SEOTools\Facades\OpenGraph::addImage(config('app.url', 'http://localhost') . '/img/domiradios-logo-og.png'); 
                }
                // ---- FIN: Depuración Extrema OpenGraph ----

                // Si tienes keywords específicas por emisora, también puedes añadirlas:
                // if (!empty($radio->tags)) {
                //    \Artesaos\SEOTools\Facades\SEOTools::addKeywords(explode(',', $radio->tags));
                // }

                // Renderizar la vista 'detalles'
                // La vista 'detalles' y su layout 'app.blade.php' usarán {!! \Artesaos\SEOTools\Facades\SEOTools::generate() !!}
                $htmlContent = View::make('detalles', [
                    'radio' => $radio,
                    'related' => $related,
                ])->render();
                
                $filePath = $staticPagesPath . '/' . $radio->slug . '.html';
                File::put($filePath, $htmlContent);
                
                $this->info("-> Página generada: {$filePath}");
                $count++;

            } catch (\Exception $e) {
                $this->error("-> Error generando página para {$radio->name}: " . $e->getMessage());
                Log::error("Error en GenerateStaticStationPages para {$radio->slug}: " . $e->getMessage(), [
                    'exception' => $e->getTraceAsString(), // Más detalle en el log
                ]);
                $errors++;
            }
        }

        if ($errors > 0) {
            $this->warn("Proceso completado con {$errors} errores. Se generaron {$count} páginas estáticas.");
            return Command::FAILURE;
        } else {
            $this->info("Proceso completado. Se generaron {$count} páginas estáticas exitosamente.");
            return Command::SUCCESS;
        }
    }
}
