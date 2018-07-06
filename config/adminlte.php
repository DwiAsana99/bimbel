
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'PanDana',

    'title_prefix' => '',

    'title_postfix' => ' - PanDana',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Pan</b>Dana',

    'logo_mini' => '<b>P</b>DN',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'yellow-light',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => 'fixed',

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'logout_method' => null,

    'login_url' => 'login',

    'register_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        'MAIN NAVIGATION',
        [
            'text'  => 'Dashboard',
            'icon'  => 'tachometer',
            'url'   => '/home',
        ],
        [
            'text'  => 'Master Data',
            'icon'  => 'database',
            'submenu' => [
                [
                    'text' => 'Kolektor',
                    'url'  => '/m/kolektor',
                ],
                [
                    'text' => 'Akun',
                    'url'  => '/m/akun',
                ],
                [
                    'text' => 'Template',
                    'url'  => '/m/txtemplate',
                ],
            ],
        ],
        // [
        //     'text'  => 'RBAC',
        //     'icon'  => 'universal-access',
        //     'submenu' => [
        //         [
        //             'text' => 'User',
        //             'url'  => '/user',
        //         ],
        //         [
        //             'text' => 'Role & Permission',
        //             'url'  => '/role',
        //         ],
        //     ],
        // ],
        [
            'text'  => 'Nasabah & Anggota',
            'icon'  => 'users',
            'url'   => '/nasabah',
        ],
        [
            'text'  => 'Simpanan Anggota',
            'icon'  => 'bank',
            'url'   => '/simpanan',
        ],
        [
            'text'  => 'Tabungan',
            'icon'  => 'money',
            'submenu' => [
                [
                    'text' => 'Data Tabungan',
                    'url'  => '/tabungan',
                ],
                [
                    'text' => 'Setor Tabungan',
                    'url'  => '/setorcepat',
                ],
                [
                    'text' => 'Tarik Tabungan',
                    'url'  => '/tarikcepat',
                ],
                [
                    'text' => 'Posting Bunga',
                    'url'  => '/postingbunga',
                ],
            ],
        ],
        [
            'text'  => 'Simulasi Pinjaman',
            'icon'  => 'calculator',
            'url'   => '/simpinjaman',
        ],
        [
            'text'  => 'Pinjaman',
            'icon'  => 'credit-card-alt',
            'submenu' => [
                [
                    'text' => 'Data Pinjaman',
                    'url'  => '/pinjaman',
                ],
                [
                    'text' => 'Data Kompensasi',
                    'url'  => '/kompensasi',
                ],
                [
                    'text' => 'Pinjaman Lunas',
                    'url'  => '/pinjamanlunas',
                ],
            ],
        ],
        [
            'text'  => 'Deposito',
            'icon'  => 'line-chart',
            'submenu' => [
                [
                    'text' => 'Data Deposito',
                    'url'  => '/deposito',
                ],
                [
                    'text' => 'Posting Bunga Deposito',
                    'url'  => '/postingdeposito',
                ],
                [
                    'text' => 'Rekomendasi Berakhir',
                    'url'  => '/depositoberakhir',
                ],
            ],
        ],
        [
            'text'  => 'Transaksi Lain',
            'icon'  => 'exchange',
            'submenu' => [
                [
                    'text' => 'Template Transaksi',
                    'url'  => '/txtemplate',
                ],
                [
                    'text' => 'Penjurnalan',
                    'url'  => '/txlain',
                ],
            ],
        ],
        [
            'text'  => 'Laporan',
            'icon'  => 'book',
            'submenu' => [
                [
                    'text' => 'Tabungan',
                    'url'  => '/laporan/tabungan',
                ],
                [
                    'text' => 'Pinjaman',
                    'url'  => '/laporan/pinjaman',
                ],
                [
                    'text' => 'Deposito',
                    'url'  => '/laporan/deposito',
                ],
                [
                    'text' => 'Keuangan',
                    'url'  => '/laporan/keu',
                ],
            ],
        ],
        [
            'text'  => 'Setting',
            'icon'  => 'gears',
            'submenu' => [
                [
                    'text' => 'Koperasi',
                    'url'  => '/setting/koperasi',
                ],
                [
                    'text' => 'Atur Tanggal',
                    'url'  => '/setting/aturtanggal',
                ],
                [
                    'text' => 'Tabungan',
                    'url'  => '/setting/tabungan',
                ],
                [
                    'text' => 'Pinjaman',
                    'url'  => '/setting/pinjaman',
                ],
                [
                    'text' => 'Deposito',
                    'url'  => '/setting/deposito',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
        'select2'    => true,
        'chartjs'    => false,
    ],
];
