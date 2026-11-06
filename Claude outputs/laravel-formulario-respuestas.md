## Laravel: formulario de respuestas (ruta, controlador y validación)

> [!abstract] Resumen
> Para responder preguntas en el foro hace falta un formulario, una ruta `post` y un controlador que valide, guarde y redirija. Este flujo es el tradicional de Laravel (sin Livewire) y convive con los comentarios, que sí son reactivos.

### Formulario en la vista

Va al final de cada pregunta: un título, un `textarea` y un botón de envío, más un bloque que muestra los errores de validación del servidor.

```blade
<form action="{{ route('answers.store', $question) }}" method="POST">
    @csrf
    <textarea name="content" required></textarea>

    @error('content')
        <p class="text-red-500">{{ $message }}</p>
    @enderror

    <button type="submit">Responder</button>
</form>
```

> [!tip] Ejemplo orientativo
> El texto no incluye el HTML. Adapta las clases al diseño que ya tienes.

### Ruta y controlador

La ruta es `post`, porque modifica la base de datos, y recibe la pregunta como parámetro:

```php
Route::post('questions/{question}/answers', [AnswerController::class, 'store'])->name('answers.store');
```

```bash
php artisan make:controller AnswerController
```

> [!info] Nombres del curso y de tu proyecto
> El curso usa `respuestas.store`, `RespuestaController` y `respuestas()`. En tu proyecto serán `answers.store`, `AnswerController` y `answers()`.

### Método store

```php
public function store(Question $question, Request $request)
{
    $request->validate([
        'content' => 'required|string|max:1900',
    ]);

    $question->answers()->create([
        'content' => $request->content,
        'user_id' => auth()->id(),
    ]);

    return back();
}
```

1. Recibe la pregunta ya resuelta (*route model binding*).
2. Valida `content`: obligatorio, `string`, máximo 1900 caracteres.
3. Crea la respuesta con la relación, que asigna sola la clave foránea de la pregunta.
4. Guarda el contenido y el usuario autenticado.
5. Redirige a la pregunta con `back()`.

### Mass assignment

Al guardar por primera vez sale el error de asignación masiva, igual que con los comentarios. Se arregla en el **modelo** `Answer`, no en el controlador:

```php
protected $fillable = ['content', 'user_id'];
```

> [!info] Por qué existe
> Es una capa de seguridad: sin `$fillable`, un atacante podría enviar campos extra en el formulario y modificar columnas sensibles.

### Validación en dos capas

- **HTML (`required`):** bloquea el envío con el `textarea` vacío. Es rápida y visual, pero fácil de saltar.
- **Servidor (`validate`):** si el navegador deja pasar el envío, Laravel devuelve el error y se muestra en el bloque de mensajes.

> [!warning] Las dos hacen falta
> La validación HTML mejora la experiencia, pero solo la del servidor garantiza que los datos cumplan las reglas.

### Mezcla con Livewire

La respuesta usa el flujo web clásico (`post`, controlador, redirección), mientras que los comentarios de cada respuesta funcionan con el componente Livewire, sin recargar. Mezclar ambos es habitual: no todo necesita ser reactivo. El mismo patrón se aplica al blog.

### Ideas clave

- [ ] Ruta `post` con la pregunta como parámetro
- [ ] `store(Question $question, Request $request)`: route model binding
- [ ] `validate` con `required|string|max:1900`
- [ ] `$question->answers()->create([...])` rellena la clave foránea sola
- [ ] `$fillable` en el modelo `Answer`
- [ ] Validar en HTML y en el servidor
- [ ] `back()` para volver a la pregunta
