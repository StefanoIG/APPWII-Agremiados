<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'Agremiados',
    'title_prefix' => '',
    'title_postfix' => ' - Sistema de Gestión',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => '<b>Agre</b>miados',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Agremiados Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Agremiados',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => true,
    'usermenu_profile_url' => 'profile',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Asset Bundling option for the admin panel.
    | Currently, the next modes are supported: 'mix', 'vite' and 'vite_js_only'.
    | When using 'vite_js_only', it's expected that your CSS is imported using
    | JavaScript. Typically, in your application's 'resources/js/app.js' file.
    | If you are not using any of these, leave it as 'false'.
    |
    | For detailed instructions you can look the asset bundling section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        
        // Dashboard Principal
        [
            'text' => 'Dashboard',
            'route' => 'home',
            'icon' => 'fas fa-home',
        ],
        
        // Sección para Administradores
        [
            'header' => 'ADMINISTRACIÓN',
            'can' => 'admin',
        ],
        [
            'text' => 'Panel de Admin',
            'route' => 'admin.dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'can' => 'admin',
        ],
        [
            'text' => 'Gestión de Roles',
            'url' => 'roles',
            'icon' => 'fas fa-user-shield',
            'can' => 'admin',
        ],
        [
            'text' => 'Cortes Mensuales',
            'route' => 'monthly-cuts.index',
            'icon' => 'fas fa-calendar-alt',
            'can' => ['Admin', 'Secretaria'],
        ],
        [
            'text' => 'Disciplinas',
            'route' => 'disciplinas.index',
            'icon' => 'fas fa-dumbbell',
            'can' => 'admin',
        ],
        [
            'text' => 'Ver Pagos',
            'route' => 'admin.payments.index',
            'icon' => 'fas fa-money-bill',
            'can' => 'admin',
        ],
        
        // Sección para Secretaria
        [
            'header' => 'SECRETARÍA',
            'can' => 'secretaria',
        ],
        [
            'text' => 'Panel Secretaria',
            'route' => 'secretaria.dashboard',
            'icon' => 'fas fa-clipboard-check',
            'can' => 'secretaria',
        ],
        [
            'text' => 'Usuarios Pendientes',
            'route' => 'secretaria.usuarios-pendientes',
            'icon' => 'fas fa-user-clock',
            'can' => 'secretaria',
        ],
        [
            'text' => 'Gestión de Deudas',
            'route' => 'user-debts.index',
            'icon' => 'fas fa-file-invoice-dollar',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Pagos Pendientes',
            'route' => 'payments.pending',
            'icon' => 'fas fa-receipt',
            'can' => ['admin', 'secretaria'],
        ],
        
        // Sección común para Admin y Secretaria
        [
            'header' => 'GESTIÓN DE USUARIOS',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Todos los Usuarios',
            'url' => 'users',
            'icon' => 'fas fa-users',
            'can' => ['admin', 'secretaria'],
        ],
        
        // Sección del sistema
        [
            'header' => 'SISTEMA',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Categorías',
            'route' => 'categorias.index',
            'icon' => 'fas fa-tags',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Disciplinas',
            'route' => 'disciplinas.index',
            'icon' => 'fas fa-dumbbell',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Dashboard Admin',
            'route' => 'admin.dashboard',
            'icon' => 'fas fa-tachometer-alt',
            'can' => 'admin',
            'label' => 'Admin',
            'label_color' => 'danger',
        ],
        [
            'text' => 'Dashboard Secretaria',
            'route' => 'secretaria.dashboard',
            'icon' => 'fas fa-clipboard-check',
            'can' => 'secretaria',
            'label' => 'Secretaría',
            'label_color' => 'info',
        ],
        [
            'text' => 'Panel Usuario',
            'route' => 'home',
            'icon' => 'fas fa-user-circle',
            'can' => 'user',
            'label' => 'Usuario',
            'label_color' => 'success',
        ],
        [
            'text' => 'Categorías',
            'route' => 'categorias.index',
            'icon' => 'fas fa-list',
            'can' => 'admin',
        ],
        [
            'text' => 'Disciplinas',
            'route' => 'disciplinas.index',
            'icon' => 'fas fa-dumbbell',
            'can' => 'admin',
        ],
        [
            'text' => 'Equipos',
            'url' => 'equipos',
            'icon' => 'fas fa-users',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Alícuotas',
            'url' => 'cuotes',
            'icon' => 'fas fa-coins',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Pagos',
            'url' => 'payments',
            'icon' => 'fas fa-money-bill',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Artículos',
            'url' => 'articles',
            'icon' => 'fas fa-newspaper',
            'can' => ['admin', 'secretaria'],
        ],
        // Sección personal
        [
            'header' => 'MIS PAGOS',
        ],
        [
            'text' => 'Mis Deudas',
            'route' => 'user-debts.my-debts',
            'icon' => 'fas fa-money-bill-wave',
        ],
        
        // Sección de competencias y cursos
        [
            'header' => 'COMPETENCIAS Y CURSOS',
        ],
        [
            'text' => 'Competencias y Cursos',
            'route' => 'competitions.index',
            'icon' => 'fas fa-trophy',
        ],
        [
            'text' => 'Crear Competencia/Curso',
            'route' => 'competitions.create',
            'icon' => 'fas fa-plus-circle',
            'can' => ['admin', 'secretaria'],
        ],
        [
            'text' => 'Mis Equipos',
            'route' => 'competitions.teams',
            'icon' => 'fas fa-users',
        ],
        [
            'text' => 'Mis Invitaciones',
            'route' => 'invitations.index',
            'icon' => 'fas fa-envelope',
        ],
        
        // Sección personal
        [
            'header' => 'MI CUENTA',
        ],
        [
            'text' => 'Mi Perfil',
            'url' => '/profile',
            'icon' => 'fas fa-user',
        ],
        [
            'text' => 'Configuración',
            'icon' => 'fas fa-cog',
            'submenu' => [
                [
                    'text' => 'Cambiar Contraseña',
                    'url' => '#',
                    'icon' => 'fas fa-key',
                ],
                [
                    'text' => 'Notificaciones',
                    'url' => '#',
                    'icon' => 'fas fa-bell',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
