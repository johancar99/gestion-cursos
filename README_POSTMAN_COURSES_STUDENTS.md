# Colección de Postman - Módulos Cursos, Estudiantes y Usuarios

Esta colección de Postman incluye todos los endpoints para los módulos de **Cursos**, **Estudiantes** y **Usuarios** de la API de Gestión de Cursos, incluyendo autenticación e inscripciones.

## 📋 Contenido de la Colección

### 🔐 Autenticación
- **Login**: Iniciar sesión para obtener token de autenticación
- **Logout**: Cerrar sesión y invalidar token

### 📚 Cursos
- **Crear Curso**: Crear un nuevo curso
- **Obtener Todos los Cursos**: Listar todos los cursos sin filtros
- **Obtener Cursos con Filtros**: Listar cursos con filtros opcionales
- **Obtener Curso por ID**: Obtener un curso específico
- **Actualizar Curso**: Modificar un curso existente
- **Eliminar Curso**: Eliminar un curso por su ID

### 👥 Estudiantes
- **Crear Estudiante**: Crear un nuevo estudiante
- **Obtener Todos los Estudiantes**: Listar todos los estudiantes sin filtros
- **Obtener Estudiantes con Filtros**: Listar estudiantes con filtros opcionales
- **Obtener Estudiante por ID**: Obtener un estudiante específico
- **Actualizar Estudiante**: Modificar un estudiante existente
- **Eliminar Estudiante**: Eliminar un estudiante por su ID

### 👤 Usuarios
- **Crear Usuario**: Crear un nuevo usuario
- **Obtener Todos los Usuarios**: Listar todos los usuarios sin filtros
- **Obtener Usuarios con Filtros**: Listar usuarios con filtros opcionales
- **Obtener Usuario por ID**: Obtener un usuario específico
- **Actualizar Usuario**: Modificar un usuario existente
- **Eliminar Usuario**: Eliminar un usuario por su ID

### 📝 Inscripciones
- **Crear Inscripción**: Inscribir un estudiante en un curso
- **Obtener Todas las Inscripciones**: Listar todas las inscripciones
- **Obtener Inscripción por ID**: Obtener una inscripción específica
- **Obtener Inscripciones por Curso**: Listar inscripciones de un curso
- **Obtener Inscripciones por Estudiante**: Listar inscripciones de un estudiante
- **Eliminar Inscripción**: Eliminar una inscripción por su ID

## 🚀 Instrucciones de Uso

### 1. Importar la Colección

1. Abre Postman
2. Haz clic en "Import" en la esquina superior izquierda
3. Selecciona el archivo `postman_collection_courses_students.json`
4. La colección se importará automáticamente

### 2. Configurar Variables de Entorno

Antes de usar la colección, configura las siguientes variables:

#### Variables Globales
- `base_url`: URL base de tu API (ej: `http://localhost:8000`)
- `auth_token`: Token de autenticación (se llena automáticamente después del login)

#### Variables de Ejemplo
- `course_id`: ID de un curso para pruebas
- `student_id`: ID de un estudiante para pruebas
- `user_id`: ID de un usuario para pruebas
- `enrollment_id`: ID de una inscripción para pruebas

### 3. Flujo de Uso Recomendado

#### Paso 1: Autenticación
1. Ejecuta el endpoint **Login** con credenciales válidas
2. Copia el token de la respuesta y guárdalo en la variable `auth_token`

#### Paso 2: Crear Datos de Prueba
1. **Crear Curso**: Crea un curso de prueba
2. **Crear Estudiante**: Crea un estudiante de prueba
3. Guarda los IDs retornados en las variables correspondientes

#### Paso 3: Probar Funcionalidades
1. **Crear Inscripción**: Inscribe el estudiante en el curso
2. **Obtener Inscripciones por Curso**: Verifica la inscripción
3. **Obtener Inscripciones por Estudiante**: Verifica desde el lado del estudiante

## 📊 Estructura de Datos

### Curso
```json
{
    "title": "Programación Web Avanzada",
    "description": "Curso completo de programación web con PHP, Laravel, JavaScript y React.",
    "start_date": "2024-02-01",
    "end_date": "2024-06-30"
}
```

### Estudiante
```json
{
    "first_name": "Juan Carlos",
    "last_name": "García López",
    "email": "juan.garcia@example.com"
}
```

### Usuario
```json
{
    "name": "Juan Pérez",
    "email": "juan.perez@example.com",
    "password": "password123"
}
```

### Inscripción
```json
{
    "course_id": "uuid-del-curso",
    "student_id": "uuid-del-estudiante"
}
```

## 🔧 Configuración del Servidor

