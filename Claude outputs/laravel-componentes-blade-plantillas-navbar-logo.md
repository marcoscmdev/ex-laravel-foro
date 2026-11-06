# Laravel: componentes Blade, plantillas, navbar y logo

> [!abstract] Resumen
> Una estructura visual organizada facilita mucho el desarrollo de aplicaciones web. En Laravel, crear y reutilizar plantillas con **componentes Blade** mejora la organización del código y mantiene las vistas claras y ordenadas.

## Crear un componente Blade

Con Artisan:

```bash
php artisan make:component
```

También se puede hacer a mano, creando directamente el archivo Blade.

Pasos manuales:

1. Ir a la carpeta `resources/views/components/forum`.
2. Crear un archivo nuevo llamado `app.blade.php`.
3. Pegar la estructura básica deseada desde el diseño ya preparado.

Ambos métodos llevan al mismo resultado.

> [!tip] Nombre del componente
> `make:component` necesita un nombre. Por ejemplo, `php artisan make:component Forum/App --view` crea `resources/views/components/forum/app.blade.php`, y se usa como `<x-forum.app>`.

## Para qué sirven varias plantillas

Usar varias plantillas permite diferenciar las vistas y secciones de la aplicación:

- Plantilla `home` para la página inicial.
- Plantilla `app` para el resto de páginas internas, como las secciones de preguntas o consultas concretas.

Así la administración es más sencilla y cada sección tiene un diseño adecuado.

## Reutilizar el menú principal (navbar)

Para evitar código duplicado y simplificar las actualizaciones:

- Generar un componente específico para el menú.
- Reutilizarlo en las distintas plantillas, lo que asegura consistencia y facilita el mantenimiento.

```blade
<x-navbar />
```

> [!info] Un solo cambio
> Al actualizar el menú una vez, el cambio se replica automáticamente en todas las páginas que usan ese componente.

## Logo dinámico con componentes

Los componentes Blade también sirven para elementos visuales repetitivos, como un logo:

1. Crear un componente `logo.blade.php`.
2. Usar propiedades dinámicas para los estilos CSS, por ejemplo `class`.

```blade
<x-logo class="h-8" />
```

Desde cualquier vista se pueden asignar estilos distintos:

```blade
<x-logo class="h-6 text-white" />
```

La ventaja es mantener un control visual eficiente y organizado en todo el proyecto.

> [!tip] Cómo recibe el `class` el componente
> Dentro de `logo.blade.php`, el atributo `class` llega a través de `$attributes`. Por ejemplo, en la etiqueta del logo: `<svg {{ $attributes->merge(['class' => 'h-8']) }}>`. Así puede tener una clase por defecto y aceptar más desde fuera.

## Por qué importan los componentes Blade

- Mantienen el código organizado y fácil de administrar.
- Reducen la redundancia al evitar copiar y pegar.
- Permiten modificar el aspecto y la estructura desde un único lugar.

El resultado es una aplicación visualmente agradable, dinámica y con un mantenimiento eficiente a largo plazo.

## Ideas clave

- [ ] `php artisan make:component` o crear el archivo `.blade.php` a mano
- [ ] Componentes en `resources/views/components/`
- [ ] Una plantilla `home` y otra `app` para distinguir secciones
- [ ] `<x-navbar />` reutilizado en todas las plantillas
- [ ] `<x-logo class="..." />` con estilos distintos según la vista
- [ ] `$attributes` para que el componente reciba `class` desde fuera
