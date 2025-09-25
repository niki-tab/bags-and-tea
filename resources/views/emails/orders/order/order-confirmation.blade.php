@extends('emails.layouts.base')

@section('title', trans('emails.order_confirmation.title') . ' - ' . $orderNumber)

@section('email-title', trans('emails.order_confirmation.title'))

@section('email-reason', trans('emails.order_confirmation.reason'))

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 30px 20px;">
    <tr>
        <td>
            <!-- Welcome Message -->
            <h3 style="margin: 0 0 20px 0; color: #482626; font-size: 20px; font-weight: bold; font-family: 'Arial', sans-serif;">
                {!! trans('emails.order_confirmation.hello', ['name' => $customerName]) !!}
            </h3>p
            
            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ trans('emails.order_confirmation.thank_you') }}
            </p>
            
            <!-- Order Number -->
            <div style="background-color: #F8F3F0; padding: 20px; margin: 20px 0; border-left: 4px solid #482626;">
                <p style="margin: 0; color: #482626; font-size: 16px; font-family: 'Arial', sans-serif;">
                    <strong>{{ trans('emails.order_confirmation.order_number') }}:</strong> {{ $orderNumber }}<br>
                    <strong>{{ trans('emails.order_confirmation.order_date') }}:</strong> {{ date('F j, Y') }}
                </p>
            </div>
            
            <!-- Order Details -->
            @if($orderData)
            <div style="background-color: #ffffff; border: 1px solid #E5E7EB; margin: 30px 0;">
                <h4 style="margin: 0; padding: 20px; background-color: #F9FAFB; color: #482626; font-size: 18px; font-weight: bold; font-family: 'Arial', sans-serif; border-bottom: 1px solid #E5E7EB;">
                    {{ trans('emails.order_confirmation.order_details') }}
                </h4>
                
                <!-- Order Items -->
                <div style="padding: 20px;">
                    @foreach($orderData['items'] as $item)
                    <div style="display: table; width: 100%; border-bottom: 1px solid #F3F4F6; padding: 15px 0;">
                        <!-- Product Image -->
                        <div style="display: table-cell; vertical-align: middle; width: 80px; padding-right: 15px;">
                            @if(isset($item['product_image']) && $item['product_image'])
                            <img src="{{ url($item['product_image']) }}" 
                                 alt="{{ is_array($item['product_name']) ? ($item['product_name']['en'] ?? $item['product_name']['es'] ?? 'Product') : $item['product_name'] }}"
                                 width="60" 
                                 height="60"
                                 style="border-radius: 8px; object-fit: cover; display: block; border: 1px solid #E5E7EB;">
                            @else
                            <div style="width: 60px; height: 60px; background-color: #F3F4F6; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid #E5E7EB;">
                                <span style="color: #9CA3AF; font-size: 12px; font-family: 'Arial', sans-serif;">No Image</span>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div style="display: table-cell; vertical-align: middle; width: calc(100% - 200px);">
                            <div style="color: #482626; font-size: 16px; font-weight: bold; margin-bottom: 5px; font-family: 'Arial', sans-serif;">
                                @php
                                    $productName = is_array($item['product_name']) 
                                        ? ($item['product_name']['en'] ?? $item['product_name']['es'] ?? 'Product')
                                        : $item['product_name'];
                                @endphp
                                {{ $productName }}
                            </div>
                            <div style="color: #6B7280; font-size: 14px; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.quantity') }}: {{ $item['quantity'] }}
                            </div>
                            @if(isset($item['product_sku']) && $item['product_sku'])
                            <div style="color: #6B7280; font-size: 12px; font-family: 'Arial', sans-serif;">
                                SKU: {{ $item['product_sku'] }}
                            </div>
                            @endif
                        </div>
                        <div style="display: table-cell; vertical-align: middle; width: 20%; text-align: right;">
                            <div style="color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                                €{{ number_format($item['unit_price'], 2, ',', '.') }}
                            </div>
                        </div>
                        <div style="display: table-cell; vertical-align: middle; width: 20%; text-align: right;">
                            <div style="color: #482626; font-size: 16px; font-weight: bold; font-family: 'Arial', sans-serif;">
                                €{{ number_format($item['total_price'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Order Totals -->
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #E5E7EB;">
                        <div style="display: table; width: 100%; margin-bottom: 8px;">
                            <div style="display: table-cell; text-align: left; color: #6B7280; font-size: 14px; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.subtotal') }}:
                            </div>
                            <div style="display: table-cell; text-align: right; color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                                €{{ number_format($orderData['subtotal'], 2, ',', '.') }}
                            </div>
                        </div>
                        
                        @if($orderData['total_fees'] > 0)
                        <div style="display: table; width: 100%; margin-bottom: 8px;">
                            <div style="display: table-cell; text-align: left; color: #6B7280; font-size: 14px; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.fees') }}:
                            </div>
                            <div style="display: table-cell; text-align: right; color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                                €{{ number_format($orderData['total_fees'], 2, ',', '.') }}
                            </div>
                        </div>
                        @endif
                        
                        @if($orderData['shipping_amount'] > 0)
                        <div style="display: table; width: 100%; margin-bottom: 8px;">
                            <div style="display: table-cell; text-align: left; color: #6B7280; font-size: 14px; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.shipping') }}:
                            </div>
                            <div style="display: table-cell; text-align: right; color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                                €{{ number_format($orderData['shipping_amount'], 2, ',', '.') }}
                            </div>
                        </div>
                        @endif
                        
                        @if($orderData['tax_amount'] > 0)
                        <div style="display: table; width: 100%; margin-bottom: 8px;">
                            <div style="display: table-cell; text-align: left; color: #6B7280; font-size: 14px; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.tax') }}:
                            </div>
                            <div style="display: table-cell; text-align: right; color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                                €{{ number_format($orderData['tax_amount'], 2, ',', '.') }}
                            </div>
                        </div>
                        @endif
                        
                        <div style="display: table; width: 100%; margin-top: 15px; padding-top: 15px; border-top: 1px solid #E5E7EB;">
                            <div style="display: table-cell; text-align: left; color: #482626; font-size: 16px; font-weight: bold; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.total') }}:
                            </div>
                            <div style="display: table-cell; text-align: right; color: #482626; font-size: 16px; font-weight: bold; font-family: 'Arial', sans-serif;">
                                €{{ number_format($orderData['total_amount'], 2, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Addresses Section -->
                @if($orderData['shipping_address'] || $orderData['billing_address'])
                <div style="border-top: 1px solid #E5E7EB; background-color: #F9FAFB;">
                    <div style="display: table; width: 100%;">
                        <!-- Shipping Address -->
                        @if($orderData['shipping_address'])
                        <div style="display: table-cell; width: 50%; padding: 20px; vertical-align: top;">
                            <h5 style="margin: 0 0 10px 0; color: #482626; font-size: 16px; font-weight: bold; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.shipping_address') }}
                            </h5>
                            <div style="color: #482626; font-size: 14px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                                {{ $orderData['shipping_address']['first_name'] ?? '' }} {{ $orderData['shipping_address']['last_name'] ?? '' }}<br>
                                {{ $orderData['shipping_address']['line1'] ?? '' }}<br>
                                @if(!empty($orderData['shipping_address']['line2']))
                                {{ $orderData['shipping_address']['line2'] }}<br>
                                @endif
                                {{ $orderData['shipping_address']['postal_code'] ?? '' }} {{ $orderData['shipping_address']['city'] ?? '' }}<br>
                                @if(!empty($orderData['shipping_address']['state']))
                                {{ $orderData['shipping_address']['state'] }}<br>
                                @endif
                                {{ $orderData['shipping_address']['country_name'] ?? $orderData['shipping_address']['country'] ?? '' }}
                            </div>
                        </div>
                        @endif
                        
                        <!-- Billing Address -->
                        @if($orderData['billing_address'])
                        <div style="display: table-cell; width: 50%; padding: 20px; vertical-align: top; @if($orderData['shipping_address'])border-left: 1px solid #E5E7EB;@endif">
                            <h5 style="margin: 0 0 10px 0; color: #482626; font-size: 16px; font-weight: bold; font-family: 'Arial', sans-serif;">
                                {{ trans('emails.order_confirmation.billing_address') }}
                            </h5>
                            <div style="color: #482626; font-size: 14px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                                {{ $orderData['billing_address']['first_name'] ?? '' }} {{ $orderData['billing_address']['last_name'] ?? '' }}<br>
                                {{ $orderData['billing_address']['line1'] ?? '' }}<br>
                                @if(!empty($orderData['billing_address']['line2']))
                                {{ $orderData['billing_address']['line2'] }}<br>
                                @endif
                                {{ $orderData['billing_address']['postal_code'] ?? '' }} {{ $orderData['billing_address']['city'] ?? '' }}<br>
                                @if(!empty($orderData['billing_address']['state']))
                                {{ $orderData['billing_address']['state'] }}<br>
                                @endif
                                {{ $orderData['billing_address']['country_name'] ?? $orderData['billing_address']['country'] ?? '' }}
                                @if(!empty($orderData['billing_address']['vat_id']))
                                <br><strong>VAT ID:</strong> {{ $orderData['billing_address']['vat_id'] }}
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @else
            <!-- Fallback for when no order data is provided -->
            <div style="background-color: #ffffff; border: 2px dashed #F8F3F0; padding: 40px 20px; text-align: center; margin: 30px 0;">
                <h4 style="margin: 0 0 10px 0; color: #482626; font-size: 18px; font-family: 'Arial', sans-serif;">
                    Order Details Section
                </h4>
                <p style="margin: 0; color: #666; font-size: 14px; font-style: italic; font-family: 'Arial', sans-serif;">
                    (This is where the order items, pricing, and shipping details will be displayed)
                </p>
            </div>
            @endif
            
            <!-- Next Steps -->
            <h4 style="margin: 30px 0 15px 0; color: #482626; font-size: 18px; font-weight: bold; font-family: 'Arial', sans-serif;">
                {{ trans('emails.order_confirmation.whats_next') }}
            </h4>
            
            <ul style="margin: 0 0 20px 20px; color: #482626; font-size: 14px; line-height: 1.8; font-family: 'Arial', sans-serif;">
                @foreach(trans('emails.order_confirmation.next_steps') as $step)
                <li>{{ $step }}</li>
                @endforeach
            </ul>
            
            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                @php
                    $orderUrl = $locale === 'es'
                        ? route('checkout.success.es', ['locale' => 'es', 'order_number' => $orderNumber]) . '?token=' . $orderData['security_token']
                        : route('checkout.success.en', ['locale' => 'en', 'order_number' => $orderNumber]) . '?token=' . $orderData['security_token'];
                @endphp
                <a href="{{ $orderUrl }}" 
                   style="background-color: #482626; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold; display: inline-block; font-family: 'Arial', sans-serif;">
                    {{ trans('emails.order_confirmation.view_order') }}
                </a>
            </div>
            
            <p style="margin: 20px 0 0 0; color: #482626; font-size: 14px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ trans('emails.order_confirmation.questions') }} 
                <a href="mailto:info@bagsandtea.com" style="color: #482626; text-decoration: underline;">info@bagsandtea.com</a>.
            </p>
            
            <p style="margin: 20px 0 0 0; color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                {{ trans('emails.order_confirmation.thank_you_closing') }}
            </p>
        </td>
    </tr>
</table>
@endsection