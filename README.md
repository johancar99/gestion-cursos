# API de Gestión de Cursos

Sistema completo de gestión de cursos desarrollado con Laravel 12, que incluye autenticación, autorización basada en roles, y una API REST completa para administrar usuarios, cursos, estudiantes y matrículas.

## 🚀 Características

- **Arquitectura Clean Architecture**: Separación clara de responsabilidades
- **Autenticación con Laravel Sanctum**: Tokens de acceso seguros
- **Autorización con Spatie Laravel Permission**: Sistema de roles y permisos
- **API REST completa**: Endpoints para todos los módulos
- **Documentación automática**: Generada con Scramble
- **Testing con Pest**: Framework de testing moderno
- **Docker con Laravel Sail**: Entorno de desarrollo containerizado

## 📋 Requisitos Previos

- Docker Desktop
- Git
- Editor de código (VS Code recomendado)

## 🛠️ Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd gestion-cursos
```

### 2. Configurar el entorno

```bash
# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
./vendor/bin/sail artisan key:generate
```

### 3. Configurar la base de datos

Editar el archivo `.env` con la configuración de la base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=gestion_cursos
DB_USERNAME=sail
DB_PASSWORD=password
```

### 4. Instalar dependencias y ejecutar migraciones

```bash
# Instalar dependencias de PHP
./vendor/bin/sail composer install

# Ejecutar migraciones
./vendor/bin/sail artisan migrate

# Ejecutar seeders para datos iniciales
./vendor/bin/sail artisan db:seed
```

### 5. Iniciar el servidor

```bash
# Iniciar todos los servicios
./vendor/bin/sail up -d

# Verificar que esté funcionando
./vendor/bin/sail ps
```

## 🌐 Acceso a la Aplicación

- **API**: http://localhost/api
- **Documentación API**: http://localhost/docs/api
- **Aplicación Web**: http://localhost

## 📚 Estructura del Proyecto

```
gestion-cursos/
├── app/
│   ├── Application/          # Casos de uso y servicios
│   ├── Domain/              # Entidades y repositorios
│   ├── Infrastructure/      # Implementaciones externas
│   └── Interfaces/          # Controladores y recursos HTTP
├── config/                  # Configuraciones
├── database/                # Migraciones y seeders
├── routes/                  # Definición de rutas
├── tests/                   # Tests con Pest
└── README_SCRAMBLE.md       # Documentación de la API
```

## 🔐 Autenticación y Autorización

### Usuario Administrador por Defecto

```bash
Email: admin@example.com
Password: password
```

### Roles Disponibles

- **admin**: Acceso completo al sistema
- **user**: Acceso limitado

### Permisos por Módulo

- **Users**: `create users`, `view users`, `edit users`, `delete users`
- **Courses**: `create courses`, `view courses`, `edit courses`, `delete courses`
- **Students**: `create students`, `view students`, `edit students`, `delete students`
- **Enrollments**: `create enrollments`, `view enrollments`, `delete enrollments`

## 📖 Documentación de la API

### Acceso a la Documentación

1. **Interfaz Web Interactiva**: http://localhost/docs/api
2. **Especificación OpenAPI**: `api.json`

### Endpoints Principales

#### Autenticación
- `POST /api/v1/auth/login` - Iniciar sesión
- `POST /api/v1/auth/logout` - Cerrar sesión

#### Usuarios
- `GET /api/v1/users` - Listar usuarios
- `POST /api/v1/users` - Crear usuario
- `GET /api/v1/users/{id}` - Obtener usuario
- `PUT /api/v1/users/{id}` - Actualizar usuario
- `DELETE /api/v1/users/{id}` - Eliminar usuario

#### Cursos
- `GET /api/v1/courses` - Listar cursos
- `POST /api/v1/courses` - Crear curso
- `GET /api/v1/courses/{id}` - Obtener curso
- `PUT /api/v1/courses/{id}` - Actualizar curso
- `DELETE /api/v1/courses/{id}` - Eliminar curso

#### Estudiantes
- `GET /api/v1/students` - Listar estudiantes
- `POST /api/v1/students` - Crear estudiante
- `GET /api/v1/students/{id}` - Obtener estudiante
- `PUT /api/v1/students/{id}` - Actualizar estudiante
- `DELETE /api/v1/students/{id}` - Eliminar estudiante

