# Sistema de diseño LogicTicket

Sistema unificado para velocidad, SEO, responsive y código reutilizable.

## Tokens

| Token | Valor | Uso |
|-------|--------|-----|
| **Verde corporativo** | `#00a650` | Botones primarios, enlaces, acentos |
| **Verde hover** | `#009345` | Estados hover |
| **Fondo suave** | `#f5f7fa` | Fondos de página (surface) |
| **Radius card** | 16px | Cards, modales |
| **Radius card-lg** | 20px | Hero, bloques grandes |
| **Botones** | 12px (rounded-xl) | Todos los botones |
| **Transición global** | 0.3s | Enlaces, botones, hovers |

## Tipografía

- **Fuente:** Inter (400, 500, 600, 700).
- Carga con `display=swap` para no bloquear render.
- Clase base: `font-sans`.

## Clases reutilizables

### Botones

- **Primario:** `btn-primary` o componente `<x-button>Ver más</x-button>`.
- **Secundario:** `<x-button variant="secondary">Cancelar</x-button>`.
- **Como enlace:** `<x-button href="{{ route('events.index') }}">Ver eventos</x-button>`.
- Tamaños: `size="sm" | "md" | "lg"`.

### Cards

- **Estándar:** `card` (blanco, radius 16px, sombra suave).
- **Grande:** `card card-lg` (radius 20px).

### Transiciones

- Enlaces y botones tienen transición 0.3s por defecto (base).
- Clase `transition-global` para otros elementos.

## Tailwind (Vite)

En `resources/css/app.css` con `@theme`:

- `bg-brand`, `bg-brand-hover`, `bg-surface`
- `rounded-card`, `rounded-card-lg`
- `duration-global` (300ms)

## Fallback (CDN)

Si no hay build, el layout usa Tailwind CDN con los mismos tokens en `theme.extend` y variables en `[data-ds]`.

## SEO y rendimiento

- Viewport y theme-color en `<head>`.
- Canonical y meta description por página.
- Fuente Inter con `display=swap`.
- Preconnect a fonts.bunny.net.

## Responsive

- Container: `max-w-7xl` (1280px).
- Espaciado: `px-4 sm:px-6 lg:px-8`.
- Breakpoints estándar Tailwind (sm, md, lg, xl).
