<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtensionListDocument;
use App\Services\ExtensionListSvgSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ExtensionListController extends Controller
{
    public function index()
    {
        $document = ExtensionListDocument::current();

        return view('admin.extension-list.index', compact('document'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'svg' => ['required', 'file', 'max:51200'],
        ]);

        $file = $validated['svg'];
        $ext = strtolower((string) ($file->getClientOriginalExtension() ?? ''));
        if ($ext !== 'svg') {
            return back()->withErrors(['svg' => 'Envie um arquivo com extensão .svg.'])->withInput();
        }

        $contents = @file_get_contents($file->getRealPath());
        if ($contents === false || $contents === '') {
            return back()->withErrors(['svg' => 'Não foi possível ler o arquivo enviado.'])->withInput();
        }

        try {
            $sanitized = ExtensionListSvgSanitizer::sanitize($contents);
        } catch (InvalidArgumentException $e) {
            return back()->withErrors(['svg' => $e->getMessage()])->withInput();
        }

        $old = ExtensionListDocument::current();
        if ($old) {
            if (Storage::disk($old->disk)->exists($old->path)) {
                Storage::disk($old->disk)->delete($old->path);
            }
            $old->delete();
        }

        $path = 'extension-lists/'.Str::uuid()->toString().'.svg';
        Storage::disk('public')->put($path, $sanitized);

        ExtensionListDocument::create([
            'disk' => 'public',
            'path' => $path,
            'original_filename' => $file->getClientOriginalName(),
        ]);

        return redirect()->route('admin.extension-list.index')
            ->with('success', 'Lista de ramais (SVG) atualizada com sucesso.');
    }
}
