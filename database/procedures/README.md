# Procedimientos almacenados – LogicTicket (MySQL)

La base de datos **logicticket** puede usar estos procedimientos para estadísticas y operaciones de administración.

## Cómo instalar en phpMyAdmin

1. Abre phpMyAdmin y selecciona la base de datos **logicticket**.
2. Asegúrate de que las tablas ya existan (ejecuta antes `php artisan migrate` con `DB_CONNECTION=mysql` y `DB_DATABASE=logicticket`).
3. Ve a la pestaña **SQL**.
4. Copia y pega todo el contenido del archivo `logicticket_stored_procedures.sql`.
5. Haz clic en **Continuar** para ejecutar.

También puedes usar la pestaña **Rutinas** para ver y editar los procedimientos después de crearlos.

## Procedimientos incluidos

| Procedimiento | Descripción |
|--------------|-------------|
| `sp_dashboard_stats()` | Devuelve una fila con totales: usuarios, organizadores, eventos, pendientes de aprobación, activos, órdenes pagadas e ingresos. |
| `sp_revenue_by_month()` | Ingresos por mes (últimos 12 meses) para gráficos. |
| `sp_approve_event(p_event_id)` | Aprueba un evento (cambia `pending_approval` → `published`). |
| `sp_reject_event(p_event_id)` | Rechaza un evento (vuelve a `draft`). |
| `sp_orders_report(p_date_from, p_date_to)` | Listado de órdenes entre dos fechas. |
| `sp_event_sales_summary(p_event_id)` | Resumen de ventas (órdenes, entradas vendidas, total) de un evento. |

## Ejemplos de uso en SQL

```sql
-- Estadísticas del dashboard
CALL sp_dashboard_stats();

-- Ingresos por mes
CALL sp_revenue_by_month();

-- Aprobar evento con id 5
CALL sp_approve_event(5);

-- Rechazar evento con id 3
CALL sp_reject_event(3);

-- Reporte de órdenes de febrero 2025
CALL sp_orders_report('2025-02-01', '2025-02-28');

-- Resumen de ventas del evento 1
CALL sp_event_sales_summary(1);
```

## Migración Laravel

La migración `2025_02_21_000001_create_stored_procedures` crea automáticamente los cuatro primeros procedimientos (los de una sola sentencia) cuando usas MySQL. Para tener también `sp_orders_report` y `sp_event_sales_summary`, ejecuta el archivo `.sql` en phpMyAdmin como se indica arriba.
