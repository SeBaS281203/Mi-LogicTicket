# Guía de la Plataforma LogicTicket

## Arquitectura general

LogicTicket es una plataforma de venta de entradas para eventos con tres roles principales y paneles diferenciados.

### Roles y permisos

| Rol | Descripción | Panel principal | Acceso |
|-----|-------------|-----------------|--------|
| **admin** | Administrador general del sistema | `/admin` | Eventos, usuarios, categorías, ciudades, banners, tendencias, órdenes, reportes, libro de reclamaciones |
| **organizer** | Organizador de eventos | `/organizer` | Sus eventos, ventas, compradores, reportes. También puede comprar tickets (acceso a `/cuenta`) |
| **client** | Comprador de entradas | `/cuenta` | Mi cuenta, tickets, órdenes, perfil |

**Redirección automática:**
- Después de login/registro, el usuario es redirigido según su rol:
  - Admin → `/admin`
  - Organizer → `/organizer`
  - Client → `/cuenta`

### Rutas modulares

Las rutas están organizadas en archivos separados:

- `routes/public.php` – Página principal, eventos públicos, auth, carrito, checkout, libro de reclamaciones
- `routes/cuenta.php` – Panel Mi Cuenta (clientes y usuarios autenticados)
- `routes/admin.php` – Panel de administración (solo admin)
- `routes/organizer.php` – Panel de organizadores (solo organizer/admin)

### Middleware

- `auth` – Usuario autenticado
- `admin` – Solo administradores
- `organizer` – Solo organizadores (admins tienen acceso implícito)
- `client` – Solo clientes (actualmente no usado en rutas; cuenta permite todos los autenticados)

### Servicios clave

- **AuthRedirectService** – Redirección post-login/registro según rol
- **CartSummaryService** – Lógica del carrito
- **ImageOptimizationService** – Optimización de imágenes en subida
- **TicketPdfService** – Generación de PDFs de tickets

### Estructura de carpetas

```
app/
├── Enums/
│   └── UserRole.php          # Enum de roles con helpers
├── Http/
│   ├── Controllers/
│   │   ├── Admin/            # Controladores del panel admin
│   │   ├── Cuenta/           # Panel Mi Cuenta
│   │   ├── Organizer/        # Panel organizadores
│   │   └── Auth/
│   ├── Middleware/
│   │   ├── EnsureUserIsAdmin.php
│   │   ├── EnsureUserIsOrganizer.php
│   │   └── EnsureUserIsClient.php
│   └── Requests/
├── Models/
├── Services/
│   ├── AuthRedirectService.php
│   ├── CartSummaryService.php
│   ├── ImageOptimizationService.php
│   └── TicketPdfService.php
└── Providers/
```

### Buenas prácticas aplicadas

1. **Separación por dominio**: Controllers agrupados por panel (Admin, Organizer, Cuenta)
2. **Rutas modulares**: Un archivo por contexto
3. **Enum de roles**: Tipo seguro y métodos centralizados
4. **Servicios inyectables**: Lógica de negocio en clases dedicadas
5. **Form Requests**: Validación en clases propias
6. **Eager loading y caché**: Optimización de consultas

### Despliegue

Ver `DEPLOYMENT.md` para instrucciones de producción.
