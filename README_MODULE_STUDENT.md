# Módulo de Estudiantes

Este módulo implementa la gestión completa de estudiantes siguiendo la arquitectura de Clean Architecture y Domain-Driven Design (DDD).

## Estructura del Módulo

### Domain Layer
- **Entities**: `Student` - Entidad principal del dominio
- **Value Objects**: 
  - `StudentId` - Identificador único del estudiante
  - `FirstName` - Nombre del estudiante
  - `LastName` - Apellido del estudiante
  - `Email` - Email del estudiante (único)
- **Repositories**: `StudentRepository` - Interfaz para persistencia

### Application Layer
- **DTOs**: 
  - `CreateStudentDTO` - Para crear estudiantes
  - `UpdateStudentDTO` - Para actualizar estudiantes
  - `StudentResponseDTO` - Para respuestas
- **Use Cases**:
  - `CreateStudentUseCase` - Crear estudiante
  - `GetStudentUseCase` - Obtener estudiante por ID
  - `GetAllStudentsUseCase` - Obtener todos los estudiantes
  - `UpdateStudentUseCase` - Actualizar estudiante
  - `DeleteStudentUseCase` - Eliminar estudiante
- **Services**: `StudentService` - Orquestador de casos de uso

### Infrastructure Layer
- **Persistence**: `EloquentStudentRepository` - Implementación con Eloquent
- **Models**: `Student` - Modelo Eloquent

### Interfaces Layer
- **Controllers**: `StudentController` - Controlador HTTP
- **Requests**: 
  - `CreateStudentRequest` - Validación para crear
  - `UpdateStudentRequest` - Validación para actualizar
- **Resources**: `StudentResource` - Transformación de respuesta

## API Endpoints

### POST /api/v1/students
Crear un nuevo estudiante.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
    "first_name": "Juan",
    "last_name": "Pérez",
    "email": "juan.perez@example.com"
}
```

**Response (201):**
```json
{
    "message": "Estudiante creado exitosamente",
    "data": {
        "id": 1,
        "first_name": "Juan",
        "last_name": "Pérez",
        "email": "juan.perez@example.com",
        "created_at": "2025-01-21 10:00:00",
        "updated_at": "2025-01-21 10:00:00"
    }
}
```

### GET /api/v1/students
Obtener todos los estudiantes.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "data": [
        {
            "id": 1,
            "first_name": "Juan",
            "last_name": "Pérez",
            "email": "juan.perez@example.com",
            "created_at": "2025-01-21 10:00:00",
            "updated_at": "2025-01-21 10:00:00"
        }
    ]
}
```

### GET /api/v1/students/{id}
Obtener un estudiante específico.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "data": {
        "id": 1,
        "first_name": "Juan",
        "last_name": "Pérez",
        "email": "juan.perez@example.com",
        "created_at": "2025-01-21 10:00:00",
        "updated_at": "2025-01-21 10:00:00"
    }
}
```

### PUT /api/v1/students/{id}
Actualizar un estudiante.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Body:**
```json
{
    "first_name": "Juan Carlos",
    "last_name": "Pérez López",
    "email": "juan.carlos.perez@example.com"
}
```

**Response (200):**
```json
{
    "message": "Estudiante actualizado exitosamente",
    "data": {
        "id": 1,
        "first_name": "Juan Carlos",
        "last_name": "Pérez López",
        "email": "juan.carlos.perez@example.com",
        "created_at": "2025-01-21 10:00:00",
        "updated_at": "2025-01-21 10:30:00"
    }
}
```

### DELETE /api/v1/students/{id}
Eliminar un estudiante.

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
    "message": "Estudiante eliminado exitosamente"
}
```

## Validaciones

### Crear Estudiante
- `first_name`: Requerido, string, máximo 255 caracteres, solo letras y espacios
- `last_name`: Requerido, string, máximo 255 caracteres, solo letras y espacios
- `email`: Requerido, formato válido de email, único en la tabla, máximo 255 caracteres

### Actualizar Estudiante
- `first_name`: Requerido, string, máximo 255 caracteres, solo letras y espacios
- `last_name`: Requerido, string, máximo 255 caracteres, solo letras y espacios
- `email`: Requerido, formato válido de email, único en la tabla (excepto el propio), máximo 255 caracteres

## Base de Datos

### Tabla: students
```sql
CREATE TABLE students (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Testing

### Tests de Feature
Ejecutar tests de integración:
```bash
php artisan test tests/Feature/StudentTest.php
```

### Tests Unitarios
Ejecutar tests unitarios:
```bash
php artisan test tests/Unit/Domain/Student/
```

## Factory

El módulo incluye un factory para generar datos de prueba:

```php
use App\Models\Student;

// Crear un estudiante con datos aleatorios
$student = Student::factory()->create();

// Crear múltiples estudiantes
$students = Student::factory()->count(5)->create();
```

## Configuración

El módulo está configurado en `app/Providers/AppServiceProvider.php`:

```php
$this->app->bind(
    \App\Domain\Student\Repositories\StudentRepository::class, 
    \App\Infrastructure\Persistence\Eloquent\EloquentStudentRepository::class
);
```

## Migración

Para crear la tabla de estudiantes:

```bash
php artisan migrate
```

## Colección de Postman

Se incluye una colección de Postman (`postman_collection_students.json`) con todos los endpoints configurados para pruebas.

## Estructura de Archivos

```
app/
├── Application/Student/
│   ├── DTOs/
│   │   ├── CreateStudentDTO.php
│   │   ├── UpdateStudentDTO.php
│   │   └── StudentResponseDTO.php
│   ├── Services/
│   │   └── StudentService.php
│   └── UseCases/
│       ├── CreateStudentUseCase.php
│       ├── GetStudentUseCase.php
│       ├── GetAllStudentsUseCase.php
│       ├── UpdateStudentUseCase.php
│       └── DeleteStudentUseCase.php
├── Domain/Student/
│   ├── Entities/
│   │   └── Student.php
│   ├── Repositories/
│   │   └── StudentRepository.php
│   └── ValueObjects/
│       ├── StudentId.php
│       ├── FirstName.php
│       ├── LastName.php
│       └── Email.php
├── Infrastructure/Persistence/Eloquent/
│   └── EloquentStudentRepository.php
├── Interfaces/Http/
│   ├── Controllers/
│   │   └── StudentController.php
│   ├── Requests/
│   │   ├── CreateStudentRequest.php
│   │   └── UpdateStudentRequest.php
│   └── Resources/
│       └── StudentResource.php
└── Models/
    └── Student.php

tests/
├── Feature/
│   └── StudentTest.php
└── Unit/Domain/Student/ValueObjects/
    ├── FirstNameTest.php
    ├── LastNameTest.php
    └── EmailTest.php

database/
├── migrations/
│   └── 2025_01_21_000000_create_students_table.php
└── factories/
    └── StudentFactory.php
``` 