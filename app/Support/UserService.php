<?php

namespace App\Support;

/**
 * Serviços operacionais que o usuário pode executar (além do menu/grupo de navegação).
 * Novos serviços: adicionar constante, rótulo em labels() e uso nas telas/rotas.
 */
final class UserService
{
    /** Checklists na aba Câmeras (iniciar, em andamento, fluxo do checklist, apagar histórico). */
    public const CHECKLIST_CAMERAS = 'checklist_cameras';

    /**
     * @return array<string, string> chave => rótulo
     */
    public static function labels(): array
    {
        return [
            self::CHECKLIST_CAMERAS => 'Checklists de câmeras (iniciar, acompanhar, preencher e apagar histórico)',
        ];
    }

    /**
     * @return list<string>
     */
    public static function allKeys(): array
    {
        return array_keys(self::labels());
    }

    public static function isValidKey(string $key): bool
    {
        return array_key_exists($key, self::labels());
    }
}
