<?php

namespace App\Livewire\Admin\Orders;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Src\Products\Certificates\Application\GenerateAndSendCertificate;
use Src\Products\Certificates\Infrastructure\EloquentCertificateRepository;
use Src\Products\Certificates\Infrastructure\PDF\CertificatePDFGenerator;
use Src\Products\Product\Infrastructure\EloquentProductRepository;

class SendCertificate extends Component
{
    public $order;
    public $showModal = false;
    public $selectedProduct = null;
    public $products = [];

    public function mount($order)
    {
        $this->order = $order;
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = [];

        if (isset($this->order->suborders) && is_array($this->order->suborders)) {
            foreach ($this->order->suborders as $suborder) {
                $items = $suborder->orderItems ?? $suborder->order_items ?? [];

                if (is_object($items)) {
                    $items = (array) $items;
                }

                foreach ($items as $item) {
                    $itemObj = is_object($item) ? $item : (object) $item;

                    // Get product snapshot
                    if (is_string($itemObj->product_snapshot ?? null)) {
                        $snapshot = json_decode($itemObj->product_snapshot, true);
                    } elseif (is_object($itemObj->product_snapshot ?? null) || is_array($itemObj->product_snapshot ?? null)) {
                        $snapshot = json_decode(json_encode($itemObj->product_snapshot), true);
                    } else {
                        $snapshot = $itemObj->product_snapshot ?? [];
                    }

                    // Get product name
                    $productName = 'N/A';
                    if (isset($snapshot['name'])) {
                        if (is_array($snapshot['name'])) {
                            $productName = $snapshot['name']['en'] ?? $snapshot['name'][array_key_first($snapshot['name'])] ?? 'N/A';
                        } elseif (is_string($snapshot['name'])) {
                            $productName = $snapshot['name'];
                        }
                    }

                    $this->products[] = [
                        'item_id' => $itemObj->id ?? null,
                        'product_id' => $itemObj->product_id ?? $snapshot['id'] ?? null,
                        'name' => $productName,
                        'snapshot' => $snapshot,
                    ];
                }
            }
        }
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProduct = null;
    }

    public function sendCertificate($productIndex, $locale = 'en')
    {
        try {
            if (!isset($this->products[$productIndex])) {
                session()->flash('error', 'Product not found.');
                return;
            }

            $product = $this->products[$productIndex];

            if (!$product['item_id'] || !$product['product_id']) {
                session()->flash('error', 'Invalid product data.');
                return;
            }

            // Get product slug - try snapshot first, then database
            $productSlug = $this->getProductSlug($product, $locale);
            if (!$productSlug) {
                session()->flash('error', 'Product slug not found for SKU: ' . ($product['snapshot']['sku'] ?? 'N/A'));
                return;
            }

            $productUrl = route('product.show.' . $locale, ['locale' => $locale, 'productSlug' => $productSlug]);

            // Prepare data for certificate generation
            $certificateData = [
                'order_number' => $this->order->order_number,
                'order_item_id' => $product['item_id'],
                'product_id' => $product['product_id'],
                'customer_name' => $this->order->customer_name ?? $this->order->billing_name,
                'customer_email' => $this->order->customer_email ?? $this->order->billing_email,
                'product_url' => $productUrl,
                'product_snapshot' => $product['snapshot'],
                'sent_by_user_id' => Auth::id(),
                'locale' => $locale,
            ];

            // Execute use case
            $useCase = new GenerateAndSendCertificate(
                new EloquentCertificateRepository(),
                new CertificatePDFGenerator()
            );

            $certificate = $useCase->execute($certificateData);

            $lang = $locale === 'es' ? 'Spanish' : 'English';
            session()->flash('success', "Authentication certificate ({$lang}) sent successfully to " . $certificateData['customer_email']);

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Error sending certificate: ' . $e->getMessage());
        }
    }

    public function sendTestCertificate($productIndex, $locale = 'en')
    {
        try {
            if (!isset($this->products[$productIndex])) {
                session()->flash('error', 'Product not found.');
                return;
            }

            $product = $this->products[$productIndex];

            if (!$product['item_id'] || !$product['product_id']) {
                session()->flash('error', 'Invalid product data.');
                return;
            }

            // Get product slug - try snapshot first, then database
            $productSlug = $this->getProductSlug($product, $locale);
            if (!$productSlug) {
                session()->flash('error', 'Product slug not found for SKU: ' . ($product['snapshot']['sku'] ?? 'N/A'));
                return;
            }

            $productUrl = route('product.show.' . $locale, ['locale' => $locale, 'productSlug' => $productSlug]);

            // Prepare data for certificate generation (send to test email)
            $certificateData = [
                'order_number' => $this->order->order_number,
                'order_item_id' => $product['item_id'],
                'product_id' => $product['product_id'],
                'customer_name' => $this->order->customer_name ?? $this->order->billing_name,
                'customer_email' => 'nicolas.tabares.tech@gmail.com', // Override with test email
                'product_url' => $productUrl,
                'product_snapshot' => $product['snapshot'],
                'sent_by_user_id' => Auth::id(),
                'locale' => $locale,
            ];

            // Execute use case
            $useCase = new GenerateAndSendCertificate(
                new EloquentCertificateRepository(),
                new CertificatePDFGenerator()
            );

            $certificate = $useCase->execute($certificateData);

            $lang = $locale === 'es' ? 'Spanish' : 'English';
            session()->flash('success', "Test certificate ({$lang}) sent successfully to nicolas.tabares.tech@gmail.com");

            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Error sending test certificate: ' . $e->getMessage());
        }
    }

    /**
     * Get product slug from snapshot or database
     */
    private function getProductSlug(array $product, string $locale = 'en'): ?string
    {
        // Try to get from snapshot first
        if (isset($product['snapshot']['slug'])) {
            $slug = $product['snapshot']['slug'];

            // Handle translatable slug (array)
            if (is_array($slug)) {
                return $slug[$locale] ?? $slug['en'] ?? $slug[array_key_first($slug)] ?? null;
            }

            // Handle simple string slug
            if (is_string($slug)) {
                return $slug;
            }
        }

        // If not in snapshot, fetch from database
        if ($product['product_id']) {
            $productRepository = new EloquentProductRepository();
            $dbProduct = $productRepository->search($product['product_id']);

            if ($dbProduct) {
                // Use getTranslation for translatable fields
                return $dbProduct->getTranslation('slug', $locale) ?? $dbProduct->slug;
            }
        }

        return null;
    }

    public function render()
    {
        return view('livewire.admin.orders.send-certificate');
    }
}
