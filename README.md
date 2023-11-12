
# Realizado por BadrWeb
<p align="center">
    <img src="image-1.jpeg" width="400" alt="Laravel Logo">
</p>

## Proyecto piedra, papel o tijeras

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="image-2.jpeg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

## Seed

Primero, ejecuta los siguientes comandos de semilla en este orden: usuarios, rondas y luego partidas.

```shell
php artisan db:seed --class=UsuarioSeeder
php artisan db:seed --class=RondaSeeder
php artisan db:seed --class=PartidaSeeder
```

## EndPoints

#### Todas las siguentes rutas contendrán /api

### Crear un nuevo usuario

Envía una solicitud POST a `/admin/user` o `/signup`con los siguientes datos JSON:

```json
{
  "name": "Miguel",
  "email": "miguel@example.com",
  "password": "StrongPassword!23"
}
```

La respuesta debería ser similar a la siguiente:

```json
{
  "success": true,
  "data": {
    "token": "7|EMBZbjr5eT9C93y2jSntCuXTIgpCdympf2zTT33nb04a4e60",
    "name": "test"
  },
  "message": "User successfully registered!"
}
```

Para interactuar con el servidor, agrega el token obtenido en la autenticación Bearer:

```
Bearer 7|EMBZbjr5eT9C93y2jSntCuXTIgpCdympf2zTT33nb04a4e60
```

### Crear una nueva ronda

Envía una solicitud JSON POST a `/admin/ronda` con los siguientes datos:

```json
{
  "id_user_1": 1,
  "id_user_2": 10
}
```

### Crear una nueva partida

Envía una solicitud JSON POST a `/admin/partida` con los siguientes datos:

```json
{
  "id_ronda": 1,
  "id_user_1": 1,
  "id_user_2": 2,
  "tirada_user_1": "rock",
  "tirada_user_2": "paper"
}
```

## Rutas y Control de Acceso

Las rutas y el control de acceso en la aplicación están configurados de la siguiente manera:

- Las rutas en el grupo `/admin` requieren autenticación y permisos de administrador.
- Las rutas fuera del grupo `/admin` permiten a los usuarios autenticados crear nuevas partidas, rondas y usuarios.

A continuación, se describen las rutas y sus respectivas funcionalidades:

### Rutas de Administrador (`/admin`)

- `/admin/user`: Permite la gestión de usuarios, incluyendo listar, mostrar, actualizar, crear y eliminar usuarios.
- `/admin/partida`: Permite la gestión de partidas, incluyendo listar, mostrar, actualizar, crear y eliminar partidas.
- `/admin/ronda`: Permite la gestión de rondas, incluyendo listar, mostrar, actualizar, crear y eliminar rondas.

### Rutas de Creación de Partidas, Rondas y Usuarios (`/partida`, `/ronda`, `/user`)

Estas rutas permiten a los usuarios autenticados crear nuevos registros, como partidas, rondas y usuarios.

## Controladores

Los controladores asociados a estas rutas se encuentran en la aplicación. Los controladores controlan la lógica de la aplicación y gestionan las solicitudes del usuario.

- `ControllerUsuario`: Controla las operaciones relacionadas con los usuarios.
- `ControllerPartida`: Controla las operaciones relacionadas con las partidas.
- `ControllerRonda`: Controla las operaciones relacionadas con las rondas.

## Autenticación

La autenticación de usuarios se maneja a través de Laravel Sanctum, un paquete de autenticación de Laravel. Los usuarios obtienen un token de acceso después de registrarse o iniciar sesión, y este token se utiliza en las solicitudes autenticadas.

## Licencia

El framework Laravel es de código abierto y está bajo la [licencia MIT](https://opensource.org/licenses/MIT), lo que significa que puedes usarlo libremente en tus proyectos.

Esperamos que esta guía te sea de ayuda para comprender y utilizar la aplicación. Si tienes alguna pregunta o necesitas asistencia adicional, no dudes en ponerte en contacto con nosotros.

¡Gracias por usar Laravel y buena suerte con tu proyecto!