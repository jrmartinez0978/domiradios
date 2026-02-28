# Verificador de Streams para Domiradios

Este sistema permite detectar emisoras que tienen su stream caído y mostrarlas en un widget en el panel de administración.

## Comando de verificación

Se ha creado un comando Artisan para verificar el estado de los streams:

```bash
php artisan radio:check-streams
```

Este comando hará lo siguiente:
- Verificará todas las emisoras activas
- Actualizará su estado en la base de datos
- Mostrará un informe detallado de los resultados

## Programación automática

Para que el verificador se ejecute automáticamente a diario, añade la siguiente línea a tu configuración de cron:

```
# Ejecutar verificador de streams diariamente a las 6:00 AM
0 6 * * * cd /ruta/a/tu/proyecto && php artisan radio:check-streams >> /dev/null 2>&1
```

### En sistemas Windows (con Task Scheduler)

1. Crea un archivo batch (.bat) con el siguiente contenido:
```
cd C:\laragon\www\domiradios
php artisan radio:check-streams
```

2. Programa este archivo para ejecutarse diariamente usando el Programador de tareas de Windows.

## Widget de Emisoras Caídas

Se ha creado un widget `OfflineRadiosWidget` que muestra las emisoras con streams caídos en el panel de administración. El widget se actualizará automáticamente cada vez que se acceda al panel y mostrará:

- Nombre de la emisora
- URL del stream
- Última vez que se detectó la caída
- Número de fallos consecutivos
- Acciones para verificar o editar la emisora
