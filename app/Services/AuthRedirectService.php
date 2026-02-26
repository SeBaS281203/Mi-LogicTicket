<?php

namespace App\Services;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class AuthRedirectService
{
    public function redirectAfterLogin(Authenticatable $user, Redirector $redirector): RedirectResponse
    {
        $intended = session()->pull('url.intended');

        if ($intended && $this->isAllowedUrlForUser($intended, $user)) {
            return $redirector->to($intended);
        }

        if ($user->isAdmin()) {
            return $redirector->route('admin.dashboard');
        }

        if ($user->isOrganizer()) {
            return $redirector->route('organizer.dashboard');
        }

        return $redirector->route('cuenta.dashboard');
    }

    public function redirectAfterRegister(Authenticatable $user, Redirector $redirector): RedirectResponse
    {
        $intended = session()->pull('url.intended');
        if ($intended && $this->isAllowedUrlForUser($intended, $user)) {
            return $redirector->to($intended);
        }

        if ($user->isAdmin()) {
            return $redirector->route('admin.dashboard');
        }

        if ($user->isOrganizer()) {
            return $redirector->route('organizer.dashboard');
        }

        return $redirector->route('cuenta.dashboard');
    }

    public function defaultUrlForUser(Authenticatable $user): string
    {
        if ($user->isAdmin()) {
            return route('admin.dashboard');
        }
        if ($user->isOrganizer()) {
            return route('organizer.dashboard');
        }
        return route('cuenta.dashboard');
    }

    private function isAllowedUrlForUser(?string $url, Authenticatable $user): bool
    {
        if (! $url) {
            return false;
        }

        try {
            $host = parse_url($url, PHP_URL_HOST);
            if ($host && $host !== parse_url(config('app.url'), PHP_URL_HOST)) {
                return false;
            }

            $path = parse_url($url, PHP_URL_PATH) ?? '';
            if (str_starts_with($path, '/admin')) {
                return method_exists($user, 'isAdmin') && $user->isAdmin();
            }

            if (str_starts_with($path, '/organizer')) {
                return ($user->role ?? null) === 'organizer';
            }

            if (str_starts_with($path, '/cuenta')) {
                return true;
            }

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
