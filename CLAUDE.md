# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Comandos de Desarrollo

### Entorno de Desarrollo
- `npm run dev` - Inicia el servidor de desarrollo con Vite (HMR para CSS/JS)
- `npm run build` - Construye los assets para producción
- `php artisan serve` - Inicia servidor de desarrollo Laravel

### Base de Datos
- `php artisan migrate` - Ejecuta migraciones de base de datos
- `php artisan migrate:fresh --seed` - Refresca base de datos con datos semilla
- `php artisan db:seed` - Ejecuta seeders

### Testing
- `vendor/bin/phpunit` - Ejecuta test suite completo
- `vendor/bin/phpunit tests/Unit` - Ejecuta solo tests unitarios
- `vendor/bin/phpunit tests/Feature` - Ejecuta solo tests de funcionalidad

### Limpieza de Código
- `vendor/bin/pint` - Formatea código PHP siguiendo estándares Laravel

### Cache y Optimización
- `php artisan config:cache` - Cachea configuración para producción
- `php artisan route:cache` - Cachea rutas para producción
- `php artisan view:cache` - Cachea vistas para producción
- `php artisan optimize:clear` - Limpia todos los caches

## Arquitectura del Sistema

### Tecnologías Principales
- **Laravel 12** - Framework PHP principal
- **Filament 3** - Panel de administración principal
- **Livewire 3** - Componentes reactivos
- **Vite + TailwindCSS** - Build system y styling
- **Laravel Pulse** - Monitoreo de aplicación
- **MariaDB** - Base de datos principal

### Estructura del Dominio
Este es un panel de administración para un directorio de radios dominicanas con las siguientes entidades principales:

- **Radio** - Estaciones de radio con metadatos, streams y redes sociales
- **Genre** - Géneros musicales para categorización
- **RadioCat** - Categorías de radios
- **Setting** - Configuración del sistema
- **User** - Usuarios del panel administrativo
- **Theme** - Temas visuales del frontend

### Arquitectura de Filament
- Recursos ubicados en `app/Filament/Resources/`
- Cada recurso incluye páginas de List, Create, Edit y View
- Widgets personalizados en `app/Filament/Widgets/`
- Configuración principal en `config/filament.php`

### Gestión de Imágenes
- **Intervention Image v3** para procesamiento
- Conversión automática a WebP para optimización
- Storage local configurado por defecto

### Frontend Assets
- **TailwindCSS** con colores de marca personalizados:
  - `brand-red`: #E21C25
  - `brand-blue`: #003A70
  - `brand-gray`: #F5F7FA
- Assets compilados con Vite desde `resources/css/app.css` y `resources/js/app.js`

### Base de Datos
- **MariaDB** como motor principal
- Migraciones en `database/migrations/`
- Seeders para datos iniciales en `database/seeders/`
- Factories para testing en `database/factories/`

### Configuración de Entorno
- Variables de entorno en `.env` (usar `.env.example` como referencia)
- Base de datos: `domiradios` por defecto
- Queue driver configurado como `database`
- Session driver configurado como `database`

### Servicios Integrados
- **Google API Client** para servicios de Google
- **Spatie Server Monitor** para monitoreo del servidor
- **Spatie Sitemap** para generación de sitemaps
- **SEO Tools** para optimización SEO

### Patrones de Desarrollo
- Modelos Eloquent en `app/Models/`
- Providers personalizados en `app/Providers/`
- Traits reutilizables en `app/Traits/`
- Comandos de consola en `app/Console/`
- Livewire components en `app/Livewire/`