# Laravel: componente Livewire para comentarios (wire:click y toggle)

> [!abstract] Resumen
> Crear un componente Livewire permite construir interfaces interactivas sin escribir JavaScript ni salir del ecosistema PHP. Se ve cómo generar componentes reutilizables, pasarles datos desde una vista y ejecutar acciones dinámicas, como mostrar u ocultar un formulario con un solo clic.

## Generar un componente desde la terminal

El problema: la lógica de los comentarios estaba duplicada, tanto en la pregunta como en la respuesta. La solución es extraer ese bloque a un **componente reutilizable**:

```bash
php artisan make:livewire comentario
```

Al ejecutarlo, Livewire genera dos archivos: uno con la lógica en PHP, dentro de la carpeta de componentes, y otro con la vista Blade. Esa separación es clave para entender cómo funciona la herramienta.

> [!info] Livewire frente a Volt
> Livewire separa la lógica y la vista en dos archivos. Volt permite escribir ambas en un mismo archivo. Si se va a usar Volt, primero conviene entender bien Livewire.

> [!warning] Con Livewire 4 el resultado es distinto
> El curso usa la forma clásica de Livewire 3 (clase en `app/Livewire/` y vista en `resources/views/livewire/`). En Livewire 4, `make:livewire` crea por defecto **un único archivo**: `resources/views/components/⚡comentario.blade.php`. La clase PHP va arriba, dentro de `new class extends Component { ... }`, y el HTML debajo. Es lo mismo que Volt, pero ya integrado. En ese formato no hace falta `render()`: las propiedades públicas están disponibles en la vista directamente.

## Invocar un componente desde una vista Blade

La sintaxis se parece a una etiqueta HTML, con el prefijo `livewire`.

En la vista de las preguntas se identifica la sección con la lista de comentarios y el botón "agregar comentario", y se reemplaza por la llamada al componente. Para no acoplarlo a un modelo concreto se usa una propiedad llamada `commentable`, porque tanto las preguntas como las respuestas pueden recibir comentarios:

```blade
<livewire:comentario :commentable="$pregunta" />
```

El mismo componente se reutiliza debajo de cada respuesta, cambiando solo el valor que se pasa a `commentable`. Una sola pieza de código sirve en varios contextos.

> [!tip] Los dos puntos
> `:commentable="$pregunta"` (con los dos puntos) pasa una variable PHP. Sin ellos, Livewire entendería el texto literal `"$pregunta"`. El nombre tras los dos puntos debe coincidir con la propiedad pública del componente.

## Recibir los datos en la clase del componente

Dentro del archivo PHP se declara una propiedad pública de tipo `Model`, porque puede llegar tanto una pregunta como una respuesta. En el método `render` se pasan los comentarios a la vista consultándolos a través de la relación:

```php
public Model $commentable;

public function render()
{
    return view('livewire.comentario', [
        'comentarios' => $this->commentable->comentarios,
    ]);
}
```

Así la variable `$comentarios` queda disponible en la vista y se renderiza correctamente.

> [!tip] Importar Model
> `Model` necesita su `use` arriba del archivo: `use Illuminate\Database\Eloquent\Model;`. Sin él, PHP no encuentra la clase.

> [!info] Nombres del curso y de tu proyecto
> En el curso la relación se llama `comentarios` y los modelos están en español. En tu proyecto los nombres son `comments`, `Question` y `Answer`, así que tendrás `$commentable->comments`.

## Interactividad con wire:click y métodos públicos

Livewire conecta eventos del navegador con métodos PHP del componente.

Para mostrar u ocultar el formulario de comentarios sin recargar la página, se declara una propiedad booleana `showForm` y se usa en una condición dentro de la vista:

- Si es `false`, se muestra el botón "Agregar comentario".
- Si es `true`, aparece el formulario.

El paso clave es reemplazar el `href` del enlace por un evento Livewire:

```blade
<button wire:click="toggle">Agregar comentario</button>
```

Después se define el método y la propiedad en la clase:

```php
public bool $showForm = false;

public function toggle()
{
    $this->showForm = !$this->showForm;
}
```

Al hacer clic, la página no se recarga: solo cambia el estado del componente y se actualiza la vista.

La condición en la vista tendría esta forma (ejemplo orientativo):

```blade
@if($showForm)
    {{-- formulario de comentario --}}
@else
    <button wire:click="toggle">Agregar comentario</button>
@endif
```

> [!info] Qué es toggle
> Significa conmutar: si el valor está en `false` lo pasa a `true`, y viceversa.

> [!info] Qué hace wire:click
> Vincula un evento de clic del navegador con un método público del componente PHP. Permite ejecutar lógica del servidor sin recargar la página.

> [!info] Para qué sirve commentable
> Es una propiedad polimórfica que recibe cualquier modelo capaz de tener comentarios, como una pregunta o una respuesta, y permite reutilizar el mismo componente.

## Conceptos clave de la clase

- **Comando `make:livewire`:** genera la clase y la vista del componente en un solo paso.
- **Separación de lógica y vista:** Livewire entrega dos archivos, mientras que Volt los unifica.
- **Propiedades públicas tipadas:** usar `Model` como tipo permite recibir entidades distintas en el mismo componente.
- **Relación `commentable`:** patrón polimórfico para que varias entidades compartan comentarios.
- **`wire:click`:** directiva que conecta eventos del DOM con métodos PHP.
- **Método `toggle`:** patrón habitual para alternar estados booleanos, como mostrar u ocultar formularios.

## Ejercicio

Implementar este componente de comentarios y comprobar que también funciona dentro del módulo de blog.

## Ideas clave

- [ ] `php artisan make:livewire nombre` crea el componente
- [ ] `<livewire:nombre :commentable="$modelo" />` para usarlo
- [ ] `public Model $commentable;` para aceptar preguntas y respuestas
- [ ] El mismo componente se reutiliza en preguntas, respuestas y blog
- [ ] `wire:click="metodo"` ejecuta un método PHP sin recargar
- [ ] `public bool $showForm` + `toggle()` para mostrar u ocultar el formulario
- [ ] Livewire 4: archivo único `⚡nombre.blade.php`, sin `render()`
