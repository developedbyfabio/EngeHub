<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @deprecated Legado (tabela seats). O mapa usa {@see devices()}.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullPathAttribute()
    {
        return public_path($this->file_path . $this->file_name);
    }

    public function getFileUrlAttribute()
    {
        return asset($this->file_path . $this->file_name);
    }

    public function fileExists()
    {
        return file_exists($this->full_path);
    }

    public function getSvgContent()
    {
        if ($this->fileExists()) {
            return file_get_contents($this->full_path);
        }

        return null;
    }

    /**
     * Lê SVG, detecta rótulos (Device::parseSvgLabel) em text/tspan/foreignObject.
     *
     * Para cada dispositivo no SVG: {@see firstOrCreate} por (network_map_id, type, code).
     * Registros já existentes não são alterados (metadata, observações, setor, etc. mantidos).
     * Dispositivos que deixaram de aparecer no SVG não são apagados.
     *
     * @return int Quantidade de dispositivos únicos encontrados no SVG (incluindo os que já existiam)
     */
    public function syncDevicesFromSvg(): int
    {
        $content = $this->getSvgContent();
        if (! $content) {
            return 0;
        }

        libxml_use_internal_errors(true);
        $dom = new \DOMDocument;
        $dom->loadXML($content);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');

        $uniqueByFull = [];

        foreach ($xpath->query('//*[local-name()="text" or local-name()="tspan"]') as $node) {
            $text = trim($node->textContent ?? '');
            if ($text === '') {
                continue;
            }
            $parsed = Device::parseSvgLabel($text);
            if ($parsed) {
                $uniqueByFull[$parsed['full_code']] = $parsed;
            }
        }

        foreach ($xpath->query('//*[local-name()="foreignObject"]//*') as $node) {
            if (! $node instanceof \DOMElement) {
                continue;
            }
            if ($node->getElementsByTagName('*')->length > 0) {
                continue;
            }
            $text = trim($node->textContent ?? '');
            if ($text === '') {
                continue;
            }
            $parsed = Device::parseSvgLabel($text);
            if ($parsed) {
                $uniqueByFull[$parsed['full_code']] = $parsed;
            }
        }

        /** Dispositivos únicos neste SVG (chave no banco: network_map_id + type + code). Não usamos isto para apagar linhas antigas. */
        $foundDevices = array_values($uniqueByFull);

        foreach ($foundDevices as $parsed) {
            $this->devices()->firstOrCreate(
                [
                    'network_map_id' => $this->id,
                    'type' => $parsed['type'],
                    'code' => $parsed['code'],
                ],
                [
                    'full_code' => $parsed['full_code'],
                ]
            );
        }

        return count($foundDevices);
    }
}
