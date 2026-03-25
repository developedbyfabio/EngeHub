<?php

/*
|--------------------------------------------------------------------------
| Load The Cached Routes
|--------------------------------------------------------------------------
|
| Here we will decode and unserialize the RouteCollection instance that
| holds all of the route information for an application. This allows
| us to instantaneously load the entire route map into the router.
|
*/

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sanctum.csrf-cookie',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5YUdPETytuLY3eJF',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/seats/occupied/list' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::annYhWbdyXTU3ttl',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/servers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'servers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/test-toast' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'test-toast',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/test-permissions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'test-permissions',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/test-modal-click' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'test-modal-click',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/favorites' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'favorites.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/tabs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.tabs.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.tabs.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/tabs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.tabs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cards' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cards/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/system-logins' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/system-logins/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/categories' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/categories/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/categories-get-all' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.get-all',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/datacenters' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.datacenters.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.datacenters.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/datacenters/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.datacenters.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/servers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.servers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.servers.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/servers/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.servers.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/server-groups' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.server-groups.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.server-groups.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/server-groups/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.server-groups.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/system-users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/system-users/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/sectors' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/sectors/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/network-maps' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/network-maps/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/seats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.seats.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.seats.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/seats/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.seats.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cameras' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cameras/dvrs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cameras/dvrs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cameras/cameras/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.cameras.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cameras/cameras' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.cameras.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/forms' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/forms/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/branches' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.branches.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.branches.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/debug/auth' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cameras' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cameras/checklists' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/cameras/historico/apagar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.historico.apagar',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::b0h094QkCVVKVcpp',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aMcn97a3K7AXdWpH',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/verify-email' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.notice',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/email/verification-notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/confirm-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.confirm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4RG5bYOwpWZKwSPx',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/a(?|pi/s(?|ystem\\-users/([^/]++)/logins(*:47)|eats/([^/]++)(*:67))|dmin/(?|tabs/([^/]++)(?|(*:99)|/edit(*:111)|(*:119))|ca(?|rds/([^/]++)(?|(*:148)|/(?|edit(*:164)|logins(*:178)|check\\-status(*:199)|filtered\\-logins(*:223))|(*:232))|tegories/([^/]++)(?|(*:261)|/edit(*:274)|(*:282))|meras/(?|dvrs/([^/]++)(?|/(?|edit(*:324)|toggle\\-status(*:346)|reorder\\-cameras(*:370)|import\\-cameras(*:393))|(*:402))|cameras/([^/]++)(?|/(?|edit(*:438)|toggle\\-status(*:460))|(*:469))))|s(?|ystem\\-(?|logins/([^/]++)(?|(*:512)|/(?|edit(*:528)|toggle\\-password(*:552)|permissions(?|(*:574)))|(*:584))|users/([^/]++)(?|(*:610)|/(?|edit(*:626)|permissions(?|(*:648))|secret\\-url(?|(*:671)|/(?|regenerate(*:693)|toggle(*:707)|expiration(*:725))))|(*:736)))|e(?|rver(?|s/([^/]++)(?|(*:770)|/(?|edit(*:786)|check\\-status(*:807))|(*:816))|\\-groups/([^/]++)(?|(*:845)|/edit(*:858)|(*:866)))|ctors/([^/]++)(?|(*:893)|/(?|edit(*:909)|cards(?|(*:925))|secret\\-url(?|(*:948)|/(?|update(*:966)|regenerate(*:984)|toggle(*:998)|expiration(*:1016))))|(*:1028))|ats/([^/]++)(?|(*:1053)|/edit(*:1067)|(*:1076))))|datacenters/([^/]++)(?|(*:1111)|/edit(*:1125)|(*:1134))|network\\-maps/([^/]++)(?|(*:1169)|/(?|edit(*:1186)|toggle\\-status(*:1209)|seats/([^/]++)(?|(*:1235))|resync\\-seats(*:1258))|(*:1268))|forms/([^/]++)(?|(*:1295)|/(?|e(?|dit(*:1315)|xport\\-(?|csv(*:1337)|pdf(*:1349)))|questions(?|(*:1372)|/([^/]++)(?|(*:1393)|/(?|options(?|(*:1416)|/(?|reorder(*:1436)|([^/]++)(?|(*:1456))))|apply\\-standard\\-weights(*:1492))))|links(?|(*:1512)|/(?|create\\-all(*:1536)|([^/]++)(?|/toggle(*:1563)|(*:1572))))|sta(?|ts(*:1592)|ndard\\-weight\\-profiles(?|(*:1627)|/([^/]++)(*:1645)))|clear\\-data(*:1667)|themes(?|(*:1685)|/([^/]++)(?|(*:1706))))|(*:1718))|branches/([^/]++)(?|(*:1748))))|/f(?|ormulario/([^/]++)(?|(*:1786))|avorites/([^/]++)/(?|toggle(*:1823)|check(*:1837)))|/s(?|/([^/]++)(?|(*:1865)|/cards/([^/]++)/logins(*:1896))|ervers/([^/]++)/check\\-status(*:1935))|/ca(?|rds/([^/]++)/logins(*:1970)|meras/checklists/([^/]++)(?|(*:2007)|/(?|detalhes(*:2028)|itens(?|(*:2045)|/([^/]++)/limpar\\-historico(*:2081))|finalizar(*:2100)|cancelar(*:2117)|anexos(?|(*:2135)|/([^/]++)(*:2153))|solucao(*:2170)|pdf(?|(*:2185)|/download(*:2203)))))|/reset\\-password/([^/]++)(*:2241)|/verify\\-email/([^/]++)/([^/]++)(*:2282))/?$}sDu',
    ),
    3 => 
    array (
      47 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KnuSZCbChpcczmiM',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      67 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::n8QEKJpOmx8aMO2Z',
          ),
          1 => 
          array (
            0 => 'code',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      99 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.tabs.show',
          ),
          1 => 
          array (
            0 => 'tab',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.tabs.edit',
          ),
          1 => 
          array (
            0 => 'tab',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      119 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.tabs.update',
          ),
          1 => 
          array (
            0 => 'tab',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.tabs.destroy',
          ),
          1 => 
          array (
            0 => 'tab',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      148 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.show',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      164 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.edit',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      178 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.logins',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      199 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.check-status',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      223 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.filtered-logins',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      232 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.update',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cards.destroy',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      261 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.show',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      274 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.edit',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      282 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.update',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.categories.destroy',
          ),
          1 => 
          array (
            0 => 'category',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      324 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.edit',
          ),
          1 => 
          array (
            0 => 'dvr',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      346 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.toggle-status',
          ),
          1 => 
          array (
            0 => 'dvr',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      370 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.reorder-cameras',
          ),
          1 => 
          array (
            0 => 'dvr',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      393 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.import-cameras',
          ),
          1 => 
          array (
            0 => 'dvr',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      402 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.update',
          ),
          1 => 
          array (
            0 => 'dvr',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.dvrs.destroy',
          ),
          1 => 
          array (
            0 => 'dvr',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      438 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.cameras.edit',
          ),
          1 => 
          array (
            0 => 'camera',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      460 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.cameras.toggle-status',
          ),
          1 => 
          array (
            0 => 'camera',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      469 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.cameras.update',
          ),
          1 => 
          array (
            0 => 'camera',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.cameras.cameras.destroy',
          ),
          1 => 
          array (
            0 => 'camera',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      512 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.show',
          ),
          1 => 
          array (
            0 => 'system_login',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      528 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.edit',
          ),
          1 => 
          array (
            0 => 'system_login',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      552 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.toggle-password',
          ),
          1 => 
          array (
            0 => 'systemLogin',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      574 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.permissions',
          ),
          1 => 
          array (
            0 => 'systemLogin',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.update-permissions',
          ),
          1 => 
          array (
            0 => 'systemLogin',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      584 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.update',
          ),
          1 => 
          array (
            0 => 'system_login',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-logins.destroy',
          ),
          1 => 
          array (
            0 => 'system_login',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      610 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.show',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      626 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.edit',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      648 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.permissions',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.update-permissions',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      671 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.secret-url',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      693 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.secret-url.regenerate',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      707 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.secret-url.toggle',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      725 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.secret-url.expiration',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      736 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.update',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.system-users.destroy',
          ),
          1 => 
          array (
            0 => 'user',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      770 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.servers.show',
          ),
          1 => 
          array (
            0 => 'server',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      786 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.servers.edit',
          ),
          1 => 
          array (
            0 => 'server',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      807 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.admin.servers.check-status',
          ),
          1 => 
          array (
            0 => 'server',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      816 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.servers.update',
          ),
          1 => 
          array (
            0 => 'server',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.servers.destroy',
          ),
          1 => 
          array (
            0 => 'server',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      845 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.server-groups.show',
          ),
          1 => 
          array (
            0 => 'server_group',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      858 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.server-groups.edit',
          ),
          1 => 
          array (
            0 => 'server_group',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      866 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.server-groups.update',
          ),
          1 => 
          array (
            0 => 'server_group',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.server-groups.destroy',
          ),
          1 => 
          array (
            0 => 'server_group',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      893 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.show',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      909 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.edit',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      925 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.cards',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.update-cards',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      948 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.secret-url',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      966 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.secret-url.update',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      984 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.secret-url.regenerate',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      998 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.secret-url.toggle',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1016 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.secret-url.expiration',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1028 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.update',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.sectors.destroy',
          ),
          1 => 
          array (
            0 => 'sector',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1053 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.seats.show',
          ),
          1 => 
          array (
            0 => 'seat',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1067 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.seats.edit',
          ),
          1 => 
          array (
            0 => 'seat',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1076 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.seats.update',
          ),
          1 => 
          array (
            0 => 'seat',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.seats.destroy',
          ),
          1 => 
          array (
            0 => 'seat',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.datacenters.show',
          ),
          1 => 
          array (
            0 => 'datacenter',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1125 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.datacenters.edit',
          ),
          1 => 
          array (
            0 => 'datacenter',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1134 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.datacenters.update',
          ),
          1 => 
          array (
            0 => 'datacenter',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.datacenters.destroy',
          ),
          1 => 
          array (
            0 => 'datacenter',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1169 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.show',
          ),
          1 => 
          array (
            0 => 'network_map',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1186 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.edit',
          ),
          1 => 
          array (
            0 => 'network_map',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1209 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.toggle-status',
          ),
          1 => 
          array (
            0 => 'networkMap',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1235 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.seats.get',
          ),
          1 => 
          array (
            0 => 'network_map',
            1 => 'code',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.seats.update',
          ),
          1 => 
          array (
            0 => 'network_map',
            1 => 'code',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1258 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.resync-seats',
          ),
          1 => 
          array (
            0 => 'network_map',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1268 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.update',
          ),
          1 => 
          array (
            0 => 'network_map',
          ),
          2 => 
          array (
            'PUT' => 0,
            'PATCH' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.network-maps.destroy',
          ),
          1 => 
          array (
            0 => 'network_map',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1295 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.show',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1315 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.edit',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1337 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.export-csv',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1349 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.export-pdf',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1372 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.questions.store',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1393 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.questions.update',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'question',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.questions.destroy',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'question',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1416 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.options.store',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'question',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1436 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.options.reorder',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'question',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1456 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.options.update',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'question',
            2 => 'option',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.options.destroy',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'question',
            2 => 'option',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1492 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.questions.apply-standard-weights',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'question',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1512 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.links.store',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1536 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.links.store-all',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1563 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.links.toggle',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'link',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1572 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.links.destroy',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'link',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1592 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.stats',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1627 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.standard-weight-profiles.store',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1645 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.standard-weight-profiles.destroy',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'profile',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1667 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.clear-data',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1685 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.themes.store',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1706 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.themes.update',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'theme',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.themes.destroy',
          ),
          1 => 
          array (
            0 => 'form',
            1 => 'theme',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1718 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.update',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.forms.destroy',
          ),
          1 => 
          array (
            0 => 'form',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1748 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.branches.update',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'admin.branches.destroy',
          ),
          1 => 
          array (
            0 => 'branch',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1786 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'form.show',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'form.submit',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1823 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'favorites.toggle',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1837 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'favorites.check',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1865 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'secret.url',
          ),
          1 => 
          array (
            0 => 'secret_url',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1896 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'secret.url.logins',
          ),
          1 => 
          array (
            0 => 'secret_url',
            1 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1935 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'servers.check-status',
          ),
          1 => 
          array (
            0 => 'server',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1970 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'public.cards.logins',
          ),
          1 => 
          array (
            0 => 'card',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2007 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.show',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2028 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.detalhes',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2045 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.itens.store',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2081 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.itens.limpar-historico',
          ),
          1 => 
          array (
            0 => 'checklist',
            1 => 'item',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2100 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.finalizar',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2117 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.cancelar',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2135 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.anexos.store',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2153 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.anexos.destroy',
          ),
          1 => 
          array (
            0 => 'checklist',
            1 => 'anexo',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2170 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.solucao.store',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2185 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.pdf',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2203 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cameras.checklists.pdf.download',
          ),
          1 => 
          array (
            0 => 'checklist',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2241 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2282 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.verify',
          ),
          1 => 
          array (
            0 => 'id',
            1 => 'hash',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'sanctum.csrf-cookie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'sanctum.csrf-cookie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5YUdPETytuLY3eJF' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:77:"function (\\Illuminate\\Http\\Request $request) {
    return $request->user();
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000a880000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::5YUdPETytuLY3eJF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KnuSZCbChpcczmiM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/system-users/{card}/logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth.any',
        ),
        'uses' => '\\App\\Http\\Controllers\\Api\\SystemUserController@getLoginsByCard',
        'controller' => '\\App\\Http\\Controllers\\Api\\SystemUserController@getLoginsByCard',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::KnuSZCbChpcczmiM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::n8QEKJpOmx8aMO2Z' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/seats/{code}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SeatApiController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\SeatApiController@show',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::n8QEKJpOmx8aMO2Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::annYhWbdyXTU3ttl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/seats/occupied/list',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\SeatApiController@occupied',
        'controller' => 'App\\Http\\Controllers\\Api\\SeatApiController@occupied',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::annYhWbdyXTU3ttl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'form.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'formulario/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:30,1',
        ),
        'uses' => 'App\\Http\\Controllers\\FormController@show',
        'controller' => 'App\\Http\\Controllers\\FormController@show',
        'as' => 'form.show',
        'namespace' => NULL,
        'prefix' => '/formulario',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'form.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'formulario/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:30,1',
        ),
        'uses' => 'App\\Http\\Controllers\\FormController@submit',
        'controller' => 'App\\Http\\Controllers\\FormController@submit',
        'as' => 'form.submit',
        'namespace' => NULL,
        'prefix' => '/formulario',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'secret.url' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 's/{secret_url}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'secret.url',
          2 => 'throttle:60,1',
        ),
        'uses' => 'App\\Http\\Controllers\\SecretUrlController@index',
        'controller' => 'App\\Http\\Controllers\\SecretUrlController@index',
        'namespace' => NULL,
        'prefix' => '/s',
        'where' => 
        array (
        ),
        'as' => 'secret.url',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'secret.url.logins' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 's/{secret_url}/cards/{card}/logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'secret.url',
          2 => 'throttle:60,1',
        ),
        'uses' => 'App\\Http\\Controllers\\SecretUrlController@logins',
        'controller' => 'App\\Http\\Controllers\\SecretUrlController@logins',
        'namespace' => NULL,
        'prefix' => '/s',
        'where' => 
        array (
        ),
        'as' => 'secret.url.logins',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'public.auth',
        ),
        'uses' => 'App\\Http\\Controllers\\HomeController@index',
        'controller' => 'App\\Http\\Controllers\\HomeController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'servers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'servers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'public.auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ServerController@index',
        'controller' => 'App\\Http\\Controllers\\ServerController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'servers.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'servers.check-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'servers/{server}/check-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\ServerController@checkStatus',
        'controller' => 'App\\Http\\Controllers\\ServerController@checkStatus',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'servers.check-status',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'public.cards.logins' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cards/{card}/logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'public.auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@logins',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@logins',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'public.cards.logins',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'test-toast' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'test-toast',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:47:"function () {
    return \\view(\'test-toast\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000a950000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'test-toast',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'test-permissions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'test-permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:53:"function () {
    return \\view(\'test-permissions\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000a970000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'test-permissions',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'test-modal-click' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'test-modal-click',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:53:"function () {
    return \\view(\'test-modal-click\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000a990000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'test-modal-click',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'favorites.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'favorites/{card}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\FavoriteController@toggle',
        'controller' => 'App\\Http\\Controllers\\FavoriteController@toggle',
        'as' => 'favorites.toggle',
        'namespace' => NULL,
        'prefix' => '/favorites',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'favorites.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'favorites',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\FavoriteController@index',
        'controller' => 'App\\Http\\Controllers\\FavoriteController@index',
        'as' => 'favorites.index',
        'namespace' => NULL,
        'prefix' => '/favorites',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'favorites.check' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'favorites/{card}/check',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\FavoriteController@check',
        'controller' => 'App\\Http\\Controllers\\FavoriteController@check',
        'as' => 'favorites.check',
        'namespace' => NULL,
        'prefix' => '/favorites',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.tabs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/tabs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.tabs.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\TabController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\TabController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.tabs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/tabs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.tabs.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\TabController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\TabController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.tabs.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/tabs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.tabs.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\TabController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\TabController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.tabs.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/tabs/{tab}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.tabs.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\TabController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\TabController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.tabs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/tabs/{tab}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.tabs.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\TabController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\TabController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.tabs.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/tabs/{tab}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.tabs.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\TabController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\TabController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.tabs.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/tabs/{tab}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.tabs.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\TabController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\TabController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cards',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.cards.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cards/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.cards.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/cards',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.cards.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cards/{card}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.cards.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cards/{card}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.cards.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/cards/{card}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.cards.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/cards/{card}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.cards.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.logins' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cards/{card}/logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@logins',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@logins',
        'as' => 'admin.cards.logins',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.check-status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cards/{card}/check-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CardController@checkStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\CardController@checkStatus',
        'as' => 'admin.cards.check-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-logins.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-logins/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-logins.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-logins.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-logins/{system_login}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-logins.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-logins/{system_login}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-logins.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/system-logins/{system_login}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-logins.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/system-logins/{system_login}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-logins.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.toggle-password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-logins/{systemLogin}/toggle-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@togglePassword',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@togglePassword',
        'as' => 'admin.system-logins.toggle-password',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.permissions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-logins/{systemLogin}/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@permissions',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@permissions',
        'as' => 'admin.system-logins.permissions',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-logins.update-permissions' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-logins/{systemLogin}/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@updatePermissions',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@updatePermissions',
        'as' => 'admin.system-logins.update-permissions',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cards.filtered-logins' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cards/{card}/filtered-logins',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@getFilteredLogins',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemLoginController@getFilteredLogins',
        'as' => 'admin.cards.filtered-logins',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.categories.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categories/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.categories.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/categories',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.categories.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categories/{category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.categories.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categories/{category}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.categories.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/categories/{category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.categories.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/categories/{category}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.categories.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.categories.get-all' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/categories-get-all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CategoryController@getAll',
        'controller' => 'App\\Http\\Controllers\\Admin\\CategoryController@getAll',
        'as' => 'admin.categories.get-all',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.datacenters.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/datacenters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.datacenters.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\DataCenterController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataCenterController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.datacenters.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/datacenters/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.datacenters.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\DataCenterController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataCenterController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.datacenters.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/datacenters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.datacenters.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\DataCenterController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataCenterController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.datacenters.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/datacenters/{datacenter}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.datacenters.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\DataCenterController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataCenterController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.datacenters.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/datacenters/{datacenter}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.datacenters.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\DataCenterController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataCenterController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.datacenters.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/datacenters/{datacenter}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.datacenters.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\DataCenterController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataCenterController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.datacenters.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/datacenters/{datacenter}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.datacenters.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\DataCenterController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\DataCenterController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.servers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/servers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.servers.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.servers.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/servers/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.servers.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.servers.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/servers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.servers.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.servers.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/servers/{server}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.servers.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.servers.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/servers/{server}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.servers.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.servers.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/servers/{server}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.servers.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.servers.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/servers/{server}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.servers.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.admin.servers.check-status' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/servers/{server}/check-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerController@checkStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerController@checkStatus',
        'as' => 'admin.admin.servers.check-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.server-groups.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/server-groups',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.server-groups.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.server-groups.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/server-groups/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.server-groups.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.server-groups.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/server-groups',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.server-groups.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.server-groups.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/server-groups/{server_group}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.server-groups.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.server-groups.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/server-groups/{server_group}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.server-groups.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.server-groups.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/server-groups/{server_group}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.server-groups.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.server-groups.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/server-groups/{server_group}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.server-groups.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\ServerGroupController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-users.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-users/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-users.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-users',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-users.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-users.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-users/{user}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-users.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/system-users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-users.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/system-users/{user}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.system-users.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.permissions' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-users/{user}/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@permissions',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@permissions',
        'as' => 'admin.system-users.permissions',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.update-permissions' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-users/{user}/permissions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@updatePermissions',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@updatePermissions',
        'as' => 'admin.system-users.update-permissions',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.secret-url' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/system-users/{user}/secret-url',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@showSecretUrl',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@showSecretUrl',
        'as' => 'admin.system-users.secret-url',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.secret-url.regenerate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-users/{user}/secret-url/regenerate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@regenerateSecretUrl',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@regenerateSecretUrl',
        'as' => 'admin.system-users.secret-url.regenerate',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.secret-url.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-users/{user}/secret-url/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@toggleSecretUrl',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@toggleSecretUrl',
        'as' => 'admin.system-users.secret-url.toggle',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.system-users.secret-url.expiration' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/system-users/{user}/secret-url/expiration',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SystemUserController@setSecretUrlExpiration',
        'controller' => 'App\\Http\\Controllers\\Admin\\SystemUserController@setSecretUrlExpiration',
        'as' => 'admin.system-users.secret-url.expiration',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sectors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.sectors.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sectors/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.sectors.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/sectors',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.sectors.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sectors/{sector}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.sectors.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sectors/{sector}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.sectors.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/sectors/{sector}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.sectors.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/sectors/{sector}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.sectors.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.cards' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sectors/{sector}/cards',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@cards',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@cards',
        'as' => 'admin.sectors.cards',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.update-cards' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/sectors/{sector}/cards',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@updateCards',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@updateCards',
        'as' => 'admin.sectors.update-cards',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.secret-url' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/sectors/{sector}/secret-url',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@secretUrl',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@secretUrl',
        'as' => 'admin.sectors.secret-url',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.secret-url.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/sectors/{sector}/secret-url/update',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@updateSecretUrl',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@updateSecretUrl',
        'as' => 'admin.sectors.secret-url.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.secret-url.regenerate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/sectors/{sector}/secret-url/regenerate',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@regenerateSecretUrl',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@regenerateSecretUrl',
        'as' => 'admin.sectors.secret-url.regenerate',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.secret-url.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/sectors/{sector}/secret-url/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@toggleSecretUrl',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@toggleSecretUrl',
        'as' => 'admin.sectors.secret-url.toggle',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.sectors.secret-url.expiration' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/sectors/{sector}/secret-url/expiration',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\SectorController@setSecretUrlExpiration',
        'controller' => 'App\\Http\\Controllers\\Admin\\SectorController@setSecretUrlExpiration',
        'as' => 'admin.sectors.secret-url.expiration',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/network-maps',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.network-maps.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/network-maps/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.network-maps.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/network-maps',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.network-maps.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/network-maps/{network_map}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.network-maps.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/network-maps/{network_map}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.network-maps.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/network-maps/{network_map}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.network-maps.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/network-maps/{network_map}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.network-maps.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.toggle-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/network-maps/{networkMap}/toggle-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@toggleStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@toggleStatus',
        'as' => 'admin.network-maps.toggle-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.seats.get' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/network-maps/{network_map}/seats/{code}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@getSeat',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@getSeat',
        'as' => 'admin.network-maps.seats.get',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.seats.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/network-maps/{network_map}/seats/{code}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@updateSeat',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@updateSeat',
        'as' => 'admin.network-maps.seats.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.network-maps.resync-seats' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/network-maps/{network_map}/resync-seats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@resyncSeats',
        'controller' => 'App\\Http\\Controllers\\Admin\\NetworkMapController@resyncSeats',
        'as' => 'admin.network-maps.resync-seats',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.seats.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/seats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.seats.index',
        'uses' => 'App\\Http\\Controllers\\Admin\\SeatController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\SeatController@index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.seats.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/seats/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.seats.create',
        'uses' => 'App\\Http\\Controllers\\Admin\\SeatController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\SeatController@create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.seats.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/seats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.seats.store',
        'uses' => 'App\\Http\\Controllers\\Admin\\SeatController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\SeatController@store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.seats.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/seats/{seat}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.seats.show',
        'uses' => 'App\\Http\\Controllers\\Admin\\SeatController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\SeatController@show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.seats.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/seats/{seat}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.seats.edit',
        'uses' => 'App\\Http\\Controllers\\Admin\\SeatController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\SeatController@edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.seats.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
        1 => 'PATCH',
      ),
      'uri' => 'admin/seats/{seat}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.seats.update',
        'uses' => 'App\\Http\\Controllers\\Admin\\SeatController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\SeatController@update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.seats.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/seats/{seat}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'as' => 'admin.seats.destroy',
        'uses' => 'App\\Http\\Controllers\\Admin\\SeatController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\SeatController@destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cameras',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@index',
        'as' => 'admin.cameras.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cameras/dvrs/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@createDvrForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@createDvrForm',
        'as' => 'admin.cameras.dvrs.create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/cameras/dvrs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@storeDvr',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@storeDvr',
        'as' => 'admin.cameras.dvrs.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cameras/dvrs/{dvr}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@editDvrForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@editDvrForm',
        'as' => 'admin.cameras.dvrs.edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/cameras/dvrs/{dvr}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@updateDvr',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@updateDvr',
        'as' => 'admin.cameras.dvrs.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/cameras/dvrs/{dvr}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@destroyDvr',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@destroyDvr',
        'as' => 'admin.cameras.dvrs.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.toggle-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/cameras/dvrs/{dvr}/toggle-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@toggleDvrStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@toggleDvrStatus',
        'as' => 'admin.cameras.dvrs.toggle-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.reorder-cameras' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/cameras/dvrs/{dvr}/reorder-cameras',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@reorderCameras',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@reorderCameras',
        'as' => 'admin.cameras.dvrs.reorder-cameras',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.dvrs.import-cameras' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/cameras/dvrs/{dvr}/import-cameras',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@importCameras',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@importCameras',
        'as' => 'admin.cameras.dvrs.import-cameras',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.cameras.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cameras/cameras/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@createCameraForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@createCameraForm',
        'as' => 'admin.cameras.cameras.create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.cameras.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/cameras/cameras',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@storeCamera',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@storeCamera',
        'as' => 'admin.cameras.cameras.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.cameras.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cameras/cameras/{camera}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@editCameraForm',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@editCameraForm',
        'as' => 'admin.cameras.cameras.edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.cameras.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/cameras/cameras/{camera}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@updateCamera',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@updateCamera',
        'as' => 'admin.cameras.cameras.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.cameras.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/cameras/cameras/{camera}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@destroyCamera',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@destroyCamera',
        'as' => 'admin.cameras.cameras.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.cameras.cameras.toggle-status' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/cameras/cameras/{camera}/toggle-status',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\CameraController@toggleCameraStatus',
        'controller' => 'App\\Http\\Controllers\\Admin\\CameraController@toggleCameraStatus',
        'as' => 'admin.cameras.cameras.toggle-status',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/forms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@index',
        'as' => 'admin.forms.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/forms/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@create',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@create',
        'as' => 'admin.forms.create',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@store',
        'as' => 'admin.forms.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/forms/{form}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@show',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@show',
        'as' => 'admin.forms.show',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/forms/{form}/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@edit',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@edit',
        'as' => 'admin.forms.edit',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/forms/{form}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@update',
        'as' => 'admin.forms.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/forms/{form}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@destroy',
        'as' => 'admin.forms.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.questions.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/questions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@storeQuestion',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@storeQuestion',
        'as' => 'admin.forms.questions.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.questions.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/forms/{form}/questions/{question}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@updateQuestion',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@updateQuestion',
        'as' => 'admin.forms.questions.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.questions.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/forms/{form}/questions/{question}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@destroyQuestion',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@destroyQuestion',
        'as' => 'admin.forms.questions.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.options.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/questions/{question}/options',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@storeOption',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@storeOption',
        'as' => 'admin.forms.options.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.options.reorder' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/questions/{question}/options/reorder',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@reorderOptions',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@reorderOptions',
        'as' => 'admin.forms.options.reorder',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.options.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/forms/{form}/questions/{question}/options/{option}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@updateOption',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@updateOption',
        'as' => 'admin.forms.options.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.options.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/forms/{form}/questions/{question}/options/{option}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@destroyOption',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@destroyOption',
        'as' => 'admin.forms.options.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.links.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/links',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@storeLink',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@storeLink',
        'as' => 'admin.forms.links.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.links.store-all' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/links/create-all',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@storeLinksForAll',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@storeLinksForAll',
        'as' => 'admin.forms.links.store-all',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.links.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/links/{link}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@toggleLink',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@toggleLink',
        'as' => 'admin.forms.links.toggle',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.links.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/forms/{form}/links/{link}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@destroyLink',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@destroyLink',
        'as' => 'admin.forms.links.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.stats' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/forms/{form}/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@stats',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@stats',
        'as' => 'admin.forms.stats',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.clear-data' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/clear-data',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@clearData',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@clearData',
        'as' => 'admin.forms.clear-data',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.export-csv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/forms/{form}/export-csv',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@exportCsv',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@exportCsv',
        'as' => 'admin.forms.export-csv',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.export-pdf' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/export-pdf',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@exportPdf',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@exportPdf',
        'as' => 'admin.forms.export-pdf',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.themes.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/themes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@storeTheme',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@storeTheme',
        'as' => 'admin.forms.themes.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.themes.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/forms/{form}/themes/{theme}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@updateTheme',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@updateTheme',
        'as' => 'admin.forms.themes.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.themes.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/forms/{form}/themes/{theme}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@destroyTheme',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@destroyTheme',
        'as' => 'admin.forms.themes.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.questions.apply-standard-weights' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/questions/{question}/apply-standard-weights',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\FormController@applyStandardWeights',
        'controller' => 'App\\Http\\Controllers\\Admin\\FormController@applyStandardWeights',
        'as' => 'admin.forms.questions.apply-standard-weights',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.standard-weight-profiles.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/forms/{form}/standard-weight-profiles',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StandardWeightProfileController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\StandardWeightProfileController@store',
        'as' => 'admin.forms.standard-weight-profiles.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.forms.standard-weight-profiles.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/forms/{form}/standard-weight-profiles/{profile}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\StandardWeightProfileController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\StandardWeightProfileController@destroy',
        'as' => 'admin.forms.standard-weight-profiles.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.branches.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/branches',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BranchController@index',
        'controller' => 'App\\Http\\Controllers\\Admin\\BranchController@index',
        'as' => 'admin.branches.index',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.branches.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/branches',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BranchController@store',
        'controller' => 'App\\Http\\Controllers\\Admin\\BranchController@store',
        'as' => 'admin.branches.store',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.branches.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'admin/branches/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BranchController@update',
        'controller' => 'App\\Http\\Controllers\\Admin\\BranchController@update',
        'as' => 'admin.branches.update',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.branches.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'admin/branches/{branch}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'App\\Http\\Controllers\\Admin\\BranchController@destroy',
        'controller' => 'App\\Http\\Controllers\\Admin\\BranchController@destroy',
        'as' => 'admin.branches.destroy',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/debug/auth',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
          2 => 'admin.access',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:696:"function() {
        $data = [
            \'web_auth\' => \\auth()->guard(\'web\')->check(),
            \'web_user\' => \\auth()->guard(\'web\')->user(),
            \'system_auth\' => \\auth()->guard(\'system\')->check(),
            \'system_user\' => \\auth()->guard(\'system\')->user(),
            \'session_id\' => \\session()->getId(),
            \'session_data\' => \\session()->all()
        ];
        
        if (\\auth()->guard(\'web\')->check()) {
            $user = \\auth()->guard(\'web\')->user();
            $data[\'user_permissions\'] = $user->userPermissions->toArray();
            $data[\'can_view_passwords\'] = $user->canViewPasswords();
        }
        
        return \\response()->json($data);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000b350000000000000000";}}',
        'as' => 'admin.',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cameras',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@index',
        'controller' => 'App\\Http\\Controllers\\CameraController@index',
        'as' => 'cameras.index',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/checklists',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@storeChecklist',
        'controller' => 'App\\Http\\Controllers\\CameraController@storeChecklist',
        'as' => 'cameras.checklists.store',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.historico.apagar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/historico/apagar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@apagarHistorico',
        'controller' => 'App\\Http\\Controllers\\CameraController@apagarHistorico',
        'as' => 'cameras.historico.apagar',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cameras/checklists/{checklist}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@showChecklist',
        'controller' => 'App\\Http\\Controllers\\CameraController@showChecklist',
        'as' => 'cameras.checklists.show',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.detalhes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cameras/checklists/{checklist}/detalhes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@showChecklistDetalhes',
        'controller' => 'App\\Http\\Controllers\\CameraController@showChecklistDetalhes',
        'as' => 'cameras.checklists.detalhes',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.itens.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/checklists/{checklist}/itens',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@storeItem',
        'controller' => 'App\\Http\\Controllers\\CameraController@storeItem',
        'as' => 'cameras.checklists.itens.store',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.itens.limpar-historico' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/checklists/{checklist}/itens/{item}/limpar-historico',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@limparHistoricoItem',
        'controller' => 'App\\Http\\Controllers\\CameraController@limparHistoricoItem',
        'as' => 'cameras.checklists.itens.limpar-historico',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.finalizar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/checklists/{checklist}/finalizar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@finalizarChecklist',
        'controller' => 'App\\Http\\Controllers\\CameraController@finalizarChecklist',
        'as' => 'cameras.checklists.finalizar',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.cancelar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/checklists/{checklist}/cancelar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@cancelarChecklist',
        'controller' => 'App\\Http\\Controllers\\CameraController@cancelarChecklist',
        'as' => 'cameras.checklists.cancelar',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.anexos.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/checklists/{checklist}/anexos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@storeAnexo',
        'controller' => 'App\\Http\\Controllers\\CameraController@storeAnexo',
        'as' => 'cameras.checklists.anexos.store',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.solucao.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'cameras/checklists/{checklist}/solucao',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@storeSolucao',
        'controller' => 'App\\Http\\Controllers\\CameraController@storeSolucao',
        'as' => 'cameras.checklists.solucao.store',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.anexos.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'cameras/checklists/{checklist}/anexos/{anexo}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@destroyAnexo',
        'controller' => 'App\\Http\\Controllers\\CameraController@destroyAnexo',
        'as' => 'cameras.checklists.anexos.destroy',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.pdf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cameras/checklists/{checklist}/pdf',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@viewPdf',
        'controller' => 'App\\Http\\Controllers\\CameraController@viewPdf',
        'as' => 'cameras.checklists.pdf',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cameras.checklists.pdf.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'cameras/checklists/{checklist}/pdf/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth.any',
        ),
        'uses' => 'App\\Http\\Controllers\\CameraController@downloadPdf',
        'controller' => 'App\\Http\\Controllers\\CameraController@downloadPdf',
        'as' => 'cameras.checklists.pdf.download',
        'namespace' => NULL,
        'prefix' => '/cameras',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::b0h094QkCVVKVcpp' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\RegisteredUserController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::b0h094QkCVVKVcpp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aMcn97a3K7AXdWpH' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::aMcn97a3K7AXdWpH',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\PasswordResetLinkController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.email',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reset-password/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@create',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.reset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\NewPasswordController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.notice' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'verify-email',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\EmailVerificationPromptController@__invoke',
        'controller' => 'App\\Http\\Controllers\\Auth\\EmailVerificationPromptController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.notice',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.verify' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'verify-email/{id}/{hash}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'signed',
          3 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\VerifyEmailController@__invoke',
        'controller' => 'App\\Http\\Controllers\\Auth\\VerifyEmailController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.verify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'email/verification-notification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'throttle:6,1',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\EmailVerificationNotificationController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\EmailVerificationNotificationController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.send',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'confirm-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@show',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4RG5bYOwpWZKwSPx' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'confirm-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@store',
        'controller' => 'App\\Http\\Controllers\\Auth\\ConfirmablePasswordController@store',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::4RG5bYOwpWZKwSPx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@destroy',
        'controller' => 'App\\Http\\Controllers\\Auth\\AuthenticatedSessionController@destroy',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
