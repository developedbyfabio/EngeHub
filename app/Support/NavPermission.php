<?php

namespace App\Support;

/**
 * Chaves de visibilidade da navegação e mapeamento de rotas admin.*.
 */
final class NavPermission
{
    public const HOME = 'home';

    public const SERVERS = 'servers';

    public const CAMERAS = 'cameras';

    public const FILIAIS = 'filiais';

    public const ADMIN_CAMERAS = 'admin_cameras';

    public const ADMIN_CARDS = 'admin_cards';

    public const ADMIN_FORMS = 'admin_forms';

    public const ADMIN_EXTENSION_LIST = 'admin_extension_list';

    public const ADMIN_NETWORK_MAPS = 'admin_network_maps';

    public const ADMIN_SERVERS = 'admin_servers';

    public const ADMIN_SECTORS = 'admin_sectors';

    public const ADMIN_SYSTEM_USERS = 'admin_system_users';

    public const ADMIN_BRANCHES = 'admin_branches';

    /**
     * @return array<string, string> key => rótulo em português (checkboxes)
     */
    public static function labels(): array
    {
        return [
            self::HOME => 'Início',
            self::SERVERS => 'Servidores',
            self::CAMERAS => 'Câmeras',
            self::FILIAIS => 'Filiais',
            self::ADMIN_CAMERAS => 'Gerenciar · Câmeras',
            self::ADMIN_CARDS => 'Gerenciar · Cards (abas, categorias, logins, data centers)',
            self::ADMIN_FORMS => 'Gerenciar · Formulários e Checklists',
            self::ADMIN_EXTENSION_LIST => 'Gerenciar · Lista de Ramais',
            self::ADMIN_NETWORK_MAPS => 'Gerenciar · Mapas de Rede e Mesas',
            self::ADMIN_SERVERS => 'Gerenciar · Servidores e grupos',
            self::ADMIN_SECTORS => 'Gerenciar · Setores',
            self::ADMIN_SYSTEM_USERS => 'Gerenciar · Grupos e Usuários',
            self::ADMIN_BRANCHES => 'Gerenciar · Filiais (cadastro)',
        ];
    }

    /**
     * @return list<string>
     */
    public static function allKeys(): array
    {
        return array_keys(self::labels());
    }

    /**
     * @return list<string>
     */
    public static function adminKeys(): array
    {
        return [
            self::ADMIN_CAMERAS,
            self::ADMIN_CARDS,
            self::ADMIN_FORMS,
            self::ADMIN_EXTENSION_LIST,
            self::ADMIN_NETWORK_MAPS,
            self::ADMIN_SERVERS,
            self::ADMIN_SECTORS,
            self::ADMIN_SYSTEM_USERS,
            self::ADMIN_BRANCHES,
        ];
    }

    /**
     * Todas as chaves com valor true (JSON de permissões “liberado”).
     *
     * @return array<string, bool>
     */
    public static function fullPermissionMap(): array
    {
        $map = [];
        foreach (self::allKeys() as $key) {
            $map[$key] = true;
        }

        return $map;
    }

    /**
     * Mapeia route name (admin.*) para chave de permissão.
     * null = exige grupo com full_access ou legacy hasFullAccess().
     */
    public static function adminRouteToNavKey(?string $routeName): ?string
    {
        if (! $routeName || ! str_starts_with($routeName, 'admin.')) {
            return null;
        }

        $without = substr($routeName, strlen('admin.'));
        $first = explode('.', $without)[0];

        return match ($first) {
            'cameras' => self::ADMIN_CAMERAS,
            'cards',
            'tabs',
            'categories',
            'datacenters',
            'system-logins' => self::ADMIN_CARDS,
            'forms' => self::ADMIN_FORMS,
            'branches' => self::ADMIN_BRANCHES,
            'extension-list' => self::ADMIN_EXTENSION_LIST,
            'network-maps',
            'seats' => self::ADMIN_NETWORK_MAPS,
            'servers',
            'server-groups' => self::ADMIN_SERVERS,
            'sectors' => self::ADMIN_SECTORS,
            'system-users',
            'user-groups' => self::ADMIN_SYSTEM_USERS,
            default => null,
        };
    }
}
