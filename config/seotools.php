<?php
/**
 * @see https://github.com/artesaos/seotools
 */

return [
    'inertia' => env('SEO_TOOLS_INERTIA', false),
    'meta' => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'        => 'Domiradios Forced Title Meta', // Usar nombre de la app
            'titleBefore'  => false,
            'description'  => 'Escucha las mejores emisoras de radio de República Dominicana en vivo. Directorio de emisoras dominicanas online.', // Descripción general
            'separator'    => ' - ',
            'keywords'     => ['radio', 'emisoras', 'dominicanas', 'online', 'en vivo', 'República Dominicana', 'Domiradios'],
            'canonical'    => false, // Deshabilitar la generación automática de canónica por defecto
            'robots'       => 'all', // O 'index, follow'
        ],
        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => 'Domiradios Forced Title OG', // Usar nombre de la app
            'description' => 'Escucha las mejores emisoras de radio de República Dominicana en vivo. Directorio de emisoras dominicanas online.', // Descripción general
            'url'         => false, // Deshabilitar la generación automática de og:url por defecto
            'type'        => 'WebSite', // Tipo general para el sitio
            'site_name'   => 'Domiradios Forced Site Name OG', // Nombre del sitio desde config
            'images'      => ['/img/domiradios-logo-og.png'], // Ruta relativa para la imagen OG por defecto
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card'        => 'summary_large_image',
            'site'        => '@domiradios', // Reemplazar con tu handle real o dejar config('app.name')
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => 'Over 9000 Thousand!', // set false to total remove
            'description' => 'For those who helped create the Genki Dama', // set false to total remove
            'url'         => false, // Set to null or 'full' to use Url::full(), set to 'current' to use Url::current(), set false to total remove
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
