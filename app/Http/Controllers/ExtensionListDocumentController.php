<?php

namespace App\Http\Controllers;

use App\Models\ExtensionListDocument;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ExtensionListDocumentController extends Controller
{
    public function show(): Response
    {
        $document = ExtensionListDocument::current();
        if (! $document) {
            abort(404, 'Lista de ramais não cadastrada.');
        }

        $disk = Storage::disk($document->disk);
        if (! $disk->exists($document->path)) {
            abort(404, 'Arquivo da lista não encontrado.');
        }

        return response()->file($disk->path($document->path), [
            'Content-Type' => 'image/svg+xml; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="lista-ramais.svg"',
            'Cache-Control' => 'private, must-revalidate, max-age=0',
        ]);
    }
}
