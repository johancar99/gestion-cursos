# Documentación de API con Scramble

Este proyecto utiliza [Scramble](https://scramble.dedoc.co/) para generar automáticamente la documentación de la API REST.

## Características de la Documentación

- **Generación automática**: La documentación se genera automáticamente a partir de las anotaciones en los controladores
- **Interfaz interactiva**: Incluye una interfaz web para probar los endpoints
- **Esquemas OpenAPI**: Genera especificaciones OpenAPI 3.1.0 completas
- **Autenticación**: Documenta los endpoints que requieren autenticación
- **Ejemplos de respuesta**: Incluye ejemplos de respuestas para cada endpoint

## Acceso a la Documentación

### 1. Interfaz Web Interactiva

Para acceder a la documentación web interactiva:

```bash
# Iniciar el servidor de desarrollo
./vendor/bin/sail up -d

# Acceder a la documentación
http://localhost/docs/api
```

### 2. Especificación OpenAPI

La especificación OpenAPI se encuentra en:
- **Archivo JSON**: `api.json`
- **Regenerar documentación**: `./vendor/bin/sail artisan scramble:export`

## Estructura de la Documentación

### Grupos de Endpoints

1. **Authentication** (`/v1/auth`)
   - `POST /login` - Autenticación de usuarios
   - `POST /logout` - Cerrar sesión

2. **Users** (`/v1/users`)
   - `GET /` - Listar usuarios con filtros
   - `GET /all` - Listar todos los usuarios
   - `POST /` - Crear usuario
   - `GET /{id}` - Obtener usuario por ID
   - `PUT /{id}` - Actualizar usuario
   - `DELETE /{id}` - Eliminar usuario

3. **Courses** (`/v1/courses`)
   - `GET /` - Listar cursos con filtros
   - `GET /all` - Listar todos los cursos
   - `POST /` - Crear curso
   - `GET /{id}` - Obtener curso por ID
   - `PUT /{id}` - Actualizar curso
   - `DELETE /{id}` - Eliminar curso

4. **Students** (`/v1/students`)
   - `GET /` - Listar estudiantes con filtros
   - `GET /all` - Listar todos los estudiantes
   - `POST /` - Crear estudiante
   - `GET /{id}` - Obtener estudiante por ID
   - `PUT /{id}` - Actualizar estudiante
   - `DELETE /{id}` - Eliminar estudiante

5. **Enrollments** (`/v1/enrollments`)
   - `GET /` - Listar todas las matrículas
   - `POST /` - Crear matrícula
   - `GET /{id}` - Obtener matrícula por ID
   - `GET /course/{courseId}` - Matrículas por curso
   - `GET /student/{studentId}` - Matrículas por estudiante
   - `DELETE /{id}` - Eliminar matrícula

## Autenticación

La mayoría de endpoints requieren autenticación mediante token Bearer. Para obtener un token:

```bash
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'
```

## Permisos

Los endpoints están protegidos por permisos específicos:

- **Users**: `create users`, `view users`, `edit users`, `delete users`
- **Courses**: `create courses`, `view courses`, `edit courses`, `delete courses`
- **Students**: `create students`, `view students`, `edit students`, `delete students`
- **Enrollments**: `create enrollments`, `view enrollments`, `delete enrollments`

## Comandos Útiles

### Regenerar Documentación

```bash
# Regenerar la especificación OpenAPI
./vendor/bin/sail artisan scramble:export

# Analizar la API (para debugging)
./vendor/bin/sail artisan scramble:analyze
```

### Configuración

La configuración de Scramble se encuentra en `config/scramble.php`:

- **Título**: "API de Gestión de Cursos"
- **Versión**: "1.0.0"
- **Descripción**: Descripción completa del sistema
- **Tema**: Claro
- **Layout**: Responsive

## Características de la Documentación

### 1. Ejemplos de Respuesta

Cada endpoint incluye ejemplos de respuesta para diferentes escenarios:
- Respuestas exitosas (200, 201)
- Errores de validación (400, 422)
- Errores de autenticación (401)
- Errores de autorización (403)
- Recursos no encontrados (404)
- Errores internos (500)

### 2. Parámetros Documentados

- **Body Parameters**: Para endpoints POST/PUT
- **Query Parameters**: Para filtros en endpoints GET
- **Path Parameters**: Para IDs en URLs
- **Headers**: Para autenticación

### 3. Esquemas de Datos

La documentación incluye esquemas completos para:
- Request bodies
- Response bodies
- Validation rules
- Error responses

## Integración con Herramientas

### Postman

Puedes importar la especificación OpenAPI en Postman:
1. Abrir Postman
2. Import → Link
3. URL: `http://localhost/api.json`

### Swagger UI

Para usar Swagger UI localmente:
```bash
# Instalar Swagger UI
npm install swagger-ui-dist

# Servir la documentación
python -m http.server 8080
# Luego acceder a http://localhost:8080 con api.json
```

## Mantenimiento

### Agregar Nuevos Endpoints

1. Crear el controlador con anotaciones Scramble
2. Agregar las rutas en `routes/api.php`
3. Regenerar la documentación: `./vendor/bin/sail artisan scramble:export`

### Actualizar Documentación Existente

1. Modificar las anotaciones en los controladores
2. Regenerar la documentación: `./vendor/bin/sail artisan scramble:export`

### Ejemplo de Anotaciones

```php
/**
 * @group MiGrupo
 * 
 * Descripción del grupo de endpoints
 */
class MiController
{
    /**
     * Título del endpoint
     * 
     * Descripción detallada del endpoint
     * 
     * @authenticated
     * 
     * @bodyParam campo string required Descripción del campo. Example: valor
     * 
     * @response 200 scenario="Éxito" {
     *   "data": "respuesta"
     * }
     */
    public function miMetodo(Request $request): JsonResponse
    {
        // Implementación
    }
}
```

## Troubleshooting

### Problemas Comunes

1. **Documentación no se actualiza**
   - Ejecutar: `./vendor/bin/sail artisan scramble:export`
   - Limpiar caché: `./vendor/bin/sail artisan cache:clear`

2. **Errores en la generación**
   - Verificar sintaxis de anotaciones
   - Revisar logs: `./vendor/bin/sail logs`

3. **Interfaz web no carga**
   - Verificar que el servidor esté corriendo
   - Comprobar configuración en `config/scramble.php`

## Recursos Adicionales

- [Documentación oficial de Scramble](https://scramble.dedoc.co/)
- [Especificación OpenAPI](https://swagger.io/specification/)
- [Laravel Sanctum](https://laravel.com/docs/sanctum) (Autenticación)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission) (Autorización) 