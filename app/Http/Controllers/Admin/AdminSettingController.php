<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::orderBy('group')->orderBy('id')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $values = $request->input('settings', []);

        if (blank($values['whatsapp_number'] ?? null)) {
            return back()
                ->withErrors(['settings.whatsapp_number' => 'El número de WhatsApp es obligatorio.'])
                ->withInput();
        }

        SiteSetting::query()->get()->each(function (SiteSetting $setting) use ($values): void {
            if (array_key_exists($setting->key, $values)) {
                $setting->update(['value' => $values[$setting->key]]);
            }
        });

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', 'Configuraciones actualizadas correctamente.');
    }
}
