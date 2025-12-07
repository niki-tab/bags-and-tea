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
                'name' => 'Blog Categories',
                'route' => 'admin.blog.categories',
                'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
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
                'name' => 'Shipping',
                'route' => 'admin.shipping',
                'icon' => 'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z',
                'roles' => ['admin'] // Only admin can see
            ],
            [
                'name' => 'Marketplace Fees',
                'route' => 'admin.marketplace-fees',
                'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                'roles' => ['admin'] // Only admin can see
            ],
            [
                'name' => 'Form Submissions',
                'route' => 'admin.form-submissions',
                'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'roles' => ['admin'] // Only admin can see
            ],
            [
                'name' => 'Bag Search Queries',
                'route' => 'admin.bag-search-queries',
                'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7',
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
        // Special handling for blog routes to prevent conflicts
        $currentRoute = request()->route()->getName();
        
        // If we're on a blog categories route, only highlight blog categories
        if (str_contains($currentRoute, 'blog.categories')) {
            return $routeName === 'admin.blog.categories';
        }
        
        // If we're on a blog articles route, only highlight blog (not blog categories)
        if (str_contains($currentRoute, 'blog') && !str_contains($currentRoute, 'blog.categories')) {
            return $routeName === 'admin.blog';
        }
        
        // Default behavior for other routes
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