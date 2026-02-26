<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Organizer = 'organizer';
    case Client = 'client';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrador',
            self::Organizer => 'Organizador',
            self::Client => 'Cliente',
        };
    }

    public function dashboardRoute(): string
    {
        return match ($this) {
            self::Admin => 'admin.dashboard',
            self::Organizer => 'organizer.dashboard',
            self::Client => 'cuenta.dashboard',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::Admin;
    }

    public function isOrganizer(): bool
    {
        return $this === self::Organizer;
    }

    public function isClient(): bool
    {
        return $this === self::Client;
    }

    public static function fromUser(?\Illuminate\Contracts\Auth\Authenticatable $user): ?self
    {
        return $user?->role ? self::tryFrom($user->role) : null;
    }
}
