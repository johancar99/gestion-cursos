# Tests del Módulo User

Este documento describe la estructura de tests para el módulo User y Authentication implementado con Pest.

## Estructura de Tests

```
tests/
├── Feature/                    # Tests de integración (endpoints)
│   ├── UserTest.php           # Tests para endpoints de usuarios
│   └── AuthenticationTest.php # Tests para endpoints de autenticación
├── Unit/                      # Tests unitarios
│   ├── Domain/               # Tests del dominio
│   │   └── User/
│   │       └── ValueObjects/
│   │           ├── EmailTest.php
│   │           ├── NameTest.php
│   │           └── PasswordTest.php
│   ├── Application/           # Tests de la capa de aplicación
│   │   └── User/
│   │       └── UseCases/
│   │           └── CreateUserUseCaseTest.php
│   └── Infrastructure/        # Tests de la capa de infraestructura
│       └── Persistence/
│           └── Eloquent/
│               └── UserRepositoryTest.php
├── Helpers/                   # Helpers para tests
│   └── UserTestHelper.php
├── Pest.php                   # Configuración de Pest
└── TestCase.php              # Clase base para tests
```

## Tipos de Tests

### 1. Feature Tests (Tests de Integración)

**Ubicación**: `tests/Feature/`

#### UserTest.php
- Tests para todos los endpoints de usuarios (CRUD)
- Validaciones de entrada
- Respuestas de API
- Persistencia en base de datos

**Endpoints cubiertos**:
- `POST /api/users` - Crear usuario
- `GET /api/users/{id}` - Obtener usuario
- `PUT /api/users/{id}` - Actualizar usuario
- `DELETE /api/users/{id}` - Eliminar usuario

#### AuthenticationTest.php
- Tests para endpoints de autenticación
- Validación de credenciales
- Generación y validación de tokens
- Logout

**Endpoints cubiertos**:
- `POST /api/auth/login` - Login
- `POST /api/auth/logout` - Logout

### 2. Unit Tests (Tests Unitarios)

**Ubicación**: `tests/Unit/`

#### Value Objects Tests
- **EmailTest.php**: Validación de formato de email
- **NameTest.php**: Validación de nombres
- **PasswordTest.php**: Validación de contraseñas

#### Use Cases Tests
- **CreateUserUseCaseTest.php**: Lógica de creación de usuarios
- Tests con mocks para aislar dependencias

#### Repository Tests
- **UserRepositoryTest.php**: Persistencia y mapeo de datos
- Tests de integración con base de datos

## Ejecutar Tests

### Ejecutar todos los tests
```bash
./vendor/bin/pest
```

### Ejecutar tests específicos
```bash
# Solo tests de Feature
./vendor/bin/pest tests/Feature/

# Solo tests unitarios
./vendor/bin/pest tests/Unit/

# Tests específicos
./vendor/bin/pest tests/Feature/UserTest.php
./vendor/bin/pest tests/Unit/Domain/User/ValueObjects/EmailTest.php
```

### Ejecutar tests con cobertura
```bash
./vendor/bin/pest --coverage
```

### Ejecutar tests en paralelo
```bash
./vendor/bin/pest --parallel
```

## Helpers

### UserTestHelper
Ubicado en `tests/Helpers/UserTestHelper.php`

Proporciona métodos para crear entidades de prueba:

```php
// Crear usuario con datos por defecto
$user = UserTestHelper::createUser();

// Crear usuario con datos específicos
$user = UserTestHelper::createUser('Juan Pérez', 'juan@example.com', 'password123');

// Crear usuario con ID específico
$user = UserTestHelper::createUserWithId('test-id', 'Juan Pérez', 'juan@example.com');

// Obtener datos de usuario para requests
$userData = UserTestHelper::getUserData();
```

## Configuración

### Pest.php
Archivo de configuración principal que define:
- Clases base para tests
- Expectations personalizadas
- Funciones helper globales

### RefreshDatabase
Los tests de Feature usan `RefreshDatabase` para limpiar la base de datos entre tests.

## Casos de Prueba Cubiertos

### User Endpoints
1. **Crear Usuario**
   - ✅ Creación exitosa
   - ✅ Validación de email inválido
   - ✅ Validación de contraseña corta
   - ✅ Validación de email duplicado

2. **Obtener Usuario**
   - ✅ Obtener usuario existente
   - ✅ Error 404 para usuario inexistente

3. **Actualizar Usuario**
   - ✅ Actualización completa
   - ✅ Actualización parcial
   - ✅ Validación de email duplicado

4. **Eliminar Usuario**
   - ✅ Eliminación exitosa
   - ✅ Error 404 para usuario inexistente

### Authentication Endpoints
1. **Login**
   - ✅ Login exitoso
   - ✅ Credenciales inválidas
   - ✅ Email inexistente
   - ✅ Validaciones de entrada

2. **Logout**
   - ✅ Logout exitoso
   - ✅ Token inválido
   - ✅ Sin token

### Value Objects
1. **Email**
   - ✅ Validación de formato
   - ✅ Conversión a minúsculas
   - ✅ Límites de longitud
   - ✅ Comparación de igualdad

2. **Name**
   - ✅ Validación de longitud
   - ✅ Caracteres especiales
   - ✅ Comparación de igualdad

3. **Password**
   - ✅ Validación de longitud mínima
   - ✅ Hashing de contraseñas
   - ✅ Verificación de contraseñas

### Repository
1. **UserRepository**
   - ✅ Guardar usuario
   - ✅ Buscar por ID
   - ✅ Buscar por email
   - ✅ Eliminar usuario
   - ✅ Verificar existencia de email
   - ✅ Actualizar usuario
   - ✅ Mapeo de datos

## Mejores Prácticas

1. **Aislamiento**: Cada test es independiente
2. **Mocks**: Uso de mocks para aislar dependencias
3. **Helpers**: Reutilización de código con helpers
4. **Descriptive Names**: Nombres descriptivos para los tests
5. **Arrange-Act-Assert**: Estructura clara en los tests
6. **Database Cleanup**: Limpieza automática de base de datos

## Cobertura de Código

Los tests cubren:
- ✅ Todos los endpoints de la API
- ✅ Todos los Value Objects del dominio
- ✅ Casos de uso principales
- ✅ Repositorios de infraestructura
- ✅ Validaciones de entrada
- ✅ Manejo de errores

## Debugging

Para debuggear tests específicos:

```bash
# Ejecutar un test específico con verbose
./vendor/bin/pest tests/Feature/UserTest.php --verbose

# Ejecutar con detención en errores
./vendor/bin/pest --stop-on-failure
``` 