<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $verticalMenuData = $this->loadMenu('verticalMenu');
        $horizontalMenuData = $this->loadMenu('horizontalMenu');

        View::share('menuData', [$verticalMenuData, $horizontalMenuData]);
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
