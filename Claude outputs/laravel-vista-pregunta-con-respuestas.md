# Laravel: vista de una pregunta con su listado de respuestas

> [!abstract] Resumen
> Mostrar una pregunta con sus respuestas implica conectar la vista con la base de datos y aprovechar las relaciones entre modelos. Se pasa de contenido estático a información dinámica usando **Blade**, **relaciones Eloquent** y el sistema de plantillas, todo dentro de un proyecto tipo foro.

## Estructura de la vista de una pregunta

Al hacer clic en una pregunta se debe ver toda su información: título, autor, categoría, fecha y la lista de respuestas. Se parte de una plantilla ya documentada y se pega dentro del archivo de la vista de la pregunta.

Una vez pegada:

1. Se ajusta el diseño y se añaden detalles visuales, como un corazón mediante una entidad HTML.
2. Se reemplazan los textos estáticos por datos reales que llegan desde el controlador.

Campos conectados directamente con la base de datos:

- **Título** de la pregunta.
- **Usuario** que la creó, accediendo a la relación con el modelo `User`.
- **Categoría**, obtenida desde la relación con `Category`, mostrando su nombre.
- **Fecha de creación**, formateada con un método de Carbon para mostrar cuánto tiempo ha pasado.

```blade
<h1>{{ $question->title }}</h1>
<span>{{ $question->user->username }}</span>
<span>{{ $question->category->name }}</span>
<span>{{ $question->created_at->diffForHumans() }}</span>
<span>&hearts;</span>
```

> [!info] Qué es Blade
> Es el motor de plantillas que permite mezclar HTML con una sintaxis especial para mostrar datos dinámicos, ejecutar bucles y condicionales sin escribir PHP plano.

## Recorrer las respuestas con foreach

Después del bloque de la pregunta viene el listado de respuestas. Un `foreach` de Blade recorre la colección de respuestas asociadas a esa pregunta.

Dentro del bucle se reemplaza el contenido estático por las propiedades reales de cada respuesta. Lo importante es cerrar bien el `foreach` y mantener el **plural** en la variable que se itera, porque representa varios elementos.

Datos que se muestran por cada respuesta:

- El **contenido** de la respuesta.
- El **nombre del usuario** que la escribió.
- La **fecha** de creación, también con formato relativo.

```blade
@foreach($question->answers as $answer)
    <p>{{ $answer->content }}</p>
    <span>{{ $answer->user->username }}</span>
    <span>{{ $answer->created_at->diffForHumans() }}</span>
@endforeach
```

> [!tip] Ejemplo orientativo
> Los nombres `answers` y `content` son de ejemplo. Usa los de tu modelo y tu migración.

## Por qué falla al mostrar el usuario de la respuesta

Al acceder al usuario desde la respuesta, el sistema falla porque la relación aún no está declarada en el modelo. Una respuesta pertenece a un usuario, y esa relación se define en el modelo `Answer` con un método que devuelve un `belongsTo` hacia `User`:

```php
// app/Models/Answer.php
public function user()
{
    return $this->belongsTo(User::class);
}
```

Una vez registrada la relación, cada respuesta muestra el nombre de quien la creó. Este flujo se repite a propósito para interiorizar cómo funcionan las relaciones en Eloquent a medida que avanza el proyecto.

> [!info] Qué hace belongsTo
> Define una relación inversa: indica que un modelo pertenece a otro. Aquí, una respuesta pertenece a un usuario, y así se accede a sus datos desde la respuesta.

> [!warning] Si falla
> Un error del tipo "Call to undefined relationship" significa que falta declarar el método de la relación en el modelo.

## Comportamiento con contenido realmente dinámico

Con todo conectado, se puede navegar desde la home hacia distintas preguntas y ver cómo cambia cada bloque sin tocar el HTML. Una pregunta puede tener tres respuestas, otra una sola y otra ninguna: la vista responde a lo que existe en la base de datos.

Esto demuestra el valor de las relaciones bien definidas y de un motor de plantillas como Blade: la interfaz refleja fielmente los datos, sin duplicar código ni escribir condicionales innecesarios.

## Siguiente paso

Replicar estos mismos cambios en la sección de preguntas y en la sección del blog para mantener la coherencia, e identificar qué relaciones hay que declarar en los modelos.

## Ideas clave

- [ ] Plantilla pegada en la vista y textos estáticos sustituidos por datos reales
- [ ] `$question->user`, `$question->category` y `created_at->diffForHumans()`
- [ ] `@foreach` sobre la colección en plural de respuestas
- [ ] `belongsTo` en `Answer` para acceder a su usuario
- [ ] La vista se adapta a cuántas respuestas existan, incluso ninguna
- [ ] Repetir el patrón en preguntas y blog
