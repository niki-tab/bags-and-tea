@extends('emails.layouts.base')

@section('title', 'Order Confirmation - ' . $orderNumber)

@section('email-title', 'Order Confirmation')

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 30px 20px;">
    <tr>
        <td>
            <!-- Welcome Message -->
            <h3 style="margin: 0 0 20px 0; color: #482626; font-size: 20px; font-weight: bold; font-family: 'Arial', sans-serif;">
                Hello {{ $customerName }},
            </h3>
            
            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                Thank you for your order! We're excited to confirm that we've received your order and it's being processed.
            </p>
            
            <!-- Order Number -->
            <div style="background-color: #F8F3F0; padding: 20px; margin: 20px 0; border-left: 4px solid #482626;">
                <p style="margin: 0; color: #482626; font-size: 16px; font-family: 'Arial', sans-serif;">
                    <strong>Order Number:</strong> {{ $orderNumber }}<br>
                    <strong>Order Date:</strong> {{ date('F j, Y') }}
                </p>
            </div>
            
            <!-- Placeholder for Order Content -->
            <div style="background-color: #ffffff; border: 2px dashed #F8F3F0; padding: 40px 20px; text-align: center; margin: 30px 0;">
                <h4 style="margin: 0 0 10px 0; color: #482626; font-size: 18px; font-family: 'Arial', sans-serif;">
                    Order Details Section
                </h4>
                <p style="margin: 0; color: #666; font-size: 14px; font-style: italic; font-family: 'Arial', sans-serif;">
                    (This is where the order items, pricing, and shipping details will be displayed)
                </p>
            </div>
            
            <!-- Next Steps -->
            <h4 style="margin: 30px 0 15px 0; color: #482626; font-size: 18px; font-weight: bold; font-family: 'Arial', sans-serif;">
                What's Next?
            </h4>
            
            <ul style="margin: 0 0 20px 20px; color: #482626; font-size: 14px; line-height: 1.8; font-family: 'Arial', sans-serif;">
                <li>We'll prepare your order with care</li>
                <li>You'll receive a shipping confirmation email once your order is on its way</li>
                <li>Track your package using the tracking number we'll provide</li>
            </ul>
            
            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/') }}" 
                   style="background-color: #482626; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold; display: inline-block; font-family: 'Arial', sans-serif;">
                    View Your Order
                </a>
            </div>
            
            <p style="margin: 20px 0 0 0; color: #482626; font-size: 14px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                If you have any questions about your order, please don't hesitate to contact us at 
                <a href="mailto:info@bagsandtea.com" style="color: #482626; text-decoration: underline;">info@bagsandtea.com</a>.
            </p>
            
            <p style="margin: 20px 0 0 0; color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                Thank you for choosing Bags and Tea!
            </p>
        </td>
    </tr>
</table>
@endsection