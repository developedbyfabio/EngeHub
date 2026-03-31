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
        'file_name_floor2',
        'has_two_floors',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_two_floors' => 'boolean',
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

    public function getFullPathFloor2Attribute(): string
    {
        return public_path($this->file_path.($this->file_name_floor2 ?? ''));
    }

    public function getFileUrlAttribute()
    {
        return asset($this->file_path . $this->file_name);
    }

    public function fileExists()
    {
        return file_exists($this->full_path);
    }

    public function fileExistsFloor2(): bool
    {
        return (bool) ($this->file_name_floor2 && file_exists($this->full_path_floor2));
    }

    public function getSvgContent()
    {
        if ($this->fileExists()) {
            return file_get_contents($this->full_path);
        }

        return null;
    }

    public function getSvgContentForFloor(int $floor): ?string
    {
        $floor = $floor === 2 ? 2 : 1;

        if ($floor === 1) {
            return $this->getSvgContent();
        }

        if (! $this->has_two_floors || ! $this->fileExistsFloor2()) {
            return null;
        }

        return file_get_contents($this->full_path_floor2);
    }

    /**
     * @return array<int, string> Conteúdo bruto de cada SVG usado na sincronização (1º andar e, se houver, 2º).
     */
    protected function svgContentsForSync(): array
    {
        $out = [];
        if ($this->fileExists()) {
            $out[] = (string) file_get_contents($this->full_path);
        }
        if ($this->has_two_floors && $this->fileExistsFloor2()) {
            $out[] = (string) file_get_contents($this->full_path_floor2);
        }

        return $out;
    }

    /**
     * @return array<string, array{type: string, code: string, full_code: string}>
     */
    protected function parseDevicesFromSvgContent(string $content): array
    {
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

        return $uniqueByFull;
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
        $merged = [];
        foreach ($this->svgContentsForSync() as $content) {
            if ($content === '') {
                continue;
            }
            foreach ($this->parseDevicesFromSvgContent($content) as $fullCode => $parsed) {
                $merged[$fullCode] = $parsed;
            }
        }

        foreach ($merged as $parsed) {
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

        return count($merged);
    }
}
