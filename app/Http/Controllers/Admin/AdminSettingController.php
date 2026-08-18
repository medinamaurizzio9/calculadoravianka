<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

        $validator = Validator::make($request->all(), [
            'settings.' . SiteSetting::AFFILIATE_URL => ['nullable', 'url', 'max:500'],
            'settings.hero_primary_url' => ['nullable', 'string', 'max:500', 'regex:/^(#|\/|https?:\/\/)/i'],
            'settings.hero_secondary_url' => ['nullable', 'string', 'max:500', 'regex:/^(#|\/|https?:\/\/)/i'],
            'site_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'settings.' . SiteSetting::AFFILIATE_URL . '.url' => 'La URL de afiliación debe ser una dirección válida.',
            'settings.' . SiteSetting::AFFILIATE_URL . '.max' => 'La URL de afiliación no debe superar 500 caracteres.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

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

        $this->storeImage($request, 'site_logo');
        $this->storeImage($request, 'hero_image');

        return redirect()
            ->route('admin.settings.edit')
            ->with('status', 'Configuraciones actualizadas correctamente.');
    }

    private function storeImage(Request $request, string $key): void
    {
        if (! $request->hasFile($key)) {
            return;
        }

        $setting = SiteSetting::query()->where('key', $key)->first();

        if (! $setting) {
            return;
        }

        $oldPath = $setting->value;
        $newPath = $request->file($key)->store('site', 'public');
        $setting->update(['value' => $newPath]);

        if (is_string($oldPath) && str_starts_with($oldPath, 'site/')) {
            Storage::disk('public')->delete($oldPath);
        }
    }
}
