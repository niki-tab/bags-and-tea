<?php

namespace Src\Order\Infrastructure;

use Src\Order\Domain\OrderRepository;
use Src\Order\Infrastructure\Eloquent\OrderEloquentModel;
use Src\Order\Infrastructure\Eloquent\SuborderEloquentModel;
use Src\Order\Infrastructure\Eloquent\OrderItemEloquentModel;
use Src\Order\Infrastructure\Eloquent\OrderFeeEloquentModel;
use Src\Vendors\Infrastructure\Eloquent\VendorCommissionEloquentModel;

class EloquentOrderRepository implements OrderRepository
{
    public function createOrder(array $orderData): array
    {
        $order = OrderEloquentModel::create($orderData);
        return $order->toArray();
    }
    
    public function createSuborder(array $suborderData): array
    {
        $suborder = SuborderEloquentModel::create($suborderData);
        return $suborder->toArray();
    }
    
    public function createOrderItem(array $orderItemData): array
    {
        $orderItem = OrderItemEloquentModel::create($orderItemData);
        return $orderItem->toArray();
    }
    
    public function createVendorCommission(array $commissionData): array
    {
        $commission = VendorCommissionEloquentModel::create($commissionData);
        return $commission->toArray();
    }

    public function findAll(): array
    {
        return OrderEloquentModel::with('suborders', 'orderFees')->ordered()->get()->all();

        //return AttributeEloquentModel::with('children')->ordered()->get()->all();
    }
    
    public function retrieveOrderByNumber(string $orderNumber): ?array
    {
        $order = OrderEloquentModel::where('order_number', $orderNumber)
            ->with(['suborders.orderItems', 'orderFees', 'orderItems'])
            ->first();

        return $order ? $order->toArray() : null;
    }
    
    public function retrieveOrderById(string $orderId): ?array
    {
        $order = OrderEloquentModel::with(['suborders.orderItems'])
            ->find($orderId);
            
        return $order ? $order->toArray() : null;
    }
    
    public function retrieveSubordersByOrderId(string $orderId): array
    {
        return SuborderEloquentModel::where('order_id', $orderId)
            ->with(['orderItems'])
            ->get()
            ->toArray();
    }
    
    public function retrieveOrderItemsBySuborderId(string $suborderId): array
    {
        return OrderItemEloquentModel::where('suborder_id', $suborderId)
            ->with('product')
            ->get()
            ->toArray();
    }
    
    public function updateOrderStatus(string $orderId, string $status): bool
    {
        return OrderEloquentModel::where('id', $orderId)
            ->update(['status' => $status]);
    }
    
    public function updateSuborderStatus(string $suborderId, string $status): bool
    {
        return SuborderEloquentModel::where('id', $suborderId)
            ->update(['status' => $status]);
    }
    
    public function updatePaymentStatus(string $orderId, string $paymentStatus, ?string $paymentIntentId = null): bool
    {
        $updateData = ['payment_status' => $paymentStatus];

        if ($paymentIntentId) {
            $updateData['payment_intent_id'] = $paymentIntentId;
        }

        return OrderEloquentModel::where('id', $orderId)->update($updateData);
    }

    public function updateOrder(string $orderId, array $orderData): bool
    {
        return OrderEloquentModel::where('id', $orderId)->update($orderData);
    }

    public function createOrderFee(array $orderFeeData): array
    {
        $orderFee = OrderFeeEloquentModel::create($orderFeeData);
        return $orderFee->toArray();
    }
}