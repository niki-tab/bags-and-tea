<?php

namespace Src\Order\Domain;

interface OrderRepository
{
    public function createOrder(array $orderData): array;
    
    public function createSuborder(array $suborderData): array;
    
    public function createOrderItem(array $orderItemData): array;
    
    public function createVendorCommission(array $commissionData): array;
    
    public function createOrderFee(array $orderFeeData): array;
    
    public function findAll(): array;
    
    public function retrieveOrderByNumber(string $orderNumber): ?array;
    
    public function retrieveOrderById(string $orderId): ?array;
    
    public function retrieveSubordersByOrderId(string $orderId): array;
    
    public function retrieveOrderItemsBySuborderId(string $suborderId): array;
    
    public function updateOrderStatus(string $orderId, string $status): bool;
    
    public function updateSuborderStatus(string $suborderId, string $status): bool;
    
    public function updatePaymentStatus(string $orderId, string $paymentStatus, ?string $paymentIntentId = null): bool;
}