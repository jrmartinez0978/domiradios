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
                
                $description = strip_tags($radio->description);
                $metaDescription = mb_substr($description, 0, 155); // Ajustado a 155 para dejar espacio para "..."
                if (mb_strlen($description) > 155) {
                    $metaDescription .= '...';
                }

                $stationUrl = route('emisoras.show', ['slug' => $radio->slug]);
                $ogImageUrl = $radio->optimized_logo_url; // Usar el logo optimizado que ya es una URL absoluta
                
                // Fallback si optimized_logo_url está vacío o no es una URL completa
                if (empty($ogImageUrl) || !filter_var($ogImageUrl, FILTER_VALIDATE_URL)) {
                    $ogImageUrl = config('app.url', 'http://localhost') . '/img/domiradios-logo-og.png';
                }

                $siteName = config('app.name', 'Domiradios');
                $twitterSite = config('seotools.twitter.defaults.site', '@Domiradios'); // Asegúrate que esto exista en config/seotools.php o usa un string directo
                $title = htmlspecialchars($radio->name . ' - Escucha en Vivo | ' . $siteName, ENT_QUOTES, 'UTF-8');
                $escapedMetaDescription = htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8');
                $escapedOgImageUrl = htmlspecialchars($ogImageUrl, ENT_QUOTES, 'UTF-8');
                $escapedStationUrl = htmlspecialchars($stationUrl, ENT_QUOTES, 'UTF-8');
                $escapedRadioName = htmlspecialchars($radio->name, ENT_QUOTES, 'UTF-8');

                $staticSeoHtml = "<title>{$title}</title>\n";
                $staticSeoHtml .= "    <meta name=\"description\" content=\"{$escapedMetaDescription}\">\n";
                if (!empty($radio->tags)) {
                    $staticSeoHtml .= "    <meta name=\"keywords\" content=\"" . htmlspecialchars($radio->tags, ENT_QUOTES, 'UTF-8') . "\">\n";
                }
                $staticSeoHtml .= "    <link rel=\"canonical\" href=\"{$escapedStationUrl}\" />\n";

                // OpenGraph
                $staticSeoHtml .= "    <meta property=\"og:title\" content=\"{$title}\" />\n";
                $staticSeoHtml .= "    <meta property=\"og:description\" content=\"{$escapedMetaDescription}\" />\n";
                $staticSeoHtml .= "    <meta property=\"og:url\" content=\"{$escapedStationUrl}\" />\n";
                $staticSeoHtml .= "    <meta property=\"og:image\" content=\"{$escapedOgImageUrl}\" />\n";
                if (str_starts_with($ogImageUrl, 'http://')) {
                    $staticSeoHtml .= "    <meta property=\"og:image:secure_url\" content=\"" . htmlspecialchars(str_replace('http://', 'https://', $ogImageUrl), ENT_QUOTES, 'UTF-8') . "\" />\n";
                }
                $staticSeoHtml .= "    <meta property=\"og:type\" content=\"website\" />\n";
                $staticSeoHtml .= "    <meta property=\"og:site_name\" content=\"" . htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') . "\" />\n";

                // Twitter Card
                $staticSeoHtml .= "    <meta name=\"twitter:card\" content=\"summary_large_image\" />\n";
                $staticSeoHtml .= "    <meta name=\"twitter:title\" content=\"{$title}\" />\n";
                $staticSeoHtml .= "    <meta name=\"twitter:description\" content=\"{$escapedMetaDescription}\" />\n";
                $staticSeoHtml .= "    <meta name=\"twitter:image\" content=\"{$escapedOgImageUrl}\" />\n";
                if ($twitterSite) {
                    $staticSeoHtml .= "    <meta name=\"twitter:site\" content=\"" . htmlspecialchars($twitterSite, ENT_QUOTES, 'UTF-8') . "\" />\n";
                }

                // JSON-LD
                $jsonLdDescription = "Escucha {$escapedRadioName} en vivo. Emisora de radio {$radio->bitrate} - " . htmlspecialchars(\Illuminate\Support\Str::of($radio->tags)->explode(',')->first(), ENT_QUOTES, 'UTF-8') . ".";
                $genresJson = htmlspecialchars($radio->genres->pluck('name')->implode(', '), ENT_QUOTES, 'UTF-8');
                
                $jsonLd = [
                    '@context' => 'https://schema.org',
                    '@type' => 'RadioStation',
                    'name' => $escapedRadioName,
                    'url' => $escapedStationUrl,
                    'logo' => $escapedOgImageUrl,
                    'image' => $escapedOgImageUrl,
                    'description' => $jsonLdDescription,
                    'contentLocation' => [
                        '@type' => 'Place',
                        'name' => $genresJson . ', República Dominicana'
                    ],
                    'genre' => htmlspecialchars($radio->tags, ENT_QUOTES, 'UTF-8'),
                    // 'frequency' => $radio->bitrate, // Omitido por no ser una frecuencia real
                    'audio' => [
                        '@type' => 'AudioObject',
                        'contentUrl' => htmlspecialchars($radio->link_radio, ENT_QUOTES, 'UTF-8'),
                        'encodingFormat' => htmlspecialchars($radio->type_radio, ENT_QUOTES, 'UTF-8') // e.g., 'audio/mpeg'
                    ]
                ];

                if ($radio->rating > 0) {
                    $jsonLd['aggregateRating'] = [
                        '@type' => 'AggregateRating',
                        'ratingValue' => (string)$radio->rating,
                        'bestRating' => '5',
                        'worstRating' => '1',
                        // 'ratingCount' => $radio->ratings()->count() // Si es eficiente y disponible
                    ];
                }

                $staticSeoHtml .= "    <script type=\"application/ld+json\">" . json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . "</script>\n";

                // Log para depuración
                Log::info('[GenerateStaticStationPages] Contenido de $staticSeoHtml (primeros 200 caracteres): ' . substr($staticSeoHtml, 0, 200));
                Log::info('[GenerateStaticStationPages] isset($staticSeoHtml) && !empty($staticSeoHtml): ' . (isset($staticSeoHtml) && !empty($staticSeoHtml) ? 'Sí' : 'No'));

                $htmlContent = View::make('detalles', [
                    'radio' => $radio,
                    'related' => $related,
                    'staticSeoHtml' => $staticSeoHtml // Pasar el HTML de SEO a la vista
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
