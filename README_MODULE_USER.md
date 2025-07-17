# Módulo User y Authentication - DDD Implementation

Este documento describe la implementación del módulo User y Authentication siguiendo los principios de Domain-Driven Design (DDD) y Clean Architecture.

## Estructura del Proyecto

### Domain Layer (Capa de Dominio)

#### User Module
```
app/Domain/User/
├── Entities/
│   └── User.php                    # Entidad principal del usuario
├── ValueObjects/
│   ├── UserId.php                  # Identificador único del usuario
│   ├── Name.php                    # Nombre del usuario
│   ├── Email.php                   # Email del usuario
│   └── Password.php                # Contraseña del usuario
├── Repositories/
│   └── UserRepository.php          # Interfaz del repositorio
└── Services/
    └── PasswordHasher.php          # Interfaz para hashear contraseñas
```

#### Authentication Module
```
app/Domain/Authentication/
├── Entities/
│   └── AuthenticationToken.php     # Entidad del token de autenticación
├── ValueObjects/
│   ├── TokenId.php                 # Identificador del token
│   └── TokenValue.php              # Valor del token
└── Repositories/
    └── AuthenticationTokenRepository.php  # Interfaz del repositorio de tokens
```

### Application Layer (Capa de Aplicación)

#### User Module
```
app/Application/User/
├── DTOs/
│   ├── CreateUserDTO.php           # DTO para crear usuarios
│   ├── UpdateUserDTO.php           # DTO para actualizar usuarios
│   └── UserResponseDTO.php         # DTO de respuesta
├── UseCases/
│   ├── CreateUserUseCase.php       # Caso de uso para crear usuarios
│   ├── GetUserUseCase.php          # Caso de uso para obtener usuarios
│   ├── UpdateUserUseCase.php       # Caso de uso para actualizar usuarios
│   └── DeleteUserUseCase.php       # Caso de uso para eliminar usuarios
└── Services/
    └── UserService.php             # Servicio de aplicación
```

#### Authentication Module
```
app/Application/Authentication/
├── DTOs/
│   ├── LoginDTO.php                # DTO para login
│   └── LoginResponseDTO.php        # DTO de respuesta de login
└── UseCases/
    ├── LoginUseCase.php            # Caso de uso para login
    └── LogoutUseCase.php           # Caso de uso para logout
```

### Infrastructure Layer (Capa de Infraestructura)

```
app/Infrastructure/
├── Persistence/
│   └── Eloquent/
│       ├── UserRepository.php      # Implementación del repositorio User
│       └── AuthenticationTokenRepository.php  # Implementación del repositorio de tokens
└── Services/
    └── PasswordHasher.php          # Implementación del hasher de contraseñas
```

### Interface Layer (Capa de Interfaz)

```
app/Interfaces/Http/
├── Controllers/
│   ├── UserController.php          # Controlador para usuarios
│   └── AuthenticationController.php # Controlador para autenticación
├── Requests/
│   ├── CreateUserRequest.php       # Request para crear usuarios
│   ├── UpdateUserRequest.php       # Request para actualizar usuarios
│   └── LoginRequest.php            # Request para login
└── Resources/
    └── UserResource.php            # API Resource para usuarios
```

## Características Principales

### 1. Clean Architecture
- **Separación de responsabilidades**: Cada capa tiene una responsabilidad específica
- **Independencia de frameworks**: El dominio no depende de Laravel/Eloquent
- **Testabilidad**: Fácil de testear cada capa independientemente

### 2. Domain-Driven Design
- **Entidades ricas**: Las entidades contienen lógica de negocio
- **Value Objects**: Inmutables y con validaciones
- **Repositorios**: Abstracción para el acceso a datos
- **Servicios de dominio**: Lógica de negocio que no pertenece a entidades

### 3. API Resources
- **Respuestas consistentes**: Uso de API Resources para formatear respuestas
- **Separación de datos**: Los DTOs separan la lógica de negocio de la presentación

### 4. Autenticación con Sanctum
- **Tokens seguros**: Generación y validación de tokens
- **Expiración**: Tokens con tiempo de expiración
- **Middleware personalizado**: Validación de tokens en cada request

## Endpoints de la API

### Authentication
```
POST /api/auth/login
POST /api/auth/logout
```

### Users
```
POST   /api/users          # Crear usuario
GET    /api/users/{id}     # Obtener usuario
PUT    /api/users/{id}     # Actualizar usuario
DELETE /api/users/{id}     # Eliminar usuario
```

## Ejemplos de Uso

### Crear Usuario
```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Juan Pérez",
    "email": "juan@example.com",
    "password": "password123"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "juan@example.com",
    "password": "password123"
  }'
```

### Obtener Usuario (con autenticación)
```bash
curl -X GET http://localhost:8000/api/users/{id} \
  -H "Authorization: Bearer {token}"
```

## Configuración

### 1. Instalar Laravel Sanctum
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 2. Registrar Service Providers
Los Service Providers ya están registrados en `AppServiceProvider.php`:
- `UserServiceProvider`
- `AuthenticationServiceProvider`

### 3. Configurar Middleware
El middleware `AuthenticateToken` está disponible para proteger rutas.

## Principios de Clean Code Aplicados

1. **Single Responsibility Principle**: Cada clase tiene una sola responsabilidad
2. **Dependency Inversion**: Las dependencias apuntan hacia abstracciones
3. **Open/Closed Principle**: Extensible sin modificar código existente
4. **Interface Segregation**: Interfaces pequeñas y específicas
5. **Dependency Injection**: Inyección de dependencias en constructores

## Testing

Cada capa puede ser testeada independientemente:
- **Domain**: Test unitarios para entidades y value objects
- **Application**: Test unitarios para casos de uso
- **Infrastructure**: Test de integración para repositorios
- **Interface**: Test de aceptación para controladores

## Ventajas de esta Implementación

1. **Mantenibilidad**: Código organizado y fácil de mantener
2. **Escalabilidad**: Fácil agregar nuevas funcionalidades
3. **Testabilidad**: Cada componente puede ser testeado aisladamente
4. **Flexibilidad**: Fácil cambiar implementaciones sin afectar el dominio
5. **Claridad**: Separación clara de responsabilidades 