# Colección Postman - Módulo de Usuarios

Esta colección de Postman contiene todos los endpoints necesarios para probar el módulo de usuarios de la API de Gestión de Cursos.

## 📋 Contenido de la Colección

### 🔐 Autenticación
- **Login**: Iniciar sesión para obtener token de autenticación
- **Logout**: Cerrar sesión y invalidar token

### 👥 Usuarios
- **Crear Usuario**: Crear un nuevo usuario
- **Obtener Usuario**: Obtener información de un usuario específico
- **Actualizar Usuario**: Actualizar información de un usuario existente
- **Eliminar Usuario**: Eliminar un usuario

## 🚀 Instrucciones de Uso

### 1. Importar la Colección
1. Abre Postman
2. Haz clic en "Import" en la esquina superior izquierda
3. Selecciona el archivo `postman_collection_users.json`
4. La colección se importará automáticamente

### 2. Configurar Variables de Entorno
Antes de usar la colección, configura las siguientes variables:

#### Variables de Colección
- `base_url`: URL base de tu API (por defecto: `http://localhost:8000`)
- `auth_token`: Token de autenticación (se llena automáticamente después del login)
- `user_id`: ID del usuario para operaciones específicas

### 3. Flujo de Pruebas Recomendado

#### Paso 1: Login
1. Ejecuta la petición **Login** con credenciales válidas
2. Copia el token de la respuesta y guárdalo en la variable `auth_token`

#### Paso 2: Crear Usuario
1. Ejecuta **Crear Usuario** con los datos deseados
2. Guarda el ID del usuario creado en la variable `user_id`

#### Paso 3: Obtener Usuario
1. Ejecuta **Obtener Usuario** para verificar que se creó correctamente

#### Paso 4: Actualizar Usuario
1. Ejecuta **Actualizar Usuario** con nuevos datos

#### Paso 5: Eliminar Usuario
1. Ejecuta **Eliminar Usuario** para eliminar el usuario de prueba

#### Paso 6: Logout
1. Ejecuta **Logout** para cerrar la sesión

## 📝 Ejemplos de Datos

### Login
```json
{
    "email": "admin@example.com",
    "password": "password123"
}
```

### Crear Usuario
```json
{
    "name": "Juan Pérez",
    "email": "juan.perez@example.com",
    "password": "password123"
}
```

### Actualizar Usuario
```json
{
    "name": "Juan Carlos Pérez",
    "email": "juan.carlos@example.com",
    "password": "newpassword123"
}
```

## 🔍 Validaciones Incluidas

### Campos Requeridos para Crear Usuario
- `name`: Obligatorio, mínimo 2 caracteres, máximo 255
- `email`: Obligatorio, formato válido, único en la base de datos
- `password`: Obligatorio, mínimo 8 caracteres

### Campos Opcionales para Actualizar Usuario
- `name`: Opcional, mínimo 2 caracteres, máximo 255
- `email`: Opcional, formato válido, único (excepto el usuario actual)
- `password`: Opcional, mínimo 8 caracteres

## 🛡️ Autenticación

Todos los endpoints de usuarios requieren autenticación mediante token Bearer. El token se obtiene del endpoint de login y debe incluirse en el header `Authorization` de todas las peticiones.

## 📊 Códigos de Respuesta

- `200`: Operación exitosa
- `201`: Usuario creado exitosamente
- `400`: Error de validación
- `401`: No autorizado
- `404`: Usuario no encontrado
- `500`: Error interno del servidor

## 🔧 Configuración del Servidor

Asegúrate de que tu servidor Laravel esté ejecutándose en la URL configurada en la variable `base_url`. Por defecto está configurado para `http://localhost:8000`.

## 📚 Endpoints Disponibles

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/v1/auth/login` | Iniciar sesión |
| POST | `/api/v1/auth/logout` | Cerrar sesión |
| POST | `/api/v1/users` | Crear usuario |
| GET | `/api/v1/users/{id}` | Obtener usuario |
| PUT | `/api/v1/users/{id}` | Actualizar usuario |
| DELETE | `/api/v1/users/{id}` | Eliminar usuario |

## 🚨 Notas Importantes

1. **Autenticación**: Todos los endpoints de usuarios requieren token de autenticación
2. **Validación**: Los datos se validan tanto en el frontend como en el backend
3. **Unicidad**: El email debe ser único en la base de datos
4. **Seguridad**: Las contraseñas se hashean automáticamente
5. **Logs**: Todas las operaciones se registran en los logs del sistema

## 🐛 Solución de Problemas

### Error 401 - No autorizado
- Verifica que el token de autenticación sea válido
- Asegúrate de haber ejecutado el login correctamente

### Error 404 - Usuario no encontrado
- Verifica que el ID del usuario sea correcto
- Asegúrate de que el usuario exista en la base de datos

### Error 400 - Error de validación
- Revisa que todos los campos requeridos estén presentes
- Verifica que los datos cumplan con las reglas de validación

### Error 500 - Error interno
- Revisa los logs del servidor para más detalles
- Verifica que la base de datos esté funcionando correctamente 