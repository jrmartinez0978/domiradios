@extends('layouts.app')

@section('title', 'Domiradios - Emisoras de República Dominicana')

@section('hero', true)

@section('content')
    <div class="container max-w-7xl mx-auto px-4">
        <livewire:radio-index />
    </div>
@endsection
