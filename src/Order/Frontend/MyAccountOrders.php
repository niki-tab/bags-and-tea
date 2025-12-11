<?php

namespace Src\Order\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Order\Infrastructure\Eloquent\OrderEloquentModel;
use Illuminate\Support\Facades\Auth;

class MyAccountOrders extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $user = Auth::user();

        $orders = OrderEloquentModel::where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
            })
            ->with(['orderItems'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('livewire.order.my-account-orders', [
            'orders' => $orders,
        ]);
    }

    public function getStatusColor(string $status): string
    {
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getPaymentStatusColor(string $status): string
    {
        return match($status) {
            'paid' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
