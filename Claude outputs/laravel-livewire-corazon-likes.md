## Laravel: botón de corazón (like) con Livewire y morphMany

> [!abstract] Resumen
> Un botón de corazón transforma la experiencia en una plataforma de preguntas y respuestas. Se construye con un único componente Livewire reutilizable que sirve para preguntas, respuestas y comentarios, apoyado en una relación polimórfica `morphMany`.

### Crear el componente

La reactividad es la clave: en vez de recargar la página, la acción se actualiza al instante.

```bash
php artisan make:livewire corazon
```

Genera la clase PHP con la lógica y la vista Blade con el diseño.

> [!info] Qué es un componente Livewire
> Una pieza reutilizable que combina lógica PHP y vista Blade, y que actualiza el DOM sin recargar la página ni escribir JavaScript.

> [!warning] Con Livewire 4
> En tu versión, `make:livewire` crea un único archivo (`resources/views/components/⚡corazon.blade.php`) con la clase PHP arriba y el HTML debajo, sin `render()`.

### Usarlo en preguntas, respuestas y comentarios

La sintaxis es la misma en los tres sitios; solo cambia el modelo que se pasa:

```blade
<livewire:corazon :model="$pregunta" />
<livewire:corazon :model="$respuesta" />
<livewire:corazon :model="$comentario" />
```

Funciona porque la propiedad `$model` acepta cualquier modelo de Eloquent.

> [!info] Nombres del curso y de tu proyecto
> En el curso: `corazon`, `$pregunta`, `$respuesta`, `$comentario`. En tu proyecto: `heart`, `$question`, `$answer`, `$comment`.

### Método toggle

Si la entidad ya tiene corazón se pinta de rojo, y si no, de gris. Al hacer clic se ejecuta `toggle()`, que decide si crear o eliminar el registro:

```php
public Model $model;

public function toggle()
{
    if ($this->model->hasHearted()) {
        $this->model->unheart();
    } else {
        $this->model->heart();
    }
}
```

`$model` está tipada con `Model`, así que siempre recibe una instancia válida, sea pregunta, respuesta o comentario.

### Relación morphMany

Un corazón puede pertenecer a tres entidades distintas, así que se usa una relación polimórfica. En cada modelo:

```php
public function hearts()
{
    return $this->morphMany(Heart::class, 'heartable');
}
```

Con `morphMany` no hace falta una tabla de likes por cada tipo de contenido: una sola tabla guarda todos los corazones y la relación indica a qué entidad pertenece cada uno.

> [!info] Qué es morphMany
> Una relación polimórfica que permite a un modelo tener muchos registros relacionados, vinculados a varios modelos distintos mediante un campo `*_type` y un `*_id`.

> [!tip] Migración del modelo Heart
> La tabla necesita `$table->morphs('heartable')` y `user_id`, como se hizo con `commentable` en los comentarios.

### Dar y quitar el corazón

Para registrar el corazón se crea un registro nuevo. El usuario está fijo en 20 hasta que haya login:

```php
public function heart()
{
    $this->hearts()->create(['user_id' => 20]);
}
```

El método inverso busca el registro existente y lo elimina:

```php
public function unheart()
{
    $this->hearts()->where('user_id', 20)->delete();
}
```

Para saber si ya hay corazón se consulta la relación con un `where` (ejemplo orientativo):

```php
public function hasHearted()
{
    return $this->hearts()->where('user_id', 20)->exists();
}
```

> [!warning] Mass assignment
> La primera vez que se ejecuta `heart()` aparece el error de asignación masiva. Se arregla en el **modelo `Heart`**, añadiendo `protected $fillable = ['user_id'];`.

### Archivos que cambian

Al ejecutar `git status` aparecen:

- Los modelos `Question`, `Answer` y `Comment`, con los métodos `hearts()`, `heart()`, `unheart()` y `hasHearted()`.
- `show.blade.php`, donde se inserta el componente.
- El nuevo componente del corazón, clase y vista.

Tras recargar la página el corazón se mantiene, porque la consulta va contra la base de datos en cada renderizado.

### Mejora pendiente

Los mismos métodos están copiados en los tres modelos, y eso huele a código repetido. La siguiente iteración será refactorizarlo con un **trait**.

### Ideas clave

- [ ] Un solo componente de corazón para preguntas, respuestas y comentarios
- [ ] `<livewire:corazon :model="$entidad" />` y `public Model $model;`
- [ ] `toggle()` crea o elimina el registro según `hasHearted()`
- [ ] `morphMany(Heart::class, 'heartable')` en cada modelo
- [ ] `$fillable` con `user_id` en el modelo `Heart`
- [ ] `user_id` fijo en 20 hasta tener login
- [ ] Código duplicado en tres modelos, candidato a un trait
