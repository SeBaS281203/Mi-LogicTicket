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
    // 1. Borrar cualquier cosa a medias
    \Illuminate\Support\Facades\Artisan::call('db:wipe');

    // 2. Borrar procedimientos anteriores
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_approve_event;');
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_dashboard_stats;');
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_reject_event;');
    \Illuminate\Support\Facades\DB::unprepared('DROP PROCEDURE IF EXISTS sp_revenue_by_month;');

    // 3. APAGAR REGLA ESTRICTA DE AIVEN E INSTALAR TUS DATOS
    $sql = "SET SESSION sql_require_primary_key = 0;\n" . file_get_contents(base_path('mysql-init/01-schema.sql'));
    \Illuminate\Support\Facades\DB::unprepared($sql);

    return '¡VICTORIA TOTAL! Base de datos instalada con éxito.';
});