<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $commission = Setting::get('commission_percentage', config('logic-ticket.commission_percentage', 5));
        return view('admin.settings.index', compact('commission'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commission_percentage' => 'required|numeric|min:0|max:100',
        ]);
        Setting::set('commission_percentage', $validated['commission_percentage']);
        return redirect()->route('admin.settings.index')->with('success', 'Configuración guardada.');
    }
}
