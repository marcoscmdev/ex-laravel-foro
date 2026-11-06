# Laravel: comentarios con relaciones polimorfas

> [!abstract] Resumen
> Implementar comentarios en sistemas dinámicos con estructuras complejas parece complicado, pero Laravel ofrece una solución práctica: las **relaciones polimorfas**. Permiten que entidades distintas, como preguntas y respuestas, compartan un mismo sistema de comentarios, lo que optimiza el diseño y evita repetir código.

## Qué son las relaciones polimorfas

Una relación polimorfa permite asociar comentarios a diferentes modelos sin crear varias tablas ni estructuras repetidas. Se definen con métodos específicos como `morphMany`, lo que da una estructura limpia y profesional.

## Configurar la relación

Hay que registrarla en cada modelo que pueda tener comentarios (`Question`, `Answer`):

```php
public function comments()
{
    return $this->morphMany(Comment::class, 'commentable');
}
```

> [!info] Qué significa `commentable`
> Es el nombre con el que se identifica la capacidad del modelo (preguntas o respuestas) de poseer comentarios. Debe coincidir con el que se usó en la migración con `$table->morphs('commentable')`, que crea las columnas `commentable_id` y `commentable_type`.

> [!tip] La otra cara de la relación
> En el modelo `Comment` se declara la inversa con `morphTo`:
> ```php
> public function commentable()
> {
>     return $this->morphTo();
> }
> ```

## Mostrar los comentarios en una vista

Con la relación configurada, se recorren los comentarios en la vista:

```blade
@foreach($question->comments as $comment)
    <div>
        <strong>{{ $comment->user->name }}</strong>
        <p>{{ $comment->created_at->format('d/m/Y') }}</p>
        <div>{{ $comment->content }}</div>
    </div>
@endforeach
```

Muestra el nombre del usuario, la fecha y el contenido del comentario.

> [!warning] Necesita relación con el usuario
> Para usar `$comment->user`, el modelo `Comment` debe tener declarado el método `user()` con su `belongsTo`.

## Error "debe ser un array o un objeto"

Durante el desarrollo puede aparecer un aviso diciendo que lo que se imprime debe ser un objeto o un array. Indica que la relación falta o está mal configurada. Para solucionarlo:

1. Verificar que el modelo incluye correctamente la relación polimorfa.
2. Asegurar que **cada** modelo involucrado tiene el método necesario (`morphMany`).

> [!info] Cómo trabajar
> Conviene ir registrando las relaciones a medida que el sistema las exige: así los errores se depuran rápido, porque cada aviso señala qué falta.

## Componentes para no copiar código

Copiar y pegar código no es buena práctica a largo plazo. La recomendación es crear **componentes** una vez que la estructura ya funciona. Así se consigue:

- Mejoras posteriores de forma ágil.
- Ahorro de tiempo y menos riesgo de errores.
- Mantenimiento más sencillo del código.

Es una evolución natural y eficiente del desarrollo: primero que funcione, después que no se repita.

## Ideas clave

- [ ] Una tabla de comentarios compartida por varios modelos
- [ ] `morphMany(Comment::class, 'commentable')` en cada modelo comentable
- [ ] `morphTo()` en `Comment` para la relación inversa
- [ ] `$table->morphs('commentable')` en la migración
- [ ] `@foreach($question->comments as $comment)` para mostrarlos
- [ ] Error de "objeto o array" = falta la relación en el modelo
- [ ] Extraer a un componente Blade cuando el código ya funciona
