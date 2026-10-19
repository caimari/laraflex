# Changelog

Todos los cambios relevantes de este paquete. Formato basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/).

## [Sin publicar]

### Añadido
- README: tabla con todo lo que incluye el panel (`/panel`) y la parte pública.

## [1.0.3] - 2026-10-19

### Añadido
- Etiqueta de publicación `laraflex-assets` (`php artisan vendor:publish --tag=laraflex-assets`) que copia los assets del paquete y del tema Limpa a `public/vendor/caimari/...`. Necesaria cuando el docroot del servidor es `public/`.
- Este `CHANGELOG.md` y README actualizado con requisitos e instrucciones de instalación completas.

### Cambiado
- Compatible con PHP 8: `php` pasa de `^7.3` a `^7.3|^8.0` (probado en PHP 8.3 con Laravel 8.83).
- Eliminado el campo `version` de `composer.json`: la versión sale ahora de las etiquetas git.
- Eliminado el bloque `repositories` de `composer.json` (apuntaba a otro usuario y Composer lo ignora en una dependencia).

### Rendimiento (tema Limpa)
- Los *view composers* sobre `'*'` se ejecutaban en cada vista y repetían las mismas consultas: ahora se calculan una sola vez por petición.
- Las vistas totales y únicas de posts y páginas se calculan en la propia consulta (`withSum` / `withCount`), sin una consulta extra por registro (N+1).
- En una web real de prueba: de 752 a 34 consultas en la portada y de ~4 s a ~0,6 s de respuesta.

## [1.0.2] - 2026-03-28

### Añadido
- Módulo de socios (Members), consentimiento de cookies, fragmentos de código (CodeSnippets), gestión de paquetes y middleware.
- Mejoras en la interfaz del panel.

## [1.0.1] - 2023-07-05

### Añadido
- Primera versión publicada: panel de administración, CRUD y CMS (páginas, posts, categorías, tags, menús y temas).
