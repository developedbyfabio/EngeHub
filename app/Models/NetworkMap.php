<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkMap extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_name',
        'file_path',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento: um mapa tem muitas mesas
     */
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Scope: apenas mapas ativos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Accessor: caminho completo do arquivo SVG
     */
    public function getFullPathAttribute()
    {
        return public_path($this->file_path . $this->file_name);
    }

    /**
     * Accessor: URL do arquivo SVG
     */
    public function getFileUrlAttribute()
    {
        return asset($this->file_path . $this->file_name);
    }

    /**
     * Verifica se o arquivo SVG existe fisicamente
     */
    public function fileExists()
    {
        return file_exists($this->full_path);
    }

    /**
     * Carrega o conteúdo do SVG
     */
    public function getSvgContent()
    {
        if ($this->fileExists()) {
            return file_get_contents($this->full_path);
        }
        return null;
    }

    /**
     * Varredura: extrai códigos de mesas do SVG (text/tspan que batem com o padrão)
     * e sincroniza com a tabela seats. Cria mesas que não existem; não remove as que existem.
     * Padrão: uma ou mais letras maiúsculas + exatamente 2 dígitos (ex: A01, D01, RH04, ADM02).
     */
    public function syncSeatsFromSvg()
    {
        $content = $this->getSvgContent();
        if (!$content) {
            return 0;
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadXML($content);
        libxml_clear_errors();

        $codes = [];
        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');
        $nodes = $xpath->query('//*[local-name()="text" or local-name()="tspan"]');

        $regex = '/^[A-Z]+\d{2}$/';
        foreach ($nodes as $node) {
            $text = trim($node->textContent ?? '');
            if ($text !== '' && preg_match($regex, $text)) {
                $codes[$text] = true;
            }
        }

        $count = 0;
        foreach (array_keys($codes) as $code) {
            $this->seats()->firstOrCreate(
                ['code' => $code],
                ['code' => $code]
            );
            $count++;
        }

        return $count;
    }
}
