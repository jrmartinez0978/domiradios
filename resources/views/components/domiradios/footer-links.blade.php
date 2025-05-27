<div>
  <h4 class="font-semibold mb-3 text-lg">Navegación</h4>
  <ul class="space-y-1">
    <li><a class="hover:text-brand-red" href="{{ url('/') }}">Inicio</a></li>
    <li><a class="hover:text-brand-red" href="{{ route('ciudades.index') }}">Por Ciudad</a></li>
    <li><a class="hover:text-brand-red" href="{{ route('favoritos') }}">Mis Favoritos</a></li>
  </ul>
</div>
<div>
  <h4 class="font-semibold mb-3 text-lg">Legal y Contacto</h4>
  <ul class="space-y-1">
    <li><a class="hover:text-brand-red" href="{{ route('terminos') }}">Términos</a></li>
    <li><a class="hover:text-brand-red" href="{{ route('privacidad') }}">Privacidad</a></li>
    <li><a class="hover:text-brand-red" href="{{ route('contacto') }}">Enviar Emisora</a></li>
  </ul>
</div>
