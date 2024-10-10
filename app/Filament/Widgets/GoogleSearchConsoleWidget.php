<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Google_Client;
use Google_Service_Webmasters;
use Google_Service_Webmasters_SearchAnalyticsQueryRequest;
use Exception;

class GoogleSearchConsoleWidget extends Widget
{
    protected static string $heading = 'Google Search Console Data';
    protected static string $view = 'filament.widgets.google-search-console-widget';

    public function getData()
    {
        try {
            // Configurar el cliente de Google
            $client = new Google_Client();
            $client->setAuthConfig(storage_path('app/google/client_secret_541629762896-uq22ftu1t13c11uq996aqq05pbhp4g85.apps.googleusercontent.com.json'));
            $client->addScope(Google_Service_Webmasters::WEBMASTERS_READONLY);

            // Autenticación y obtención de datos de Search Console
            $service = new Google_Service_Webmasters($client);
            $siteUrl = "https://domiradios.com.do/"; // Reemplaza con tu dominio verificado

            // Solicitar datos de los últimos 7 días
            $request = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();
            $request->setStartDate(date('Y-m-d', strtotime('-7 days')));
            $request->setEndDate(date('Y-m-d'));
            $request->setDimensions(['query']);

            $response = $service->searchanalytics->query($siteUrl, $request);

            return $response ? $response->getRows() : [];
        } catch (Exception $e) {
            // Manejo de errores
            \Log::error('Error al obtener datos de Google Search Console: ' . $e->getMessage());
            return [];
        }
    }

    // Pasar los datos a la vista
    protected function getViewData(): array
    {
        return [
            'data' => $this->getData(),
        ];
    }
}
