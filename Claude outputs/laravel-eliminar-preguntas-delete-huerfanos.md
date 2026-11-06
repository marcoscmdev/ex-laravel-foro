## Laravel: eliminar preguntas (método DELETE y datos huérfanos)

> [!abstract] Resumen
> Eliminar una pregunta requiere una ruta, un método en el controlador y entender cómo Laravel simula el verbo `DELETE` dentro de un formulario `POST`. También aparece un problema silencioso: al borrar el padre quedan registros huérfanos en las tablas relacionadas.

### Definir la ruta para eliminar

El botón de borrar ya existe en la vista `show`, junto al de editar, pero al pulsarlo da error porque la ruta aún no está registrada. Se declara en `routes/web.php`, en la sección de preguntas:

```php
Route::delete('questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');
```

El formulario envía con `method="POST"` y Laravel lee la directiva `@method('DELETE')` para enrutar la petición al método correcto:

```blade
<form action="{{ route('questions.destroy', $question) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Eliminar</button>
</form>
```

> [!info] Por qué POST si la ruta es DELETE
> HTML solo soporta `GET` y `POST` de forma nativa. Laravel añade un campo oculto `_method` para indicar al backend que la intención real es `DELETE`. Lo mismo vale para `PUT` y `PATCH`.

> [!tip] Tu formulario
> En tu `show.blade.php` el formulario ya tiene `@csrf` y `@method('DELETE')`, pero con `action="#"`. Ahí hay que poner `route('questions.destroy', $question)`.

### Método destroy en el controlador

Se añade en `App\Http\Controllers`. Al escribirlo, el editor autocompleta una comprobación de autorización: es una capa de seguridad que se desarrolla más adelante para que cada usuario solo borre sus propias preguntas.

Por ahora la lógica es simple:

1. Recibir la pregunta a eliminar.
2. Borrarla de la base de datos.
3. Redirigir al home con un mensaje de confirmación.

```php
public function destroy(Question $question)
{
    $question->delete();

    return redirect()->route('home')->with('success', 'Pregunta eliminada');
}
```

> [!tip] Ejemplo orientativo
> El texto no incluye el código. Adapta el mensaje y los nombres a los de tu proyecto.

Al probarlo: se elige una pregunta, se pulsa eliminar y el sistema vuelve al inicio. En la base de datos el conteo baja, de 28 a 27 registros en el ejemplo.

### Datos huérfanos

La tabla de preguntas se limpia, pero las relaciones asociadas no. En el ejemplo, la tabla de corazones tenía 13 registros. Se da corazón a una pregunta nueva (14) y se elimina esa pregunta: desaparece la pregunta, pero el corazón sigue ahí, apuntando a algo que ya no existe.

> [!info] Qué son los datos huérfanos
> Registros en tablas relacionadas que apuntan a un padre ya eliminado. Por ejemplo, un like de una pregunta borrada. Generan inconsistencia y ocupan espacio innecesario.

> [!warning] Por qué ocurre con corazones y comentarios
> Las relaciones polimórficas (`morphs`) no crean una clave foránea en la base de datos, así que no se borran en cascada como una relación normal. Hay que limpiarlas desde Eloquent.

### Comprobar que funciona

Se valida en dos niveles:

- **Visual:** se pulsa eliminar, se redirige al home y la pregunta ya no aparece en el listado.
- **Técnico:** se abre la base de datos y se confirma que el número de filas ha bajado.

Esto revela el siguiente reto: las tablas relacionadas necesitan limpieza automática al borrar un padre. Se resuelve en la siguiente clase con relaciones de Eloquent y eventos de modelo.

### Ejercicio

Implementar la eliminación en el foro: ruta nombrada, método en el controlador y redirección al home, usando `DELETE` simulado desde un formulario `POST`.

### Ideas clave

- [ ] `Route::delete(...)` con un nombre, por ejemplo `questions.destroy`
- [ ] Formulario `POST` con `@csrf` y `@method('DELETE')`
- [ ] `destroy(Question $question)` borra y redirige al home
- [ ] La autorización por usuario se añade más adelante
- [ ] Borrar el padre deja datos huérfanos en las relaciones polimórficas
- [ ] Comprobar en la vista y en la base de datos
- [ ] La limpieza automática se hace desde el modelo
