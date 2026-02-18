-- =====================================================
-- LogicTicket - Procedimientos almacenados (MySQL)
-- Base de datos: logicticket
-- Ejecutar en phpMyAdmin (pestaña SQL) o desde la CLI
-- =====================================================

-- Eliminar procedimientos si existen (para poder recrearlos)
DROP PROCEDURE IF EXISTS sp_dashboard_stats;
DROP PROCEDURE IF EXISTS sp_revenue_by_month;
DROP PROCEDURE IF EXISTS sp_approve_event;
DROP PROCEDURE IF EXISTS sp_reject_event;
DROP PROCEDURE IF EXISTS sp_orders_report;
DROP PROCEDURE IF EXISTS sp_event_sales_summary;

DELIMITER $$

-- ---------------------------------------------------------------------------
-- sp_dashboard_stats: Estadísticas generales del dashboard
-- Retorna una fila con totales de usuarios, eventos, órdenes e ingresos
-- ---------------------------------------------------------------------------
CREATE PROCEDURE sp_dashboard_stats()
BEGIN
    SELECT
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM users WHERE role = 'organizer') AS total_organizers,
        (SELECT COUNT(*) FROM events) AS total_events,
        (SELECT COUNT(*) FROM events WHERE status = 'published') AS events_published,
        (SELECT COUNT(*) FROM events WHERE status = 'pending_approval') AS events_pending,
        (SELECT COUNT(*) FROM events WHERE status = 'published' AND start_date >= CURDATE()) AS events_active,
        (SELECT COUNT(*) FROM orders WHERE status = 'paid') AS total_orders,
        (SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'paid') AS total_revenue;
END$$

-- ---------------------------------------------------------------------------
-- sp_revenue_by_month: Ingresos por mes (últimos 12 meses)
-- Útil para gráficos en el dashboard
-- ---------------------------------------------------------------------------
CREATE PROCEDURE sp_revenue_by_month()
BEGIN
    SELECT
        DATE_FORMAT(created_at, '%Y-%m') AS month,
        COALESCE(SUM(total), 0) AS revenue
    FROM orders
    WHERE status = 'paid'
      AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month;
END$$

-- ---------------------------------------------------------------------------
-- sp_approve_event: Aprueba un evento (cambia a publicado)
-- Parámetros: p_event_id INT
-- ---------------------------------------------------------------------------
CREATE PROCEDURE sp_approve_event(IN p_event_id INT)
BEGIN
    UPDATE events
    SET status = 'published'
    WHERE id = p_event_id AND status = 'pending_approval';
    SELECT ROW_COUNT() AS rows_affected;
END$$

-- ---------------------------------------------------------------------------
-- sp_reject_event: Rechaza un evento (vuelve a borrador)
-- Parámetros: p_event_id INT
-- ---------------------------------------------------------------------------
CREATE PROCEDURE sp_reject_event(IN p_event_id INT)
BEGIN
    UPDATE events
    SET status = 'draft'
    WHERE id = p_event_id AND status = 'pending_approval';
    SELECT ROW_COUNT() AS rows_affected;
END$$

-- ---------------------------------------------------------------------------
-- sp_orders_report: Listado de órdenes en un rango de fechas
-- Parámetros: p_date_from DATE, p_date_to DATE
-- ---------------------------------------------------------------------------
CREATE PROCEDURE sp_orders_report(IN p_date_from DATE, IN p_date_to DATE)
BEGIN
    SELECT
        order_number,
        customer_email,
        customer_name,
        subtotal,
        COALESCE(commission_amount, 0) AS commission_amount,
        total,
        status,
        payment_method,
        created_at
    FROM orders
    WHERE DATE(created_at) BETWEEN p_date_from AND p_date_to
    ORDER BY created_at DESC;
END$$

-- ---------------------------------------------------------------------------
-- sp_event_sales_summary: Resumen de ventas por evento
-- Parámetros: p_event_id INT
-- ---------------------------------------------------------------------------
CREATE PROCEDURE sp_event_sales_summary(IN p_event_id INT)
BEGIN
    SELECT
        e.id AS event_id,
        e.title AS event_title,
        e.status,
        COUNT(DISTINCT oi.order_id) AS orders_count,
        COALESCE(SUM(oi.quantity), 0) AS tickets_sold,
        COALESCE(SUM(oi.subtotal), 0) AS total_sales
    FROM events e
    LEFT JOIN order_items oi ON oi.event_id = e.id
    LEFT JOIN orders o ON o.id = oi.order_id AND o.status = 'paid'
    WHERE e.id = p_event_id
    GROUP BY e.id, e.title, e.status;
END$$

DELIMITER ;
