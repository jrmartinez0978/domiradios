@extends('layouts.dark')

@section('title', 'Sobre Nosotros - Domiradios')
@section('meta_description', 'Conoce Domiradios, el directorio más completo de emisoras de radio dominicanas en vivo. Nuestra misión, equipo y compromiso con la radio dominicana.')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-12">
    {{-- About header --}}
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-extrabold mb-4">
            <span class="text-gradient">Sobre Domiradios</span>
        </h1>
        <p class="text-dark-300 text-lg max-w-2xl mx-auto">
            Conectando a los dominicanos con su radio, desde cualquier lugar del mundo.
        </p>
    </div>

    {{-- Mission --}}
    <section class="glass-card p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-100 mb-4 flex items-center">
            <i class="fas fa-bullseye text-accent-red mr-3"></i>
            Nuestra Misión
        </h2>
        <div class="text-dark-300 leading-relaxed space-y-4">
            <p>
                <strong class="text-gray-200">Domiradios</strong> nació con la misión de facilitar el acceso a la radio dominicana para todos.
                Creemos que la radio es un medio fundamental que conecta comunidades, preserva cultura y entretiene a millones de personas.
            </p>
            <p>
                Nuestro directorio reúne las emisoras de radio de República Dominicana en una plataforma moderna, rápida y gratuita.
                Desde merengue y bachata hasta noticias y deportes, ofrecemos acceso instantáneo a la programación que los dominicanos aman.
            </p>
        </div>
    </section>

    {{-- What we do --}}
    <section class="glass-card p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-100 mb-6 flex items-center">
            <i class="fas fa-headphones text-accent-red mr-3"></i>
            Lo Que Hacemos
        </h2>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                <h3 class="font-bold text-gray-200 mb-2"><i class="fas fa-broadcast-tower text-accent-red mr-2"></i>Directorio de Emisoras</h3>
                <p class="text-dark-300 text-sm">Mantenemos un catálogo actualizado de más de 30 emisoras dominicanas con información detallada de cada una.</p>
            </div>
            <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                <h3 class="font-bold text-gray-200 mb-2"><i class="fas fa-play-circle text-accent-red mr-2"></i>Streaming en Vivo</h3>
                <p class="text-dark-300 text-sm">Tecnología de streaming avanzada incluyendo HTML5 Audio y RTCStream para la mejor calidad y menor latencia.</p>
            </div>
            <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                <h3 class="font-bold text-gray-200 mb-2"><i class="fas fa-mobile-alt text-accent-red mr-2"></i>Multiplataforma</h3>
                <p class="text-dark-300 text-sm">Funciona en cualquier dispositivo: celulares, tablets, computadoras. Sin necesidad de instalar aplicaciones.</p>
            </div>
            <div class="bg-glass-white-10 rounded-xl p-5 border border-glass-border">
                <h3 class="font-bold text-gray-200 mb-2"><i class="fas fa-globe-americas text-accent-red mr-2"></i>Acceso Global</h3>
                <p class="text-dark-300 text-sm">Disponible en todo el mundo para los millones de dominicanos en el exterior que quieren mantener su conexión con la isla.</p>
            </div>
        </div>
    </section>

    {{-- E-E-A-T signals --}}
    <section class="glass-card p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-100 mb-4 flex items-center">
            <i class="fas fa-shield-alt text-accent-red mr-3"></i>
            Compromiso y Confianza
        </h2>
        <div class="text-dark-300 leading-relaxed space-y-4">
            <p>
                Domiradios opera desde Santo Domingo, República Dominicana. Trabajamos directamente con emisoras de radio para
                asegurar que los streams estén activos y la información sea precisa.
            </p>
            <p>
                Nos comprometemos a mantener un servicio gratuito, sin publicidad intrusiva, respetando la privacidad de nuestros usuarios.
                Puedes leer nuestra <a href="{{ route('privacidad') }}" class="text-accent-red hover:underline">Política de Privacidad</a>
                y nuestros <a href="{{ route('terminos') }}" class="text-accent-red hover:underline">Términos y Condiciones</a> para más detalles.
            </p>
        </div>
    </section>

    {{-- Contact CTA --}}
    <div class="text-center">
        <p class="text-dark-400 mb-4">¿Tienes una emisora que quieres agregar al directorio?</p>
        <a href="{{ route('contacto') }}" class="btn-primary inline-flex items-center">
            <i class="fas fa-broadcast-tower mr-2"></i> Envía tu emisora
        </a>
    </div>
</div>
@endsection
