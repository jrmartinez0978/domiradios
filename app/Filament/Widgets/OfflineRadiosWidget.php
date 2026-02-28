<?php

namespace App\Filament\Widgets;

use App\Models\Radio;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class OfflineRadiosWidget extends BaseWidget
{
    protected static ?int $sort = 99;
    protected int | string | array $columnSpan = 12;
    protected static ?string $pollingInterval = '60s';

    public function table(Table $table): Table
    {
        return $table
            ->heading('Emisoras con Streams Caídos')
            ->description('Emisoras que presentan problemas de conexión en sus streams')
            ->query(
                Radio::query()
                    ->where('is_stream_active', false)
                    ->orderByDesc('stream_failure_count')
                    ->orderByDesc('last_stream_failure')
                    ->limit(15)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('img')
                    ->label('Logo')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Emisora')
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('link_radio')
                    ->label('URL del Stream')
                    ->limit(50)
                    ->searchable()
                    ->url(fn (Radio $record): string => $record->link_radio)
                    ->openUrlInNewTab()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('last_stream_failure')
                    ->label('Última caída')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->badge()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('stream_failure_count')
                    ->label('Fallos')
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state > 5 => 'danger',
                        $state > 2 => 'warning',
                        default => 'gray',
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('checkStream')
                    ->label('Verificar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->action(function (Radio $record) {
                        try {
                            $url = $record->link_radio;

                            // Verificación con cURL (HEAD request)
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                            curl_setopt($ch, CURLOPT_NOBODY, true);
                            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

                            curl_exec($ch);
                            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            curl_close($ch);

                            $isActive = ($httpCode === 200);

                            // Si HEAD falla, intentar con GET
                            if (!$isActive) {
                                $ch = curl_init();
                                curl_setopt($ch, CURLOPT_URL, $url);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

                                curl_exec($ch);
                                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                                curl_close($ch);

                                $isActive = ($httpCode === 200);
                            }

                            // Actualizar el estado del radio
                            if ($isActive) {
                                $record->update([
                                    'is_stream_active' => true,
                                    'stream_failure_count' => 0,
                                    'last_stream_check' => now(),
                                ]);

                                Notification::make()
                                    ->success()
                                    ->title('Stream activo')
                                    ->body("El stream de '{$record->name}' está funcionando correctamente.")
                                    ->send();
                            } else {
                                $newFailureCount = $record->stream_failure_count + 1;

                                $record->update([
                                    'is_stream_active' => false,
                                    'stream_failure_count' => $newFailureCount,
                                    'last_stream_check' => now(),
                                    'last_stream_failure' => now(),
                                ]);

                                Notification::make()
                                    ->warning()
                                    ->title('Stream inactivo')
                                    ->body("El stream de '{$record->name}' sigue inactivo. Fallos: {$newFailureCount}")
                                    ->send();
                            }

                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Error de verificación')
                                ->body('Error: ' . $e->getMessage())
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('editRadio')
                    ->label('Editar')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Radio $record): string => '/panel/radios/'.$record->id.'/edit')
                    ->color('warning'),
                Tables\Actions\Action::make('resetStream')
                    ->label('Restablecer')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function (Radio $record) {
                        $record->update([
                            'is_stream_active' => true,
                            'stream_failure_count' => 0,
                            'last_stream_check' => now(),
                        ]);

                        Notification::make()
                            ->success()
                            ->title('Stream restablecido')
                            ->body("El stream de '{$record->name}' ha sido restablecido correctamente.")
                            ->send();
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('checkAll')
                    ->label('Verificar todos')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Verificar streams offline')
                    ->modalDescription('Esto verificará solo las emisoras que están marcadas como offline para ver si han vuelto a estar activas.')
                    ->modalSubmitActionLabel('Verificar')
                    ->action(fn () => $this->checkAllStreams()),
                Tables\Actions\Action::make('resetAll')
                    ->label('Restablecer todos')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Restablecer todos los streams')
                    ->modalDescription('¿Estás seguro de que deseas restablecer el estado de todos los streams marcados como caídos?')
                    ->modalSubmitActionLabel('Sí, restablecer')
                    ->action(fn () => $this->resetAllStreams()),
            ])
            ->emptyStateHeading('¡Todas las emisoras están funcionando correctamente!')
            ->emptyStateDescription('No hay emisoras con problemas de conexión en este momento.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }

    /**
     * Verifica solo los streams que están marcados como caídos
     */
    public function checkAllStreams(): void
    {
        try {
            // Obtener solo las radios inactivas para verificar
            $offlineRadios = Radio::where('is_stream_active', false)->get();

            if ($offlineRadios->isEmpty()) {
                Notification::make()
                    ->info()
                    ->title('Sin radios offline')
                    ->body('No hay radios offline para verificar.')
                    ->send();
                return;
            }

            $reactivated = 0;
            $stillOffline = 0;

            foreach ($offlineRadios as $radio) {
                $url = $radio->link_radio;

                if (empty($url)) {
                    continue;
                }

                // Verificación rápida con cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 3); // Timeout corto para rapidez
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

                curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                $isActive = ($httpCode === 200);

                if ($isActive) {
                    $radio->update([
                        'is_stream_active' => true,
                        'stream_failure_count' => 0,
                        'last_stream_check' => now(),
                    ]);
                    $reactivated++;
                } else {
                    $radio->update([
                        'stream_failure_count' => $radio->stream_failure_count + 1,
                        'last_stream_check' => now(),
                        'last_stream_failure' => now(),
                    ]);
                    $stillOffline++;
                }
            }

            Notification::make()
                ->success()
                ->title('Verificación completada')
                ->body("Reactivadas: {$reactivated} | Aún offline: {$stillOffline}")
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error de verificación')
                ->body('Error: ' . $e->getMessage())
                ->send();
        }
    }

    /**
     * Restablece todos los streams marcados como caídos
     */
    public function resetAllStreams(): void
    {
        try {
            $count = Radio::where('is_stream_active', false)
                ->update([
                    'is_stream_active' => true,
                    'stream_failure_count' => 0,
                    'last_stream_check' => now()
                ]);

            Notification::make()
                ->success()
                ->title('Streams restablecidos')
                ->body("Se han restablecido {$count} streams correctamente.")
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('Error al restablecer: ' . $e->getMessage())
                ->send();
        }
    }
}
