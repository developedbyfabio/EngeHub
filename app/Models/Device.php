<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Device extends Model
{
    public const TYPES = ['SEAT', 'PRINTER', 'TV', 'SCAN', 'PHONE', 'AP', 'OUTLET'];

    /** Ordem de exibição no painel de filtros e nos resumos do mapa (Filiais). */
    public const MAP_LAYER_TYPE_ORDER = ['OUTLET', 'SEAT', 'PRINTER', 'SCAN', 'TV', 'PHONE', 'AP'];

    public const TYPE_REGEX = '/^(SEAT|PRINTER|TV|SCAN|PHONE|AP)-[A-Z0-9\-]+$/';

    /** Rótulo simples no SVG para ponto de tomada (ex.: A01, B02). */
    public const OUTLET_CODE_REGEX = '/^[A-Z]\d{2}$/';

    protected $fillable = [
        'network_map_id',
        'type',
        'code',
        'full_code',
        'setor',
        'observacoes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function networkMap(): BelongsTo
    {
        return $this->belongsTo(NetworkMap::class);
    }

    /** Rótulo em português (igual ao painel de camadas do SVG). */
    public static function mapLayerTypeLabel(string $type): string
    {
        return match ($type) {
            'OUTLET' => 'Pontos (tomadas)',
            'SEAT' => 'Mesas',
            'PRINTER' => 'Impressoras',
            'SCAN' => 'Scanners',
            'TV' => 'TVs',
            'PHONE' => 'Telefones',
            'AP' => 'Access points',
            default => $type,
        };
    }

    public static function parseFullCode(string $full): ?array
    {
        $full = trim($full);
        if ($full === '' || ! preg_match(self::TYPE_REGEX, $full)) {
            return null;
        }
        $parts = explode('-', $full);
        $type = array_shift($parts);
        $code = implode('-', $parts);

        return [
            'type' => $type,
            'code' => $code,
            'full_code' => $full,
        ];
    }

    /**
     * Interpreta texto do SVG: padrão TIPO-… ou ponto de rede {@see OUTLET_CODE_REGEX}.
     *
     * @return array{type: string, code: string, full_code: string}|null
     */
    public static function parseSvgLabel(string $text): ?array
    {
        $parsed = self::parseFullCode($text);
        if ($parsed !== null) {
            return $parsed;
        }

        $text = trim($text);
        if ($text === '' || ! preg_match(self::OUTLET_CODE_REGEX, $text)) {
            return null;
        }

        return [
            'type' => 'OUTLET',
            'code' => $text,
            'full_code' => 'OUTLET-'.$text,
        ];
    }

    /**
     * Formato único para admin, URL secreta e integrações (detalhes por tipo).
     *
     * @return array<string, mixed>
     */
    public function toApiArray(): array
    {
        $m = $this->metadata ?? [];

        $base = [
            'id' => $this->id,
            'type' => $this->type,
            'code' => $this->code,
            'full_code' => $this->full_code,
            'setor' => $this->setor,
            'observacoes' => $this->observacoes,
            'metadata' => $m,
        ];

        $base['details'] = match ($this->type) {
            'SEAT' => [
                'collaborator_name' => $m['collaborator_name'] ?? null,
                'computer_name' => $m['computer_name'] ?? null,
                'computer_kind' => $m['computer_kind'] ?? null,
                'computer_ip' => $m['computer_ip'] ?? null,
                'workstation_photo' => $m['workstation_photo'] ?? null,
                'workstation_photo_url' => ! empty($m['workstation_photo'])
                    ? Storage::disk('public')->url($m['workstation_photo'])
                    : null,
            ],
            'PRINTER' => [
                'ip' => $m['ip'] ?? null,
                'model' => $m['model'] ?? null,
                'device_photo' => $m['device_photo'] ?? null,
                'device_photo_url' => ! empty($m['device_photo'])
                    ? Storage::disk('public')->url($m['device_photo'])
                    : null,
            ],
            'TV' => [
                'location' => $m['location'] ?? null,
                'device_photo' => $m['device_photo'] ?? null,
                'device_photo_url' => ! empty($m['device_photo'])
                    ? Storage::disk('public')->url($m['device_photo'])
                    : null,
            ],
            'SCAN' => [
                'sector' => $m['sector'] ?? $this->setor,
                'device_photo' => $m['device_photo'] ?? null,
                'device_photo_url' => ! empty($m['device_photo'])
                    ? Storage::disk('public')->url($m['device_photo'])
                    : null,
            ],
            'PHONE' => [
                'extension' => $m['extension'] ?? null,
                'device_photo' => $m['device_photo'] ?? null,
                'device_photo_url' => ! empty($m['device_photo'])
                    ? Storage::disk('public')->url($m['device_photo'])
                    : null,
            ],
            'AP' => [
                'ssid' => $m['ssid'] ?? null,
                'location' => $m['location'] ?? null,
                'device_photo' => $m['device_photo'] ?? null,
                'device_photo_url' => ! empty($m['device_photo'])
                    ? Storage::disk('public')->url($m['device_photo'])
                    : null,
            ],
            'OUTLET' => [
                'outlet_type' => $m['outlet_type'] ?? null,
                'device_photo' => $m['device_photo'] ?? null,
                'device_photo_url' => ! empty($m['device_photo'])
                    ? Storage::disk('public')->url($m['device_photo'])
                    : null,
            ],
            default => [],
        };

        return $base;
    }
}
