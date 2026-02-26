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
    // 1. Borrar cualquier tabla o dato a medias
    \Illuminate\Support\Facades\Artisan::call('db:wipe');

    // 2. Borrar los procedimientos si ya existían
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_approve_event;');
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_dashboard_stats;');
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_reject_event;');
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_revenue_by_month;');

    // 3. Ahora sí, instalar tu archivo SQL limpio
    \Illuminate\Support\Facades\DB::unprepared(file_get_contents(base_path('mysql-init/01-schema.sql')));

    return '¡Limpieza profunda terminada y base de datos instalada con éxito!';
});