Asegúrate de que tu servidor Laravel esté configurado correctamente:

1. **Base de datos**: Ejecuta las migraciones
   ```bash
   php artisan migrate
   ```

2. **Usuarios de prueba**: Ejecuta los seeders
   ```bash
   php artisan db:seed
   ```

3. **Servidor**: Inicia el servidor de desarrollo
   ```bash
   php artisan serve
   ```

## 📝 Notas Importantes

### Autenticación
- Todos los endpoints (excepto login) requieren autenticación
- El token debe incluirse en el header `Authorization: Bearer {token}`
- El token se obtiene del endpoint de login

### Validaciones
- **Cursos**: Título (2-255 chars), descripción (10-1000 chars), fechas válidas
- **Estudiantes**: Nombre y apellido (máx 255 chars), email único
- **Usuarios**: Nombre (máx 255 chars), email único, password (mín 6 chars)
- **Inscripciones**: IDs válidos de curso y estudiante

### Filtros Disponibles

#### Cursos
- `title`: Filtro por título (búsqueda parcial)
- `description`: Filtro por descripción (búsqueda parcial)
- `start_date`: Filtro por fecha de inicio
- `end_date`: Filtro por fecha de fin
- `date_range`: Filtro por rango de fechas

#### Estudiantes
- `first_name`: Filtro por nombre (búsqueda parcial)
- `last_name`: Filtro por apellido (búsqueda parcial)
- `email`: Filtro por email (búsqueda parcial)
- `name`: Filtro por nombre completo (busca en nombre y apellido)
- `created_at`: Filtro por fecha de creación
- `date_range`: Filtro por rango de fechas

#### Usuarios
- `name`: Filtro por nombre (búsqueda parcial)
- `email`: Filtro por email (búsqueda parcial)
- `created_at`: Filtro por fecha de creación
- `date_range`: Filtro por rango de fechas

### Códigos de Respuesta
- `200`: Operación exitosa
- `201`: Recurso creado exitosamente
- `400`: Error de validación
- `401`: No autenticado
- `404`: Recurso no encontrado
- `500`: Error interno del servidor

## 🧪 Casos de Prueba

### Flujo Completo de Gestión de Cursos
1. Login → Obtener token
2. Crear Curso → Guardar ID
3. Obtener Todos los Cursos → Verificar creación
4. Obtener Curso por ID → Verificar datos
5. Actualizar Curso → Verificar cambios
6. Eliminar Curso → Verificar eliminación

### Flujo Completo de Gestión de Estudiantes
1. Login → Obtener token
2. Crear Estudiante → Guardar ID
3. Obtener Todos los Estudiantes → Verificar creación
4. Obtener Estudiantes con Filtros → Probar filtros
5. Obtener Estudiante por ID → Verificar datos
6. Actualizar Estudiante → Verificar cambios
7. Eliminar Estudiante → Verificar eliminación

### Flujo Completo de Gestión de Usuarios
1. Login → Obtener token
2. Crear Usuario → Guardar ID
3. Obtener Todos los Usuarios → Verificar creación
4. Obtener Usuarios con Filtros → Probar filtros
5. Obtener Usuario por ID → Verificar datos
6. Actualizar Usuario → Verificar cambios
7. Eliminar Usuario → Verificar eliminación

### Flujo Completo de Inscripciones
1. Login → Obtener token
2. Crear Curso y Estudiante → Guardar IDs
3. Crear Inscripción → Verificar creación
4. Obtener Inscripciones por Curso → Verificar relación
5. Obtener Inscripciones por Estudiante → Verificar relación
6. Eliminar Inscripción → Verificar eliminación

## 🔍 Troubleshooting

### Error 401 - No autenticado
- Verifica que el token esté configurado correctamente
- Asegúrate de que el token no haya expirado
- Ejecuta el login nuevamente

### Error 404 - Recurso no encontrado
- Verifica que los IDs sean válidos
- Asegúrate de que los recursos existan en la base de datos

### Error 400 - Error de validación
- Revisa los campos requeridos
- Verifica el formato de los datos (fechas, emails, etc.)
- Consulta los mensajes de error en la respuesta

### Error 500 - Error interno
- Verifica que el servidor esté funcionando
- Revisa los logs de Laravel
- Asegúrate de que la base de datos esté configurada correctamente

## 📞 Soporte

Si tienes problemas con la colección o la API:

1. Verifica que el servidor esté funcionando
2. Revisa los logs de Laravel en `storage/logs/laravel.log`
3. Asegúrate de que todas las dependencias estén instaladas
4. Verifica la configuración de la base de datos

---

**Colección creada para el proyecto de Gestión de Cursos - Módulos Cursos y Estudiantes** 