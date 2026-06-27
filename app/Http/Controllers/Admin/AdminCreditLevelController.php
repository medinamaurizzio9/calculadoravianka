<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminCreditLevelController extends Controller
{
    public function index(): View
    {
        return view('admin.credit-levels.index', [
            'creditLevels' => CreditLevel::orderBy('sort_order')->get(),
        ]);
    }

    public function edit(CreditLevel $creditLevel): View
    {
        return view('admin.credit-levels.edit', [
            'creditLevel' => $creditLevel,
            'termsText' => implode(',', $creditLevel->available_terms ?? []),
        ]);
    }

    public function update(Request $request, CreditLevel $creditLevel): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer', 'min:1'],
            'affiliations' => ['required', 'integer', 'min:0'],
            'affiliation_cost' => ['required', 'numeric', 'min:0'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'min:0'],
            'annual_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'available_terms' => ['nullable', 'string'],
            'authorized_use' => ['nullable', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $validated['available_terms'] = collect(explode(',', $validated['available_terms'] ?? ''))
            ->map(fn (string $term) => trim($term))
            ->filter(fn (string $term) => $term !== '')
            ->map(fn (string $term) => (int) $term)
            ->filter(fn (int $term) => $term > 0)
            ->values()
            ->all();
        $validated['is_housing'] = $request->boolean('is_housing');
        $validated['evaluation_required'] = $request->boolean('evaluation_required');
        $validated['is_active'] = $request->boolean('is_active');

        $creditLevel->update($validated);

        return redirect()
            ->route('admin.credit-levels.index')
            ->with('status', 'Crédito actualizado correctamente.');
    }
}
