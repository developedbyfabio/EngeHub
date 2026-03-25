<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'color',
        'order'
    ];

    public function cards()
    {
        return $this->hasMany(Card::class)->orderBy('name');
    }
} 