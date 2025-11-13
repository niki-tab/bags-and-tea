<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $orderNumber;
    public $customerName;
    public $orderData;
    public $locale;

    /**
     * Create a new message instance.
     */
    public function __construct($orderNumber, $forceLocale = null)
    {
        $this->orderNumber = $orderNumber;
        $this->loadOrderData($forceLocale);
    }

    /**
     * Load order data from database
     */
    private function loadOrderData($forceLocale = null)
    {
        // Fetch order data from database
        $order = \DB::table('orders')->where('order_number', $this->orderNumber)->first();
        
        if (!$order) {
            // Fallback for missing orders
            $this->customerName = 'Valued Customer';
            $this->orderData = null;
            $this->locale = $forceLocale ?: 'en';
            return;
        }
        
        $this->customerName = $order->customer_name;
        
        // Determine locale - use forced locale first, then check user preference, then check browser language from order, then default to English
        if ($forceLocale) {
            $this->locale = $forceLocale;
        } else {
            // First try to get user's preferred locale
            $user = \DB::table('users')->where('email', $order->customer_email)->first();
            if ($user && isset($user->locale)) {
                $this->locale = $user->locale;
            } else {
                // If no user or no locale preference, try to detect from browser language or default to Spanish for ES addresses
                if (isset($order->shipping_address)) {
                    $shippingAddress = json_decode($order->shipping_address, true);
                    if (isset($shippingAddress['country']) && $shippingAddress['country'] === 'ES') {
                        $this->locale = 'es'; // Default Spanish for Spain addresses
                    } else {
                        $this->locale = 'en'; // Default English for other countries
                    }
                } else {
                    $this->locale = 'en'; // Ultimate fallback
                }
            }
        }
        
        // Get suborders and items
        $suborders = \DB::table('suborders')->where('order_id', $order->id)->get();
        $orderItems = [];
        
        foreach ($suborders as $suborder) {
            $items = \DB::table('order_items')->where('suborder_id', $suborder->id)->get();
            foreach ($items as $item) {
                // Get primary product image
                $primaryImage = \DB::table('product_media')
                    ->where('product_id', $item->product_id)
                    ->where('is_primary', 1)
                    ->first();

                $orderItems[] = [
                    'product_id' => $item->product_id,
                    'product_name' => json_decode($item->product_name, true),
                    'product_sku' => $item->product_sku,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                    'product_image' => $primaryImage ? $primaryImage->file_path : null,
                    'product_snapshot' => json_decode($item->product_snapshot, true),
                ];
            }
        }
        
        // Prepare order data for email
        $this->orderData = [
            'items' => $orderItems,
            'subtotal' => $order->subtotal,
            'total_fees' => $order->total_fees,
            'shipping_amount' => $order->shipping_amount,
            'tax_amount' => $order->tax_amount,
            'total_amount' => $order->total_amount,
            'shipping_address' => $this->formatAddressForEmail(json_decode($order->shipping_address, true)),
            'billing_address' => $this->formatAddressForEmail(json_decode($order->billing_address, true)),
            'order_id' => $order->id, // Full UUID for backend verification
            'security_token' => substr($order->id, 0, 8), // First 8 chars as security token
        ];
    }

    /**
     * Format address for email display, converting country codes to full names
     */
    private function formatAddressForEmail($address)
    {
        if (!$address) return null;

        // Country mapping (same as in checkout.php translations)
        $countries = [
            'AD' => ['en' => 'Andorra', 'es' => 'Andorra'],
            'AL' => ['en' => 'Albania', 'es' => 'Albania'],
            'AT' => ['en' => 'Austria', 'es' => 'Austria'],
            'BA' => ['en' => 'Bosnia and Herzegovina', 'es' => 'Bosnia y Herzegovina'],
            'BE' => ['en' => 'Belgium', 'es' => 'Bélgica'],
            'BG' => ['en' => 'Bulgaria', 'es' => 'Bulgaria'],
            'BY' => ['en' => 'Belarus', 'es' => 'Bielorrusia'],
            'CH' => ['en' => 'Switzerland', 'es' => 'Suiza'],
            'CY' => ['en' => 'Cyprus', 'es' => 'Chipre'],
            'CZ' => ['en' => 'Czech Republic', 'es' => 'República Checa'],
            'DE' => ['en' => 'Germany', 'es' => 'Alemania'],
            'DK' => ['en' => 'Denmark', 'es' => 'Dinamarca'],
            'EE' => ['en' => 'Estonia', 'es' => 'Estonia'],
            'ES' => ['en' => 'Spain', 'es' => 'España'],
            'FI' => ['en' => 'Finland', 'es' => 'Finlandia'],
            'FO' => ['en' => 'Faroe Islands', 'es' => 'Islas Feroe'],
            'FR' => ['en' => 'France', 'es' => 'Francia'],
            'GB' => ['en' => 'United Kingdom', 'es' => 'Reino Unido'],
            'GI' => ['en' => 'Gibraltar', 'es' => 'Gibraltar'],
            'GL' => ['en' => 'Greenland', 'es' => 'Groenlandia'],
            'GR' => ['en' => 'Greece', 'es' => 'Grecia'],
            'HR' => ['en' => 'Croatia', 'es' => 'Croacia'],
            'HU' => ['en' => 'Hungary', 'es' => 'Hungría'],
            'IE' => ['en' => 'Ireland', 'es' => 'Irlanda'],
            'IS' => ['en' => 'Iceland', 'es' => 'Islandia'],
            'IT' => ['en' => 'Italy', 'es' => 'Italia'],
            'LI' => ['en' => 'Liechtenstein', 'es' => 'Liechtenstein'],
            'LT' => ['en' => 'Lithuania', 'es' => 'Lituania'],
            'LU' => ['en' => 'Luxembourg', 'es' => 'Luxemburgo'],
            'LV' => ['en' => 'Latvia', 'es' => 'Letonia'],
            'MC' => ['en' => 'Monaco', 'es' => 'Mónaco'],
            'MD' => ['en' => 'Moldova', 'es' => 'Moldavia'],
            'ME' => ['en' => 'Montenegro', 'es' => 'Montenegro'],
            'MK' => ['en' => 'North Macedonia', 'es' => 'Macedonia del Norte'],
            'MT' => ['en' => 'Malta', 'es' => 'Malta'],
            'NL' => ['en' => 'Netherlands', 'es' => 'Países Bajos'],
            'NO' => ['en' => 'Norway', 'es' => 'Noruega'],
            'PL' => ['en' => 'Poland', 'es' => 'Polonia'],
            'PT' => ['en' => 'Portugal', 'es' => 'Portugal'],
            'RO' => ['en' => 'Romania', 'es' => 'Rumania'],
            'RS' => ['en' => 'Serbia', 'es' => 'Serbia'],
            'RU' => ['en' => 'Russia', 'es' => 'Rusia'],
            'SE' => ['en' => 'Sweden', 'es' => 'Suecia'],
            'SI' => ['en' => 'Slovenia', 'es' => 'Eslovenia'],
            'SK' => ['en' => 'Slovakia', 'es' => 'Eslovaquia'],
            'SM' => ['en' => 'San Marino', 'es' => 'San Marino'],
            'UA' => ['en' => 'Ukraine', 'es' => 'Ucrania'],
            'VA' => ['en' => 'Vatican City', 'es' => 'Ciudad del Vaticano'],
            'US' => ['en' => 'United States', 'es' => 'Estados Unidos'],
            'CA' => ['en' => 'Canada', 'es' => 'Canadá'],
            'AU' => ['en' => 'Australia', 'es' => 'Australia'],
            'JP' => ['en' => 'Japan', 'es' => 'Japón'],
            'BR' => ['en' => 'Brazil', 'es' => 'Brasil'],
            'MX' => ['en' => 'Mexico', 'es' => 'México'],
            'AR' => ['en' => 'Argentina', 'es' => 'Argentina'],
            'CL' => ['en' => 'Chile', 'es' => 'Chile'],
            'CO' => ['en' => 'Colombia', 'es' => 'Colombia'],
            'PE' => ['en' => 'Peru', 'es' => 'Perú'],
            'CN' => ['en' => 'China', 'es' => 'China'],
            'IN' => ['en' => 'India', 'es' => 'India'],
            'KR' => ['en' => 'South Korea', 'es' => 'Corea del Sur'],
            'TH' => ['en' => 'Thailand', 'es' => 'Tailandia'],
            'SG' => ['en' => 'Singapore', 'es' => 'Singapur'],
            'MY' => ['en' => 'Malaysia', 'es' => 'Malasia'],
            'ID' => ['en' => 'Indonesia', 'es' => 'Indonesia'],
            'PH' => ['en' => 'Philippines', 'es' => 'Filipinas'],
            'VN' => ['en' => 'Vietnam', 'es' => 'Vietnam'],
            'ZA' => ['en' => 'South Africa', 'es' => 'Sudáfrica'],
            'EG' => ['en' => 'Egypt', 'es' => 'Egipto'],
            'MA' => ['en' => 'Morocco', 'es' => 'Marruecos'],
            'NG' => ['en' => 'Nigeria', 'es' => 'Nigeria'],
            'KE' => ['en' => 'Kenya', 'es' => 'Kenia'],
            'NZ' => ['en' => 'New Zealand', 'es' => 'Nueva Zelanda'],
        ];

        // Convert country code to full name
        if (isset($address['country']) && isset($countries[$address['country']])) {
            $address['country_name'] = $countries[$address['country']][$this->locale] ?? $countries[$address['country']]['en'];
        } else {
            $address['country_name'] = $address['country'] ?? '';
        }

        return $address;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Set the locale for translation
        app()->setLocale($this->locale);
        
        return new Envelope(
            subject: trans('emails.order_confirmation.subject', ['orderNumber' => $this->orderNumber]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Set the locale for this email
        app()->setLocale($this->locale);
        
        return new Content(
            view: 'emails.orders.order.order-confirmation',
            with: [
                'orderNumber' => $this->orderNumber,
                'customerName' => $this->customerName,
                'orderData' => $this->orderData,
                'locale' => $this->locale,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
