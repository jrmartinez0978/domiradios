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
            'title'        => 'Domiradios',
            'titleBefore'  => false,
            'description'  => 'Escucha las mejores emisoras de radio de República Dominicana en vivo. Directorio actualizado de radios dominicanas online.',
            'separator'    => ' | ',
            'keywords'     => ['radio', 'emisoras', 'dominicanas', 'online', 'en vivo', 'República Dominicana', 'Domiradios', 'streaming', 'música'],
            'canonical'    => 'current',
            'robots'       => 'index, follow',
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
            'title'       => 'Domiradios',
            'description' => 'Escucha las mejores emisoras de radio de República Dominicana en vivo. Directorio actualizado de radios dominicanas online.',
            'url'         => 'current',
            'type'        => 'website',
            'site_name'   => 'Domiradios',
            'images'      => [env('APP_URL', 'https://domiradios.com.do') . '/img/domiradios-logo-og.jpg'],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
            'card'        => 'summary_large_image',
            'site'        => '@Domiradios',
            'images'      => [env('APP_URL', 'https://domiradios.com.do') . '/img/domiradios-logo-og.jpg'],
        ],
    ],
    'json-ld' => [
        /*
         * The default configurations to be used by the json-ld generator.
         */
        'defaults' => [
            'title'       => 'Domiradios',
            'description' => 'Escucha las mejores emisoras de radio de República Dominicana en vivo. Directorio actualizado de radios dominicanas online.',
            'url'         => 'current',
            'type'        => 'WebPage',
            'images'      => [env('APP_URL', 'https://domiradios.com.do') . '/img/domiradios-logo-og.jpg'],
        ],
    ],
];
