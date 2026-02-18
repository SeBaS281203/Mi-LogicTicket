<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Crea procedimientos almacenados solo para MySQL.
     * Para procedimientos con múltiples sentencias (DELIMITER $$), ejecutar
     * manualmente el archivo database/procedures/logicticket_stored_procedures.sql
     * en phpMyAdmin (pestaña SQL) sobre la base de datos logicticket.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        // sp_dashboard_stats: estadísticas en una sola SELECT
        DB::unprepared("
            CREATE PROCEDURE sp_dashboard_stats()
            SELECT
                (SELECT COUNT(*) FROM users) AS total_users,
                (SELECT COUNT(*) FROM users WHERE role = 'organizer') AS total_organizers,
                (SELECT COUNT(*) FROM events) AS total_events,
                (SELECT COUNT(*) FROM events WHERE status = 'published') AS events_published,
                (SELECT COUNT(*) FROM events WHERE status = 'pending_approval') AS events_pending,
                (SELECT COUNT(*) FROM events WHERE status = 'published' AND start_date >= CURDATE()) AS events_active,
                (SELECT COUNT(*) FROM orders WHERE status = 'paid') AS total_orders,
                (SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'paid') AS total_revenue;
        ");

        // sp_revenue_by_month: ingresos por mes (una sola SELECT)
        DB::unprepared("
            CREATE PROCEDURE sp_revenue_by_month()
            SELECT
                DATE_FORMAT(created_at, '%Y-%m') AS month,
                COALESCE(SUM(total), 0) AS revenue
            FROM orders
            WHERE status = 'paid'
              AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month;
        ");

        // sp_approve_event
        DB::unprepared("
            CREATE PROCEDURE sp_approve_event(IN p_event_id INT)
            UPDATE events SET status = 'published' WHERE id = p_event_id AND status = 'pending_approval';
        ");

        // sp_reject_event
        DB::unprepared("
            CREATE PROCEDURE sp_reject_event(IN p_event_id INT)
            UPDATE events SET status = 'draft' WHERE id = p_event_id AND status = 'pending_approval';
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_dashboard_stats');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_revenue_by_month');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_approve_event');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_reject_event');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_orders_report');
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_event_sales_summary');
    }
};
