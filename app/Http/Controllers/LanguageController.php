<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'locale' => ['required', 'string', 'in:'.implode(',', config('app.available_locales'))],
        ]);

        $request->session()->put('locale', $validated['locale']);
        App::setLocale($validated['locale']);

        return redirect()->back();
    }
}
