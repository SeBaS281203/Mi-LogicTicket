<?php

/*
|--------------------------------------------------------------------------
| Web Routes - LogicTicket
|--------------------------------------------------------------------------
| Rutas modulares: public, admin, organizer, cuenta.
*/

require __DIR__ . '/public.php';
require __DIR__ . '/cuenta.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/organizer.php';

Route::get('/instalar-bd', function () {
    \Illuminate\Support\Facades\DB::unprepared(file_get_contents(base_path('mysql-init/01-schema.sql')));
    return '¡Base de datos lista en la nube y tablas creadas!';
});