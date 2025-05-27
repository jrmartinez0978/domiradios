@extends('layouts.app')

@section('content')
<section class="container max-w-6xl py-16 grid gap-8 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
    @foreach($emisoras as $emisora)
    <article class="bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-300 flex flex-col">
        <img src="{{ asset('storage/'.$emisora->img) }}" alt="Logo {{ $emisora->nombre }}" class="w-full aspect-square object-contain rounded-t-2xl p-4" />
        <div class="p-5 flex-1">
            <h3 class="font-semibold text-xl text-brand-blue">{{ $emisora->nombre }}</h3>
            <p class="text-xs text-slate-500">
                @if($emisora->frecuencia)
                <span class="block">Frecuencia: {{ $emisora->frecuencia }}</span>
                @endif
                @if($emisora->ciudad)
                <span class="block">Ciudad: {{ $emisora->ciudad->nombre }}</span>
                @endif
                @if($emisora->genero)
                <span class="block">Género: {{ $emisora->genero }}</span>
                @endif
            </p>
        </div>
        <a href="{{ route('emisoras.show', $emisora->slug) }}" class="mx-5 mb-5 mt-0 inline-block text-center bg-emerald-600 text-white font-semibold rounded-lg py-2 hover:bg-emerald-700">
            Escuchar
        </a>
    </article>
    @endforeach
</section>
@endsection
