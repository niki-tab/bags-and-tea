<?php

namespace App\Listeners;

use App\Events\NewOrderCreated;
use App\Mail\OrderConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewOrderCreated $event): void
    {
        try {
            Log::info('Processing NewOrderCreated event for order: ' . $event->orderNumber);
            
            // Get the order to find customer email
            $order = DB::table('orders')->where('order_number', $event->orderNumber)->first();
            
            if (!$order) {
                Log::error('Order not found when trying to send confirmation email: ' . $event->orderNumber);
                return;
            }
            
            if (!$order->customer_email) {
                Log::error('Customer email not found for order: ' . $event->orderNumber);
                return;
            }
            
            Log::info('Sending order confirmation email to: ' . $order->customer_email);
            
            // Send the order confirmation email with BCC
            Mail::to($order->customer_email)
                ->bcc('nicolas.tabares.tech@gmail.com')
                ->send(new OrderConfirmation($event->orderNumber));
            
            Log::info('Order confirmation email sent successfully for order: ' . $event->orderNumber);
            
        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email for order ' . $event->orderNumber . ': ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw the exception to mark the job as failed for retry
            throw $e;
        }
    }
}
