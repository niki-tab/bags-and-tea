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
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    public function getNavigationItems()
    {
        $user = Auth::user();
        
        $allItems = [
            [
                'name' => 'Dashboard',
                'route' => 'admin.home',
                'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z',
                'roles' => ['admin', 'vendor'] // Both admin and vendor can see
            ],
            [
                'name' => 'Products',
                'route' => 'admin.products',
                'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10',
                'roles' => ['admin', 'vendor'] // Both admin and vendor can see
            ],
            [
                'name' => 'Orders',
                'route' => 'admin.orders',
                'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                'roles' => ['admin', 'vendor'] // Both admin and vendor can see
            ],
            [
                'name' => 'Blog',
                'route' => 'admin.blog',
                'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                'roles' => ['admin'] // Only admin can see
            ],
            [
                'name' => 'Categories',
                'route' => 'admin.categories',
                'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                'roles' => ['admin'] // Only admin can see
            ],
            [
                'name' => 'Attributes',
                'route' => 'admin.attributes',
                'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                'roles' => ['admin'] // Only admin can see
            ],
            [
                'name' => 'Settings',
                'route' => 'admin.settings',
                'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                'roles' => ['admin'] // Only admin can see
            ]
        ];

        // Filter navigation items based on user role
        if (!$user) {
            return [];
        }

        $filteredItems = [];
        foreach ($allItems as $item) {
            // Check if user has any of the required roles for this item
            foreach ($item['roles'] as $requiredRole) {
                if ($user->hasRole($requiredRole)) {
                    // Remove the 'roles' key before adding to final array
                    unset($item['roles']);
                    $filteredItems[] = $item;
                    break; // Found a matching role, no need to check others
                }
            }
        }

        return $filteredItems;
    }

    public function getCurrentUser()
    {
        return Auth::user();
    }

    public function getUserRoleDisplay()
    {
        $user = Auth::user();
        if (!$user) {
            return 'Guest';
        }
        
        if ($user->hasRole('admin')) {
            return 'Administrator';
        } elseif ($user->hasRole('vendor')) {
            return 'Vendor';
        }
        
        return 'User';
    }

    public function isActiveRoute(string $routeName): bool
    {
        return request()->routeIs($routeName) || request()->routeIs($routeName . '.*');
    }

    public function render()
    {
        return view('livewire.admin.dashboard.sidebar', [
            'navigationItems' => $this->getNavigationItems(),
            'currentUser' => $this->getCurrentUser(),
            'userRoleDisplay' => $this->getUserRoleDisplay()
        ]);
    }
}