#### Matrículas
- `GET /api/v1/enrollments` - Listar matrículas
- `POST /api/v1/enrollments` - Crear matrícula
- `GET /api/v1/enrollments/{id}` - Obtener matrícula
- `GET /api/v1/enrollments/course/{courseId}` - Matrículas por curso
- `GET /api/v1/enrollments/student/{studentId}` - Matrículas por estudiante
- `DELETE /api/v1/enrollments/{id}` - Eliminar matrícula

### Ejemplo de Uso

```bash
# Obtener token de autenticación
curl -X POST http://localhost/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password"
  }'

# Usar el token para acceder a endpoints protegidos
curl -X GET http://localhost/api/v1/users \
  -H "Authorization: Bearer {token}"
```

## 🧪 Testing

### Ejecutar Tests

```bash
# Ejecutar todos los tests
./vendor/bin/sail artisan test

# Ejecutar tests específicos
./vendor/bin/sail artisan test --filter=UserTest

# Ejecutar tests con coverage
./vendor/bin/sail artisan test --coverage
```

### Tipos de Tests

- **Feature Tests**: Tests de integración para endpoints
- **Unit Tests**: Tests unitarios para lógica de negocio
- **Helpers**: Clases auxiliares para testing

## 🛠️ Comandos Útiles

### Laravel Sail

```bash
# Iniciar servicios
./vendor/bin/sail up -d

# Detener servicios
./vendor/bin/sail down

# Ver logs
./vendor/bin/sail logs

# Ejecutar comandos artisan
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed
./vendor/bin/sail artisan route:list
```

### Desarrollo

```bash
# Limpiar caché
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear

# Regenerar documentación
./vendor/bin/sail artisan scramble:export

# Ejecutar análisis estático
./vendor/bin/sail composer pint
```

### Base de Datos

```bash
# Ejecutar migraciones
./vendor/bin/sail artisan migrate

# Revertir migraciones
./vendor/bin/sail artisan migrate:rollback

# Ejecutar seeders
./vendor/bin/sail artisan db:seed

# Refrescar base de datos
./vendor/bin/sail artisan migrate:fresh --seed
```

## 📦 Dependencias Principales

### Backend (PHP)

- **Laravel 12**: Framework PHP
- **Laravel Sanctum**: Autenticación API
- **Spatie Laravel Permission**: Gestión de roles y permisos
- **Dedoc Scramble**: Documentación automática de API
- **Pest**: Framework de testing

### Frontend

- **Vite**: Build tool
- **Tailwind CSS**: Framework CSS
- **Alpine.js**: Framework JavaScript

### Docker

- **Laravel Sail**: Entorno de desarrollo Docker
- **MySQL 8.0**: Base de datos
- **Redis**: Cache y sesiones
- **Mailpit**: Servidor de correo para desarrollo

## 🔧 Configuración

### Variables de Entorno

```env
APP_NAME="API de Gestión de Cursos"
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=gestion_cursos
DB_USERNAME=sail
DB_PASSWORD=password

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Configuración de Scramble

```php
// config/scramble.php
'title' => 'API de Gestión de Cursos',
'version' => '1.0.0',
'description' => 'Sistema completo para administrar cursos...',
```

## 🚀 Despliegue

### Producción

1. Configurar variables de entorno para producción
2. Ejecutar migraciones: `php artisan migrate --force`
3. Optimizar autoloader: `composer install --optimize-autoloader --no-dev`
4. Cachear configuraciones: `php artisan config:cache`

### Docker Production

```bash
# Construir imagen de producción
docker build -t gestion-cursos .

# Ejecutar contenedor
docker run -p 80:80 gestion-cursos
```

## 📝 Contribución

1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 🤝 Soporte

- **Documentación API**: http://localhost/docs/api
- **Issues**: Crear un issue en el repositorio
- **Documentación Laravel**: https://laravel.com/docs
- **Documentación Scramble**: https://scramble.dedoc.co/

## 📚 Recursos Adicionales

- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission)
- [Scramble Documentation](https://scramble.dedoc.co/)
- [Pest Testing](https://pestphp.com/)
- [Laravel Sail](https://laravel.com/docs/sail)

---

**Desarrollado con ❤️ usando Laravel 12 y Clean Architecture**
