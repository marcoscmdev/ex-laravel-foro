# Laravel: formulario de comentarios con Livewire (wire:model, wire:submit y fillable)

> [!abstract] Resumen
> Un formulario reactivo en Livewire permite registrar datos en tiempo real sin recargar la página. Se conectan inputs con propiedades usando `wire:model`, se envía el formulario con `wire:submit`, se valida el contenido y se guardan comentarios polimórficos usando asignación masiva (*mass assignment*).

## Conectar un input al backend con wire:model

El punto de partida es reutilizar el formulario que ya estaba diseñado y enlazarlo con la clase Livewire. La caja de texto necesita comunicarse con una propiedad pública del componente, para que cualquier cambio en el navegador se refleje en el servidor.

1. Declarar en la clase una propiedad de tipo `string`.
2. En la vista Blade, añadir el atributo `wire:model` al input.

```php
public string $contenido = '';
```

```blade
<input type="text" wire:model="contenido">
```

Con eso el dato viaja automáticamente entre el frontend y el backend.

> [!info] Qué hace wire:model
> Vincula un input HTML con una propiedad pública del componente PHP y sincroniza el valor en ambos lados, sin escribir JavaScript.

> [!info] Nombres del curso y de tu proyecto
> El curso usa `contenido`, `comentarios` y `Comentario`. En tu proyecto son `content`, `comments` y `Comment`, así que el input lleva `wire:model="content"`.

## Cancelar el formulario sin recargar

El botón de cancelar reutiliza el método `toggle`, que funciona como una palanca: cambia `showForm` de `false` a `true` y al revés, mostrando u ocultando el formulario.

- Clic en "Agregar comentario": la variable pasa a `true` y aparece el formulario.
- Clic en "Cancelar": la variable vuelve a `false` y el formulario se oculta.
- Todo ocurre sin recargar la página, gracias a la reactividad de Livewire.

## Enviar el formulario y disparar el método add

Cuando el usuario pulsa "Comentar" tiene que ejecutarse un método del componente. Para eso se usa `wire:submit` en la etiqueta `<form>`:

```blade
<form wire:submit="add">
    <input type="text" wire:model="content">

    <button type="submit">Comentar</button>
    <button type="button" wire:click="toggle">Cancelar</button>
</form>
```

> [!tip] Ejemplo orientativo
> El texto no incluye el HTML del formulario. Este es un ejemplo mínimo: adapta las clases y el diseño al que ya tienes.

> [!info] Si el método no existe
> Livewire lanza un error claro indicando que falta crear el método `add`.

## El método add: validar y guardar

Lo primero dentro de `add` es **validar**. El contenido debe ser requerido, de tipo `string` y con un máximo de 255 caracteres. Así se protege la base de datos de entradas vacías o demasiado largas.

```php
public function add()
{
    $this->validate([
        'contenido' => 'required|string|max:255',
    ]);

    $this->commentable->comentarios()->create([
        'contenido' => $this->contenido,
        'user_id' => 20,
    ]);

    $this->reset(['contenido', 'showForm']);
}
```

> [!warning] user_id fijo
> `user_id` está fijo en 20 mientras no exista el sistema de inicio de sesión. Más adelante se obtendrá del usuario autenticado. Hasta entonces, tiene que existir un usuario con ese id en la base de datos.

## El error de mass assignment

Al guardar el comentario por primera vez, Laravel lanza un error (`MassAssignmentException`). Se debe a que se envía una colección de datos al modelo sin haberle dicho qué campos puede aceptar. Es una capa de seguridad.

> [!info] Qué es fillable
> Es una propiedad del modelo Eloquent que lista los campos permitidos para asignación masiva. Sin ella, Laravel bloquea la inserción para evitar que campos sensibles se modifiquen sin querer.

La solución es abrir el modelo del comentario y añadir los campos permitidos:

```php
protected $fillable = ['contenido', 'user_id'];
```

Con eso el comentario se guarda y la relación polimórfica funciona: un comentario puede pertenecer a una pregunta o a una respuesta, sin importar el tipo del modelo padre.

> [!warning] Va en el modelo, no en el componente
> El `$fillable` se escribe en `app/Models/Comment.php`, dentro de la clase del modelo. Si se pone en la clase del componente Livewire no hace nada, porque no es un modelo Eloquent, y el error sigue apareciendo.

> [!tip] Alternativa en Laravel 13
> Los proyectos con Laravel 13 también pueden declararlo con el atributo `#[Fillable(['content', 'user_id'])]` sobre la clase, como hace `User.php` (con `use Illuminate\Database\Eloquent\Attributes\Fillable;`).

## Limpiar el formulario después de guardar

Una vez guardado, el input debe quedar vacío y el formulario ocultarse. Se puede hacer reasignando valores a mano, pero Livewire ofrece `reset`, que es más limpio:

```php
$this->reset('contenido');                 // deja una propiedad en su valor inicial
$this->reset(['contenido', 'showForm']);   // resetea las dos a la vez
```

El input se vacía y el formulario se cierra en el mismo paso.

## Comprobar que funciona

Al probarlo en el navegador, se escribe "ABC", se pulsa "Comentar" y el registro aparece en la tabla de comentarios sin recargar la página. Si se revisa la base de datos ordenando de forma descendente, se ve que un comentario pertenece a la pregunta con id 2 y otro a la respuesta con id 38, lo que confirma que la relación polimórfica funciona.

## Livewire frente a JavaScript puro

Livewire da el poder de JavaScript escribiendo solo PHP. No hace falta configurar `fetch` ni `axios`, ni manejar estados a mano. Las propiedades públicas del componente actúan como el estado, y los métodos como los manejadores de eventos.

- Reactividad sin escribir una línea de JS.
- Validaciones nativas de Laravel reutilizadas en el frontend.
- Reseteo de variables con un solo método.

## Ejercicio

Hacer que el formulario funcione y probar el componente dentro del foro y del blog. Pensar qué validaciones extra añadir.

## Ideas clave

- [ ] `wire:model="propiedad"` sincroniza un input con una propiedad pública
- [ ] `wire:submit="add"` en el `<form>` ejecuta el método `add`
- [ ] `wire:click="toggle"` para mostrar y ocultar el formulario
- [ ] Validar siempre antes de guardar: `required|string|max:255`
- [ ] `$fillable` en el **modelo** para permitir asignación masiva
- [ ] `$this->reset(['content', 'showForm'])` limpia el formulario
- [ ] `user_id` fijo (20) hasta tener login
- [ ] El mismo componente sirve para preguntas, respuestas y blog (polimórfico)
