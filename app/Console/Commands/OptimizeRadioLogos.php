<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Radio;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Str;

class OptimizeRadioLogos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'radios:optimize-logos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimiza todas las imágenes de logos de radios en WebP para carga rápida';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $radios = Radio::whereNotNull('img')->where('img', '!=', '')->get();
        $optimizedCount = 0;
        $skippedCount = 0;
        $errorCount = 0;

        $this->info('Procesando ' . $radios->count() . ' radios...');
        foreach ($radios as $radio) {
            $originalPath = $radio->img;
            if (!Storage::disk('public')->exists($originalPath)) {
                $this->warn('No existe la imagen original para radio ' . $radio->id . ': ' . $originalPath);
                $errorCount++;
                continue;
            }

            $imageName = pathinfo($originalPath, PATHINFO_FILENAME) . '.webp';
            $optimizedDirPath = 'radios/optimized';
            $optimizedFullPath = $optimizedDirPath . '/' . $imageName;

            $needsOptimization = !Storage::disk('public')->exists($optimizedFullPath) ||
                Storage::disk('public')->lastModified($originalPath) > Storage::disk('public')->lastModified($optimizedFullPath);

            if (!$needsOptimization) {
                $skippedCount++;
                continue;
            }

            try {
                if (!Storage::disk('public')->exists($optimizedDirPath)) {
                    Storage::disk('public')->makeDirectory($optimizedDirPath);
                }
                $imageContent = Storage::disk('public')->get($originalPath);
                $img = Image::read($imageContent);
                $img->resize(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $encodedImage = $img->encode(new WebpEncoder(quality: 90));
                Storage::disk('public')->put($optimizedFullPath, (string) $encodedImage);
                $optimizedCount++;
                $this->info('Optimizado: ' . $originalPath . ' → ' . $optimizedFullPath);
            } catch (\Exception $e) {
                $this->error('Error optimizando imagen para radio ' . $radio->id . ': ' . $e->getMessage());
                $errorCount++;
            }
        }
        $this->info("\nResumen: $optimizedCount optimizadas, $skippedCount ya estaban optimizadas, $errorCount con error.");
    }
}
