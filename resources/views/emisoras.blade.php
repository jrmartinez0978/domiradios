@extends('layouts.dark')

@section('title', 'Domiradios - Escucha Radios Dominicanas Online Gratis ' . date('Y'))

@section('meta_description', 'Escucha más de 30 emisoras dominicanas GRATIS en vivo: Z101, La Mega, Alofoke, Latina 104 y más. Sin descargas ni registro. Compatible iPhone y Android.')

@section('meta_keywords', 'radio dominicana online, emisoras dominicanas gratis, radio en vivo RD, Z101 online, La Mega online, radio Santo Domingo, escuchar radio dominicana, streaming radio dominicana')

@section('hero')
<div class="relative overflow-hidden py-20 md:py-28">
    {{-- Animated gradient background --}}
    <div class="absolute inset-0 bg-gradient-to-br from-dark-950 via-dark-900 to-dark-950"></div>
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-0 -left-1/4 w-1/2 h-1/2 bg-accent-red/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 -right-1/4 w-1/2 h-1/2 bg-brand-blue/20 rounded-full blur-[120px]"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-5xl md:text-6xl xl:text-7xl font-extrabold mb-4 animate-fade-in">
            <span class="text-gradient">Domiradios</span>
        </h1>
        <p class="text-xl md:text-2xl text-dark-300 max-w-2xl mx-auto mb-8 animate-slide-up">
            Directorio dominicano de emisoras de radio en vivo
        </p>
        {{-- Stats --}}
        <div class="flex items-center justify-center gap-8 text-sm text-dark-400 animate-slide-up">
            <div class="flex items-center gap-2">
                <i class="fas fa-broadcast-tower text-accent-red"></i>
                <span>30+ emisoras</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-accent-red"></i>
                <span>Todas las ciudades</span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-wifi text-accent-red"></i>
                <span>24/7 en vivo</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <livewire:radio-index />

        {{-- SEO Content Section --}}
        <div class="mt-12 space-y-8">
            {{-- Sobre Domiradios --}}
            <section class="glass-card p-8">
                <h2 class="text-2xl font-bold text-gray-100 mb-6 flex items-center">
                    <i class="fas fa-microphone-alt text-accent-red mr-3"></i>
                    Escucha las Mejores Emisoras de Radio Dominicanas Online
                </h2>
                <div class="text-dark-300 leading-relaxed space-y-4">
                    <p class="text-lg">
                        <strong class="text-gray-100">Domiradios</strong> es el directorio más completo y actualizado de <strong class="text-gray-200">emisoras de radio dominicanas en vivo</strong>.
                        Ofrecemos acceso gratuito e inmediato a todas las estaciones de radio de República Dominicana, desde las más populares
                        hasta las emisoras locales de cada provincia.
                    </p>
                    <p>
                        Nuestra plataforma te permite <strong class="text-gray-200">escuchar radio online gratis</strong> sin necesidad de descargas, registros ni aplicaciones.
                        Solo necesitas conexión a internet para disfrutar de tu emisora favorita desde cualquier dispositivo: computadora, celular, tablet
                        o smart TV. Con más de 30 emisoras disponibles, encontrarás estaciones de <strong class="text-gray-200">merengue, bachata, salsa, reggaetón, noticias,
                        deportes y música cristiana</strong>.
                    </p>
                    <p>
                        Ya sea que busques <strong class="text-gray-200">radios de Santo Domingo</strong>, Santiago, La Vega, San Pedro de Macorís o cualquier otra ciudad
                        dominicana, Domiradios te conecta con la programación que más te gusta. Todas nuestras transmisiones son en <strong class="text-gray-200">tiempo real
                        y alta calidad</strong>, garantizando la mejor experiencia de audio streaming.
                    </p>
                </div>
            </section>

            {{-- Por qué escuchar en Domiradios --}}
            <section class="glass-card p-8">
                <h2 class="text-2xl font-bold text-gray-100 mb-6">
                    ¿Por Qué Escuchar Radio Online en Domiradios?
                </h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                        <h3 class="text-lg font-bold text-gray-100 mb-2 flex items-center">
                            <i class="fas fa-check-circle text-accent-green mr-2"></i>
                            100% Gratis y Sin Registro
                        </h3>
                        <p class="text-dark-300 text-sm">
                            Todas las emisoras están disponibles de forma completamente gratuita. No necesitas crear cuenta ni proporcionar
                            datos personales. Solo haz clic en "Reproducir" y disfruta.
                        </p>
                    </div>
                    <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                        <h3 class="text-lg font-bold text-gray-100 mb-2 flex items-center">
                            <i class="fas fa-mobile-alt text-accent-blue mr-2"></i>
                            Compatible con Todos los Dispositivos
                        </h3>
                        <p class="text-dark-300 text-sm">
                            Escucha desde tu celular Android, iPhone, iPad, computadora o tablet. Funciona en cualquier
                            navegador web moderno sin necesidad de instalar apps.
                        </p>
                    </div>
                    <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                        <h3 class="text-lg font-bold text-gray-100 mb-2 flex items-center">
                            <i class="fas fa-bolt text-accent-amber mr-2"></i>
                            Transmisión en Vivo 24/7
                        </h3>
                        <p class="text-dark-300 text-sm">
                            Todas las radios transmiten en tiempo real las 24 horas del día. Nunca te pierdas
                            tus programas favoritos, noticias de última hora o la música que más te gusta.
                        </p>
                    </div>
                    <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                        <h3 class="text-lg font-bold text-gray-100 mb-2 flex items-center">
                            <i class="fas fa-music text-accent-red mr-2"></i>
                            Variedad de Géneros Musicales
                        </h3>
                        <p class="text-dark-300 text-sm">
                            Desde merengue típico y bachata hasta reggaetón urbano, salsa, baladas, rock, música cristiana y más.
                            También emisoras especializadas en noticias, deportes y programación hablada.
                        </p>
                    </div>
                </div>
            </section>

            {{-- FAQ Section --}}
            <section class="glass-card p-8">
                <h2 class="text-2xl font-bold text-gray-100 mb-6 flex items-center">
                    <i class="fas fa-question-circle text-accent-red mr-3"></i>
                    Preguntas Frecuentes sobre Radio Online Dominicana
                </h2>
                @php
                $homepageFaqs = [
                    ['question' => '¿Cómo puedo escuchar radio dominicana online gratis?', 'answer' => 'Es muy fácil. Solo busca tu emisora favorita en el directorio de Domiradios, haz clic en el botón "Reproducir" y comenzarás a escuchar inmediatamente. No necesitas descargar ninguna aplicación ni registrarte. El servicio es 100% gratuito.'],
                    ['question' => '¿Qué emisoras de Santo Domingo puedo escuchar?', 'answer' => 'En Domiradios encontrarás todas las principales emisoras de Santo Domingo como Z101, La Mega, La Nueva, Latina 104, Romantica, CDN Radio, La Z Digital y muchas más. Puedes filtrar por ciudad para ver solo las estaciones de la capital.'],
                    ['question' => '¿Funciona en iPhone y Android?', 'answer' => 'Sí, Domiradios funciona perfectamente en todos los celulares modernos. Es compatible con iPhone (iOS), Android, tablets y cualquier dispositivo con navegador web. No necesitas instalar ninguna app.'],
                    ['question' => '¿Puedo escuchar desde fuera de República Dominicana?', 'answer' => 'Por supuesto. Domiradios está disponible en todo el mundo. Miles de dominicanos en el exterior utilizan nuestra plataforma para mantenerse conectados con la música, noticias y cultura de su país.'],
                    ['question' => '¿Cuánto consume de datos móviles escuchar radio online?', 'answer' => 'Escuchar radio online consume aproximadamente 50-70 MB por hora en calidad estándar. Te recomendamos usar WiFi cuando sea posible, pero el consumo de datos es razonable.']
                ];
                @endphp
                <x-faq-schema :faqs="$homepageFaqs" />
            </section>

            {{-- Blog Widget --}}
            @if(isset($latestBlogPosts) && $latestBlogPosts->count() > 0)
            <section class="glass-card p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-100 flex items-center">
                        <i class="fas fa-newspaper text-accent-red mr-3"></i>
                        Últimas Noticias del Blog
                    </h2>
                    <a href="{{ route('blog.index') }}" class="text-accent-red hover:text-red-400 font-semibold flex items-center text-sm transition-colors">
                        Ver todos <i class="fas fa-chevron-right ml-1.5 text-xs"></i>
                    </a>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($latestBlogPosts as $post)
                    <article class="glass-card-hover overflow-hidden">
                        @if($post->featured_image)
                        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden">
                            <img src="{{ $post->optimized_featured_image_url }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </a>
                        @else
                        <a href="{{ route('blog.show', $post->slug) }}" class="block bg-gradient-to-br from-dark-700 to-dark-800 h-44 flex items-center justify-center">
                            <i class="fas fa-newspaper text-dark-500 text-3xl"></i>
                        </a>
                        @endif
                        <div class="p-5">
                            @if($post->category)
                            <a href="{{ route('blog.category', $post->category) }}" class="inline-block px-2.5 py-0.5 bg-accent-red/20 text-red-300 text-xs font-semibold rounded-full mb-3 hover:bg-accent-red/30 transition-colors">
                                {{ $post->category }}
                            </a>
                            @endif
                            <h3 class="text-base font-bold text-gray-100 mb-2 line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-accent-red transition-colors">{{ $post->title }}</a>
                            </h3>
                            <p class="text-dark-400 text-sm mb-3 line-clamp-2">{{ $post->excerpt }}</p>
                            <div class="flex items-center justify-between text-xs text-dark-500">
                                <span><i class="far fa-calendar mr-1"></i>{{ $post->published_at->diffForHumans() }}</span>
                                <span><i class="far fa-clock mr-1"></i>{{ $post->reading_time }} min</span>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Géneros --}}
            <section class="glass-card p-8">
                <h2 class="text-2xl font-bold text-gray-100 mb-6">
                    Géneros Musicales Más Escuchados
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-bold text-accent-red mb-2">Urbano y Reggaetón</h3>
                        <p class="text-dark-300 text-sm">Las emisoras urbanas dominan el panorama musical dominicano con reggaetón, dembow y trap. Estaciones como Alofoke Radio Show y La Kalle lideran este género.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-accent-red mb-2">Merengue y Bachata</h3>
                        <p class="text-dark-300 text-sm">Los ritmos tradicionales dominicanos siguen siendo los favoritos. Emisoras especializadas transmiten lo mejor del merengue típico y bachata romántica las 24 horas.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-accent-red mb-2">Noticias y Deportes</h3>
                        <p class="text-dark-300 text-sm">Mantente informado con las principales emisoras noticiosas como CDN Radio y Z101. Cobertura en vivo de noticias nacionales, internacionales y deportes.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
