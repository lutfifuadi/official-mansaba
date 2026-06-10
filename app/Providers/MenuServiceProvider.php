<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    private $menuData = null;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer([
            'layouts.sections.menu.verticalMenu',
            'layouts.sections.menu.horizontalMenu'
        ], function ($view) {
            if ($this->menuData === null) {
                $this->menuData = [
                    $this->loadMenu('verticalMenu'),
                    $this->loadMenu('horizontalMenu')
                ];
            }
            $view->with('menuData', $this->menuData);
        });
    }

    private function loadMenu(string $type)
    {
        $roleMap = [
            'super_admin' => 'superadmin',
            'admin'       => 'admin',
            'operator'    => 'operator',
            'editor'      => 'editor',
        ];

        $user = auth()->user();
        $suffix = $user && isset($roleMap[$user->role]) ? '-' . $roleMap[$user->role] : '';

        $path = base_path("resources/menu/{$type}{$suffix}.json");

        if (!file_exists($path)) {
            $path = base_path("resources/menu/{$type}.json");
        }

        return json_decode(file_get_contents($path));
    }
}
