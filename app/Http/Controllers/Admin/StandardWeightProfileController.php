<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\StandardWeightProfile;
use Illuminate\Http\Request;

class StandardWeightProfileController extends Controller
{
    public function store(Request $request, Form $form)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'options' => 'required|array',
            'options.*.option_text' => 'required|string|max:255',
            'options.*.weight' => 'required|integer',
        ]);

        $profile = $form->standardWeightProfiles()->create(['name' => $validated['name']]);

        foreach (array_values($validated['options']) as $order => $opt) {
            $profile->options()->create([
                'option_text' => $opt['option_text'],
                'weight' => $opt['weight'],
                'order' => $order,
            ]);
        }

        if ($request->wantsJson()) {
            $form->load('standardWeightProfiles.options');
            return response()->json([
                'success' => true,
                'message' => 'Perfil de pesos padrão criado.',
                'html' => view('admin.forms.partials.modal-pesos-body', compact('form'))->render(),
            ]);
        }

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Perfil de pesos padrão criado.');
    }

    public function destroy(Request $request, Form $form, StandardWeightProfile $profile)
    {
        if ($profile->form_id !== $form->id) {
            abort(404);
        }

        $profile->delete();

        if ($request->wantsJson()) {
            $form->load('standardWeightProfiles.options');
            return response()->json([
                'success' => true,
                'message' => 'Perfil excluído.',
                'html' => view('admin.forms.partials.modal-pesos-body', compact('form'))->render(),
            ]);
        }

        return redirect()
            ->route('admin.forms.show', $form)
            ->with('success', 'Perfil excluído.');
    }
}
