# Plan de Migración: Unificar appdomiradios en Laravel

## Objetivo
Eliminar el panel PHP legacy (appdomiradios) migrando su única funcionalidad necesaria (API REST para apps móviles) a Laravel. Todo lo demás (admin de radios, géneros, temas, settings, configs) ya existe en Filament.

## Análisis de la situación actual

### Lo que YA tiene Laravel (Filament):
- CRUD completo de Radios, Géneros, Temas, Settings, Configs
- Blog, Dashboard, Usuarios, Permisos
- SEO, Sitemap, Stream Monitoring

### Lo ÚNICO que falta en Laravel:
- **API REST para las apps móviles** (4 endpoints GET)

### Endpoints de la API legacy que consumen las apps:
```
GET /appdomiradios/api/api.php?method=getgenres&api_key=XXX
GET /appdomiradios/api/api.php?method=getradios&api_key=XXX&offset=0&limit=20
GET /appdomiradios/api/api.php?method=getthemes&api_key=XXX&offset=0&limit=20
GET /appdomiradios/api/api.php?method=getremoteconfigs&api_key=XXX
```

### Formato de respuesta que esperan las apps:
```json
{
  "status": 200,
  "msg": "success",
  "datas": [...]
}
```

### Imágenes:
- DB guarda: `radios/filename.webp` (con prefijo de carpeta)
- Legacy sirve desde: `/appdomiradios/uploads/{img_value}`
- Laravel sirve desde: `/storage/{img_value}`
- Ambos directorios tienen las mismas imágenes

---

## Plan de implementación (5 pasos)

### Paso 1: Crear ApiController en Laravel
Archivo: `app/Http/Controllers/Api/MobileApiController.php`

Replicar exactamente los 4 métodos con el mismo formato de respuesta JSON:
- `getGenres()` - SELECT id, name, img FROM genres WHERE isActive=1
- `getRadios()` - Soporta: radio_id, offset, limit, is_feature, genre_id, q
- `getThemes()` - Soporta: app_type, offset, limit
- `getRemoteConfigs()` - SELECT * FROM configs

Autenticación: Validar api_key contra variable de entorno (no hardcodeada).

### Paso 2: Crear rutas API en Laravel
Archivo: `routes/api.php`

```php
Route::get('/mobile', [MobileApiController::class, 'handle'])
    ->middleware('throttle:100,60');
```

El controlador leerá `?method=xxx` y despachará al método correcto (igual que el legacy).

### Paso 3: Redirect del path legacy al nuevo
Archivo: `/appdomiradios/api/.htaccess`

```apache
RewriteEngine On
RewriteRule ^api\.php$ /panel/api/mobile [QSA,R=301,L]
```

Esto garantiza que las apps ya publicadas (que apuntan al path viejo) lleguen al nuevo endpoint sin necesidad de actualizar las apps.

### Paso 4: Crear symlink para imágenes
Las apps construyen URLs de imágenes relativas. Crear symlink:
```bash
ln -s /panel/storage/app/public /appdomiradios/uploads
```
O alternativamente, en el nuevo API retornar URLs absolutas de las imágenes.

### Paso 5: Mover api_key al .env y desactivar appdomiradios
- Agregar `MOBILE_API_KEY=eHJhZGlvcGVyZmVjdGFwcA` al `.env`
- Renombrar la carpeta appdomiradios a `_appdomiradios_disabled`
- Mantener solo el redirect activo

---

## Archivos a crear/modificar:
1. **CREAR**: `app/Http/Controllers/Api/MobileApiController.php` (~150 líneas)
2. **MODIFICAR**: `routes/api.php` (agregar 1 ruta)
3. **MODIFICAR**: `.env` (agregar MOBILE_API_KEY)
4. **CREAR**: `config/mobile.php` (configuración de API key)
5. **CREAR**: `/appdomiradios/api/.htaccess` (redirect 301)

## Resultado final:
- Un solo panel (Laravel/Filament) para gestionar TODO
- API segura con rate limiting, validación, y Eloquent ORM
- Sin código PHP vanilla inseguro expuesto
- Apps móviles siguen funcionando sin cambios
