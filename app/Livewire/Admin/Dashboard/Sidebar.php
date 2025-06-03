<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    public bool $sidebarOpen = false;

    public function toggleSidebar()
    {
        $this->sidebarOpen = !$this->sidebarOpen;
    }

    public function closeSidebar()
    {
        $this->sidebarOpen = false;
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function getNavigationItems()
    {
        return [
            [
                'name' => 'Dashboard',
                'route' => 'admin.home',
                'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z'
            ],
            [
                'name' => 'Products',
                'route' => 'admin.products',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10'
            ],
            [
                'name' => 'Orders',
                'route' => 'admin.orders',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
            ],
            [
                'name' => 'Blog',
                'route' => 'admin.blog',
                'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'
            ],
            [
                'name' => 'Settings',
                'route' => 'admin.settings',
                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
            ]
        ];
    }

    public function getCurrentUser()
    {
        return Auth::user();
    }

    public function isActiveRoute(string $routeName): bool
    {
        return request()->routeIs($routeName) || request()->routeIs($routeName . '.*');
    }

    public function render()
    {
        return view('livewire.admin.dashboard.sidebar', [
            'navigationItems' => $this->getNavigationItems(),
            'currentUser' => $this->getCurrentUser()
        ]);
    }
}