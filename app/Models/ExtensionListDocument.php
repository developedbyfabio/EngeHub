<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtensionListDocument extends Model
{
    protected $table = 'extension_list_documents';

    protected $fillable = [
        'disk',
        'path',
        'original_filename',
    ];

    public static function current(): ?self
    {
        return static::query()->latest('id')->first();
    }
}
