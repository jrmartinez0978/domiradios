@extends('layouts.dark')

@section('title', 'Domiradios - Escucha Radios Dominicanas Online Gratis ' . date('Y'))

@section('meta_description', 'Escucha más de 30 emisoras dominicanas GRATIS en vivo: Z101, La Mega, Alofoke, Latina 104 y más. Sin descargas ni registro. Compatible iPhone y Android.')

@section('meta_keywords', 'radio dominicana online, emisoras dominicanas gratis, radio en vivo RD, Z101 online, La Mega online, radio Santo Domingo, escuchar radio dominicana, streaming radio dominicana')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <h1 class="text-3xl md:text-4xl font-extrabold text-primary mt-6 mb-2">Emisoras de Radio Dominicanas en Vivo</h1>

        {{-- Search Card (floating, like habidominicana) --}}
        <div class="mt-2 relative z-10">
            <livewire:radio-index />
        </div>

        {{-- SEO Content --}}
        <div class="mt-16 space-y-16">
            {{-- Sobre Domiradios --}}
            <section>
                <h2 class="text-2xl font-bold text-primary mb-6">
                    Escucha las Mejores Emisoras de Radio Dominicanas Online
                </h2>
                <div class="text-gray-600 leading-relaxed space-y-4">
                    <p class="text-lg">
                        <strong class="text-gray-800">Domiradios</strong> es el directorio más completo y actualizado de <strong class="text-gray-800">emisoras de radio dominicanas en vivo</strong>.
                        Ofrecemos acceso gratuito e inmediato a todas las estaciones de radio de República Dominicana, desde las más populares
                        hasta las emisoras locales de cada provincia.
                    </p>
                    <p>
                        Nuestra plataforma te permite <strong class="text-gray-800">escuchar radio online gratis</strong> sin necesidad de descargas, registros ni aplicaciones.
                        Solo necesitas conexión a internet para disfrutar de tu emisora favorita desde cualquier dispositivo: computadora, celular, tablet
                        o smart TV. Con más de 30 emisoras disponibles, encontrarás estaciones de <strong class="text-gray-800">merengue, bachata, salsa, reggaetón, noticias,
                        deportes y música cristiana</strong>.
                    </p>
                    <p>
                        Ya sea que busques <strong class="text-gray-800">radios de Santo Domingo</strong>, Santiago, La Vega, San Pedro de Macorís o cualquier otra ciudad
                        dominicana, Domiradios te conecta con la programación que más te gusta. Todas nuestras transmisiones son en <strong class="text-gray-800">tiempo real
                        y alta calidad</strong>, garantizando la mejor experiencia de audio streaming.
                    </p>
                </div>
            </section>

            {{-- Por qué escuchar --}}
            <section>
                <h2 class="text-2xl font-bold text-primary mb-6">
                    ¿Por Qué Escuchar Radio Online en Domiradios?
                </h2>
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-check-circle text-emerald-500 mr-3"></i>
                            100% Gratis y Sin Registro
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Todas las emisoras están disponibles de forma completamente gratuita. No necesitas crear cuenta ni proporcionar datos personales.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-mobile-alt text-blue-500 mr-3"></i>
                            Compatible con Todos los Dispositivos
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Escucha desde tu celular Android, iPhone, iPad, computadora o tablet. Funciona en cualquier navegador web moderno.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-bolt text-amber-500 mr-3"></i>
                            Transmisión en Vivo 24/7
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Todas las radios transmiten en tiempo real las 24 horas. Nunca te pierdas tus programas favoritos o noticias de última hora.
                        </p>
                    </div>
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                            <i class="fas fa-music text-primary mr-3"></i>
                            Variedad de Géneros Musicales
                        </h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Desde merengue típico y bachata hasta reggaetón urbano, salsa, baladas, rock, música cristiana y más.
                        </p>
                    </div>
                </div>
            </section>

            {{-- FAQ Section --}}
            <section>
                <h2 class="text-2xl font-bold text-primary mb-6">
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
            <section>
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="inline-block px-3 py-1 bg-primary text-white text-xs font-semibold rounded-full mb-2">BLOG</span>
                        <h2 class="text-2xl font-bold text-primary">Últimas Noticias</h2>
                        <p class="text-gray-500 mt-1">Lo más reciente de la radio dominicana</p>
                    </div>
                    <a href="{{ route('blog.index') }}" class="btn-outline text-sm">
                        Ver todos
                    </a>
                </div>
                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($latestBlogPosts as $post)
                    <article class="bg-white rounded-2xl border border-surface-300 shadow-sm overflow-hidden group hover:shadow-lg hover:border-primary-200 transition-all duration-300 hover:-translate-y-1">
                        @if($post->featured_image)
                        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden aspect-[16/10]">
                            <img src="{{ $post->optimized_featured_image_url }}" alt="{{ $post->featured_image_alt ?? $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </a>
                        @else
                        <a href="{{ route('blog.show', $post->slug) }}" class="block aspect-[16/10] flex items-center justify-center" style="background:linear-gradient(135deg, rgba(0,80,70,0.1), rgba(0,80,70,0.05), rgba(0,80,70,0.15))">
                            <i class="fas fa-newspaper text-3xl" style="color:rgba(0,80,70,0.25)"></i>
                        </a>
                        @endif
                        <div class="p-5">
                            @if($post->category)
                            <a href="{{ route('blog.category', $post->category) }}" class="inline-block px-2.5 py-0.5 bg-primary-50 text-primary text-xs font-semibold rounded-full mb-3">
                                {{ $post->category }}
                            </a>
                            @endif
                            <h3 class="text-base font-bold text-gray-800 mb-2 line-clamp-2">
                                <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-primary transition-colors">{{ $post->title }}</a>
                            </h3>
                            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $post->excerpt }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-400">
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
            <section>
                <h2 class="text-2xl font-bold text-primary mb-6">
                    Géneros Musicales Más Escuchados
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-primary mb-2">Urbano y Reggaetón</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Las emisoras urbanas dominan el panorama musical dominicano con reggaetón, dembow y trap. Estaciones como Alofoke Radio Show y La Kalle lideran este género.</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-primary mb-2">Merengue y Bachata</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Los ritmos tradicionales dominicanos siguen siendo los favoritos. Emisoras especializadas transmiten lo mejor del merengue típico y bachata romántica las 24 horas.</p>
                    </div>
                    <div class="bg-white rounded-2xl border border-surface-300 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-primary mb-2">Noticias y Deportes</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">Mantente informado con las principales emisoras noticiosas como CDN Radio y Z101. Cobertura en vivo de noticias nacionales, internacionales y deportes.</p>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
