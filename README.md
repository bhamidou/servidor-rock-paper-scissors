<p align="center"><a href="https://laravel.com" target="_blank"><img src="paper-stone-scissors-wallpaper-preview.jpg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Seed

primero el de usuarios y luego el de rondas y por último el de las partidas

php artisan db:seed --class=UsuarioSeeder

php artisan db:seed --class=RondaSeeder

php artisan db:seed --class=PartidaSeeder


## EndPoints

### New user

`POST: /admin/user`

```json
{
  "nombre": "Miguel",
  "email": "miguel@example.com",
  "password": "StrongPassword!23"
}
```

crear nueva ronda:

{
  "id_user_1" : 1,
  "id_user_2" : 10,
}



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
