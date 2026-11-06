## Laravel: refactorización de componentes Livewire (wire:key y Traits)

> [!abstract] Resumen
> Escribir código limpio es necesario para tener aplicaciones profesionales, escalables y mantenibles. Aquí se resuelven dos problemas habituales con Livewire: los fallos al renderizar componentes dentro de un `foreach` (se arreglan con claves únicas) y el código duplicado en varios modelos (se aísla en un Trait).

### Por qué fallan los componentes Livewire dentro de un foreach

Un componente Livewire dentro de un `foreach` puede fallar si no tiene una **clave única**. Se nota cuando acciones simples, como añadir un comentario o dar un corazón, provocan errores en el sistema reactivo.

La solución es añadir una clave única, para que Livewire distinga los elementos repetidos y no entre en conflicto consigo mismo.

### Asignar una clave única

La práctica recomendada es usar una nomenclatura clara con la estructura `nombre-componente-ID`:

```blade
<div wire:key="respuesta-{{ $comment->id }}">
    ...
</div>
```

> [!tip] En la etiqueta `<livewire:...>` se usa `:key`
> `wire:key` va en elementos HTML normales. Cuando el elemento repetido es un componente Livewire, la clave se pasa como atributo de la etiqueta:
> ```blade
> <livewire:heart :heartable="$comment" :key="'comment-heart-'.$comment->id" />
> ```
> Es lo que ya tienes en tu `show.blade.php` y en `⚡comment.blade.php`.

> [!warning] Clave única entre tipos distintos
> Si repites el mismo componente para preguntas, respuestas y comentarios, incluye en la clave el tipo (`answer-heart-`, `comment-heart-`). Un comentario y una respuesta pueden tener el mismo `id`.

### Aislar la lógica repetida en Traits

Cuando la misma lógica se comparte en varios sitios, como dar y quitar corazones en `Question`, `Answer` y `Comment`, se aísla en un **Trait**. Los pasos:

1. Crear una carpeta para los Traits.
2. Crear el archivo con la estructura de un `trait`.
3. Mover a él la lógica repetida.

```php
namespace App\Traits;

trait HasCorazon
{
    // lógica para manejar corazones
}
```

Después se incluye en cada modelo con `use`:

```php
use HasCorazon;
```

Así la lógica queda centralizada, sin duplicar y fácil de mantener.

> [!tip] Cómo quedaría en tu proyecto
> Ejemplo orientativo con tus nombres, moviendo los cuatro métodos que hoy están copiados en los tres modelos:
> ```php
> namespace App\Traits;
>
> use App\Models\Heart;
>
> trait HasHearts
> {
>     public function hearts()
>     {
>         return $this->morphMany(Heart::class, 'heartable');
>     }
>
>     public function isHeartedBy()
>     {
>         return $this->hearts()->where('user_id', 20)->exists();
>     }
>
>     public function heart()
>     {
>         $this->hearts()->create(['user_id' => 20]);
>     }
>
>     public function unheart()
>     {
>         $this->hearts()->where('user_id', 20)->delete();
>     }
> }
> ```
> En cada modelo: `use App\Traits\HasHearts;` arriba y `use HasHearts;` dentro de la clase, y se borran los métodos copiados. Tu proyecto ya trae `app/Concerns`, que también sirve como carpeta.

### Ideas clave

- [ ] Los componentes Livewire dentro de un `foreach` necesitan una clave única
- [ ] `wire:key="nombre-{{ $x->id }}"` en elementos HTML
- [ ] `:key="..."` en la etiqueta `<livewire:componente>`
- [ ] Incluir el tipo en la clave para no repetir ids entre modelos
- [ ] Trait = lógica repetida en un solo sitio
- [ ] `namespace App\Traits;` y `use NombreDelTrait;` dentro del modelo
- [ ] Mismos métodos en varios modelos son candidatos a un Trait
