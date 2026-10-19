# LaraFlex CMS & CRUD Toolkit (Admin Panel)

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

El paquete "LaraFlex CMS & CRUD Toolkit" es una solución, en fase temprana de desarrollo, para añadir a cualquier aplicación Laravel un panel de administración, generación de CRUD (Create, Read, Update, Delete) y un sistema de gestión de contenido: páginas, posts, categorías, tags, menús, importación de temas, etc. Está pensado para ahorrar trabajo repetitivo y ofrecer una interfaz intuitiva.

Se instala como un paquete más de Composer en un Laravel limpio y crea el panel de administración y la parte pública (con un tema) sin tocar el resto de la aplicación.

## Requisitos

| | Versión |
|---|---|
| PHP | 7.3 o superior, incluido PHP 8.x (probado en PHP 8.3) |
| Laravel | **8.x** (`laravel/framework ^8.0`). No es compatible todavía con Laravel 9 o superior |
| Base de datos | MySQL / MariaDB |
| Otros | `doctrine/dbal`, `realrashid/sweet-alert` (se instalan solos con Composer) |

## Características destacadas

- **Panel de administración listo para usar:** interfaz profesional y amigable para administrar la aplicación.
- **CRUD automático:** genera las operaciones CRUD de tus modelos.
- **Sistema de gestión de contenido (CMS):** páginas, posts, categorías, tags y más.
- **Integración con Laravel:** se registra solo mediante el descubrimiento de paquetes de Laravel.
- **Cookies, fragmentos de código (snippets) y menús** gestionables desde el panel.

### En desarrollo

- Personalización flexible mediante opciones de configuración y ajustes visuales.
- Autenticación y autorización con roles y permisos.
- Soporte para múltiples idiomas.

## Instalación

Ejecuta estos comandos en la raíz de un proyecto Laravel 8 (con la base de datos ya configurada en `.env`):

```bash
composer require caimari/laraflex
composer require caimari/fmanager
composer require caimari/laraflex-theme-plexus

php artisan migrate
php artisan storage:link
php artisan vendor:publish --tag=laraflex-images
php artisan vendor:publish --tag=laraflex-assets
```

### Assets (CSS/JS/imágenes) y carpeta pública

Los temas cargan sus estilos y scripts desde URLs `/vendor/caimari/...`. En un Laravel normal la carpeta pública del servidor web es `public/`, así que esos ficheros hay que publicarlos allí con:

```bash
php artisan vendor:publish --tag=laraflex-assets
```

Tras actualizar los paquetes con `composer update`, vuelve a publicarlos para que los assets se actualicen:

```bash
php artisan vendor:publish --tag=laraflex-assets --force
```

### Ruta de bienvenida

En `routes/web.php`, comenta o elimina la ruta de bienvenida que trae Laravel por defecto, porque usa la misma URL raíz (`/`) que el tema y entraría en conflicto:

```php
/*
Route::get('/', function () {
    return view('welcome');
});
*/
```

Comprueba también que ninguna otra ruta tuya use `/`.

## Desinstalación

```bash
composer remove caimari/laraflex caimari/fmanager caimari/laraflex-theme-plexus
```

## Acceso

La ruta predeterminada del panel es `/panel`. Tras migrar las tablas se crea un usuario administrador con estas credenciales:

- Login: `admin@admin.com`
- Contraseña: `adminadmin`

**Cambia estas credenciales inmediatamente** después del primer acceso: son públicas.

## Historial de cambios

Consulta [CHANGELOG.md](CHANGELOG.md).
