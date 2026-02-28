@extends('errors::layout')

@section('title', 'Demasiadas solicitudes')
@section('code', '429')
@section('message', 'Demasiadas solicitudes')
@section('detail', 'Has realizado demasiadas solicitudes. Espera un momento e intenta de nuevo.')
