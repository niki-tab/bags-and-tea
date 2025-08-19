<?php

namespace Src\Admin\Order\Order\Frontend;

use Livewire\Component;
use Livewire\WithPagination;
use Src\Order\Infrastructure\EloquentOrderRepository;
use Src\Order\Infrastructure\Eloquent\OrderEloquentModel;
use Src\Categories\Infrastructure\Eloquent\CategoryEloquentModel;
use Src\Blog\Categories\Infrastructure\Eloquent\BlogCategoryEloquentModel;

class ShowAllOrder extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'order_number';
    public $sortDirection = 'desc';
    public $perPage = 15;
    public $currentPage = 1;

    protected $paginationTheme = 'bootstrap';
    
    protected $listeners = ['pageChanged' => 'handlePageChanged'];

    public function updatingSearch()
    {
        $this->resetPage();
        $this->currentPage = 1;
    }

    public function updatedSortBy()
    {
        $this->resetPage();
        $this->currentPage = 1;
    }

    public function updatedSortDirection()
    {
        $this->resetPage();
        $this->currentPage = 1;
    }
    
    public function handlePageChanged($page)
    {
        $this->currentPage = $page;
        $this->setPage($page);
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
        
        $this->resetPage();
        $this->currentPage = 1;
    }

    public function toggleActive($categoryId)
    {
        $category = BlogCategoryEloquentModel::find($categoryId);
        if ($category) {
            $category->is_active = !$category->is_active;
            $category->save();
        }
    }

    public function render()
    {
        $this->currentPage = $this->getPage();
        
        $orderRepository = new EloquentOrderRepository();
        $orders = $orderRepository->findAll();

        $cleanedOrders = array();
        

        foreach($orders as $order){

            $cleanedOrdersAux = array();
            
            $orderNumber = $order["order_number"];

            $orderStatus = $order["status"];
            $orderCustomerEmail = $order["customer_email"];
            $orderSubtotal= $order["subtotal"];
            $orderTotalDiscounts= $order["total_discounts"];
            $orderTotalFees= $order["total_fees"];
            $orderShippingAmount= $order["shipping_amount"];
            $orderShippingPromotionId= $order["shipping_promotion_id"];
            $orderTaxAmount= $order["tax_amount"];
            $orderTotalAmount= $order["total_amount"];
            $orderCreatedAt= $order["created_at"];

            $cleanedOrdersAux["status"] = $orderStatus;
            $cleanedOrdersAux["customerEmail"] = $orderCustomerEmail;
            $cleanedOrdersAux["subtotal"] = $orderSubtotal;
            $cleanedOrdersAux["totalDiscounts"] = $orderTotalDiscounts;
            $cleanedOrdersAux["totalFees"] = $orderTotalFees;
            $cleanedOrdersAux["shippingAmount"] = $orderShippingAmount;
            $cleanedOrdersAux["shippingPromotionId"] = $orderShippingPromotionId;
            $cleanedOrdersAux["taxAmount"] = $orderTaxAmount;
            $cleanedOrdersAux["totalAmount"] = $orderTotalAmount;
            $cleanedOrdersAux["createdAt"] = $orderCreatedAt;

            $cleanedOrders[$orderNumber] = $cleanedOrdersAux;

        }
      

        $orders = OrderEloquentModel::query()
            ->when($this->search, function ($query) {
                $query->where('order_number', 'like', '%' . $this->search . '%')
                      ->orWhere('customer_email', 'like', '%' . $this->search . '%');
            })
            ->when($this->sortBy === 'order_number', function ($query) {
                $query->orderBy('order_number', $this->sortDirection);
            })
            ->when($this->sortBy === 'customer_email', function ($query) {
                $query->orderBy('customer_email', $this->sortDirection);
            })
            ->when($this->sortBy === 'total_amount', function ($query) {
                $query->orderBy('total_amount', $this->sortDirection);
            })
            ->when($this->sortBy === 'created_at', function ($query) {
                $query->orderBy('created_at', $this->sortDirection);
            })
            /*->when($this->sortBy === 'parent_id', function ($query) {
                $query->leftJoin('categories as parent_categories', 'categories.parent_id', '=', 'parent_categories.id')
                      ->orderBy('parent_categories.name->en', $this->sortDirection)
                      ->select('categories.*');
            })
            ->when($this->sortBy === 'is_active', function ($query) {
                $query->orderBy('is_active', $this->sortDirection);
            })
            ->when($this->sortBy === 'display_order', function ($query) {
                $query->orderBy('display_order', $this->sortDirection);
            })
            ->when($this->sortBy === 'created_at', function ($query) {
                $query->orderBy('created_at', $this->sortDirection);
            })*/
            ->paginate($this->perPage);

        return view('livewire.admin.orders.order.show-all', [
            'orders' => $orders
        ]);
    }
}