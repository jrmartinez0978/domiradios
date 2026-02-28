@extends('layouts.app')

@section('title', 'Domiradios - Escucha Radios Dominicanas Online Gratis ' . date('Y'))

@section('meta_description', 'Escucha más de 30 emisoras dominicanas GRATIS en vivo: Z101, La Mega, Alofoke, Latina 104 y más. Sin descargas ni registro. Compatible iPhone y Android.')

@section('meta_keywords', 'radio dominicana online, emisoras dominicanas gratis, radio en vivo RD, Z101 online, La Mega online, radio Santo Domingo, escuchar radio dominicana, streaming radio dominicana')

@section('hero', true)

@section('content')
    <div class="container max-w-7xl mx-auto px-4">
        <livewire:radio-index />

        {{-- SEO Content Section - 2025 Optimization --}}
        <div class="mt-12 space-y-8">
            {{-- Sobre Domiradios --}}
            <section class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                    Escucha las Mejores Emisoras de Radio Dominicanas Online
                </h2>
                <div class="prose max-w-none text-gray-700 leading-relaxed space-y-4">
                    <p class="text-lg">
                        <strong>Domiradios</strong> es el directorio más completo y actualizado de <strong>emisoras de radio dominicanas en vivo</strong>.
                        Ofrecemos acceso gratuito e inmediato a todas las estaciones de radio de República Dominicana, desde las más populares
                        hasta las emisoras locales de cada provincia.
                    </p>
                    <p>
                        Nuestra plataforma te permite <strong>escuchar radio online gratis</strong> sin necesidad de descargas, registros ni aplicaciones.
                        Solo necesitas conexión a internet para disfrutar de tu emisora favorita desde cualquier dispositivo: computadora, celular, tablet
                        o smart TV. Con más de 30 emisoras disponibles, encontrarás estaciones de <strong>merengue, bachata, salsa, reggaetón, noticias,
                        deportes y música cristiana</strong>.
                    </p>
                    <p>
                        Ya sea que busques <strong>radios de Santo Domingo</strong>, Santiago, La Vega, San Pedro de Macorís o cualquier otra ciudad
                        dominicana, Domiradios te conecta con la programación que más te gusta. Todas nuestras transmisiones son en <strong>tiempo real
                        y alta calidad</strong>, garantizando la mejor experiencia de audio streaming.
                    </p>
                </div>
            </section>

            {{-- Por qué escuchar en Domiradios --}}
            <section class="bg-gradient-to-br from-brand-blue/5 to-brand-red/5 rounded-xl p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    ¿Por Qué Escuchar Radio Online en Domiradios?
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-xl font-bold text-brand-blue mb-3 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            100% Gratis y Sin Registro
                        </h3>
                        <p class="text-gray-700">
                            Todas las emisoras están disponibles de forma completamente gratuita. No necesitas crear cuenta ni proporcionar
                            datos personales. Solo haz clic en "Reproducir" y disfruta.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-xl font-bold text-brand-blue mb-3 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Compatible con Todos los Dispositivos
                        </h3>
                        <p class="text-gray-700">
                            Escucha desde tu celular Android, iPhone, iPad, computadora o tablet. Nuestra plataforma funciona en cualquier
                            navegador web moderno sin necesidad de instalar apps.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-xl font-bold text-brand-blue mb-3 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Transmisión en Vivo 24/7
                        </h3>
                        <p class="text-gray-700">
                            Todas las radios transmiten en tiempo real las 24 horas del día, 7 días a la semana. Nunca te pierdas
                            tus programas favoritos, noticias de última hora o la música que más te gusta.
                        </p>
                    </div>

                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-xl font-bold text-brand-blue mb-3 flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                            </svg>
                            Variedad de Géneros Musicales
                        </h3>
                        <p class="text-gray-700">
                            Desde merengue típico y bachata hasta reggaetón urbano, salsa, baladas, rock, música cristiana y más.
                            También emisoras especializadas en noticias, deportes y programación hablada.
                        </p>
                    </div>
                </div>
            </section>

            {{-- FAQ Section para Homepage --}}
            <section class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Preguntas Frecuentes sobre Radio Online Dominicana
                </h2>
                @php
                $homepageFaqs = [
                    [
                        'question' => '¿Cómo puedo escuchar radio dominicana online gratis?',
                        'answer' => 'Es muy fácil. Solo busca tu emisora favorita en el directorio de Domiradios, haz clic en el botón "Reproducir" y comenzarás a escuchar inmediatamente. No necesitas descargar ninguna aplicación ni registrarte. El servicio es 100% gratuito.'
                    ],
                    [
                        'question' => '¿Qué emisoras de Santo Domingo puedo escuchar?',
                        'answer' => 'En Domiradios encontrarás todas las principales emisoras de Santo Domingo como Z101, La Mega, La Nueva, Latina 104, Romantica, CDN Radio, La Z Digital y muchas más. Puedes filtrar por ciudad para ver solo las estaciones de la capital.'
                    ],
                    [
                        'question' => '¿Funciona en iPhone y Android?',
                        'answer' => 'Sí, Domiradios funciona perfectamente en todos los celulares modernos. Es compatible con iPhone (iOS), Android, tablets y cualquier dispositivo con navegador web. No necesitas instalar ninguna app, simplemente accede desde tu navegador móvil.'
                    ],
                    [
                        'question' => '¿Puedo escuchar desde fuera de República Dominicana?',
                        'answer' => 'Por supuesto. Domiradios está disponible en todo el mundo. Miles de dominicanos en el exterior utilizan nuestra plataforma para mantenerse conectados con la música, noticias y cultura de su país desde Estados Unidos, España, Puerto Rico y otros países.'
                    ],
                    [
                        'question' => '¿Cuánto consume de datos móviles escuchar radio online?',
                        'answer' => 'Escuchar radio online consume aproximadamente 50-70 MB por hora en calidad estándar. Te recomendamos usar WiFi cuando sea posible, pero el consumo de datos es bastante razonable si usas tu plan móvil.'
                    ]
                ];
                @endphp
                <x-faq-schema :faqs="$homepageFaqs" />
            </section>

            {{-- Widget del Blog --}}
            @if(isset($latestBlogPosts) && $latestBlogPosts->count() > 0)
            <section class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-md p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        Últimas Noticias del Blog
                    </h2>
                    <a href="{{ route('blog.index') }}" class="text-brand-blue hover:text-brand-red font-semibold flex items-center transition-colors">
                        Ver todos
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($latestBlogPosts as $post)
                        <article class="bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group">
                            @if($post->featured_image)
                                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden">
                                    <img
                                        src="{{ $post->optimized_featured_image_url }}"
                                        alt="{{ $post->featured_image_alt ?? $post->title }}"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    >
                                </a>
                            @else
                                <a href="{{ route('blog.show', $post->slug) }}" class="block bg-gradient-to-br from-brand-blue to-brand-red h-48 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </a>
                            @endif

                            <div class="p-5">
                                @if($post->category)
                                    <a href="{{ route('blog.category', $post->category) }}"
                                       class="inline-block px-3 py-1 bg-brand-blue text-white text-xs font-semibold rounded-full mb-3 hover:bg-brand-red transition-colors">
                                        {{ $post->category }}
                                    </a>
                                @endif

                                <h3 class="text-lg font-bold mb-2 line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-brand-blue transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">
                                    {{ $post->excerpt }}
                                </p>

                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $post->published_at->diffForHumans() }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                        {{ $post->reading_time }} min
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-6 text-center">
                    <a href="{{ route('blog.index') }}"
                       class="inline-flex items-center px-6 py-3 bg-brand-blue text-white font-semibold rounded-lg hover:bg-brand-red transition-colors shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Explorar Todo el Blog
                    </a>
                </div>
            </section>
            @endif

            {{-- Emisoras Populares por Género --}}
            <section class="bg-white rounded-xl shadow-md p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">
                    Géneros Musicales Más Escuchados
                </h2>
                <div class="grid md:grid-cols-3 gap-6 text-gray-700">
                    <div>
                        <h3 class="text-xl font-bold text-brand-red mb-3">Urbano y Reggaetón</h3>
                        <p>Las emisoras urbanas dominan el panorama musical dominicano con reggaetón, dembow y trap. Estaciones como Alofoke Radio Show y La Kalle lideran este género con los éxitos más recientes.</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-brand-red mb-3">Merengue y Bachata</h3>
                        <p>Los ritmos tradicionales dominicanos siguen siendo los favoritos. Emisoras especializadas transmiten lo mejor del merengue típico, merengue de calle y bachata romántica las 24 horas.</p>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-brand-red mb-3">Noticias y Deportes</h3>
                        <p>Mantente informado con las principales emisoras noticiosas como CDN Radio y Z101. Cobertura en vivo de noticias nacionales, internacionales y deportes dominicanos.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
