<?php

declare(strict_types=1);

namespace Src\Products\Certificates\Infrastructure\PDF;

use Src\Products\Certificates\Infrastructure\Eloquent\CertificateEloquentModel;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

final class CertificatePDFGenerator
{
    /**
     * Generate PDF for a certificate
     *
     * @return string Path to the generated PDF file
     */
    public function generate(CertificateEloquentModel $certificate, string $locale = 'en'): string
    {
        // Set the application locale for translations
        $previousLocale = app()->getLocale();
        app()->setLocale($locale);

        // Generate QR code as SVG (no extension required)
        $qrCodeSvg = QrCode::format('svg')
            ->size(120)
            ->margin(0)
            ->errorCorrection('H')
            ->generate($certificate->product_qr_url);

        $qrCode = base64_encode((string) $qrCodeSvg);

        // Get product snapshot data
        $snapshot = $certificate->product_snapshot;

        // Get primary image from product_media table
        $primaryImage = $this->getPrimaryProductImage($certificate->product_id);

        // Get brand name from brands table
        $brandName = $this->getBrandName($certificate->product_id, $locale);

        // Prepare data for the view
        $data = [
            'certificate' => $certificate,
            'qrCode' => $qrCode,
            'productName' => $this->getTranslatedValue($snapshot['name'] ?? 'N/A', $locale),
            'productImage' => $primaryImage,
            'brandName' => $brandName,
            'sku' => is_array($snapshot['sku'] ?? null) ? 'N/A' : ($snapshot['sku'] ?? 'N/A'),
            'qualityName' => $this->extractName($snapshot['quality'] ?? null, $locale),
            'categories' => $snapshot['categories'] ?? [],
            'locale' => $locale,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('pdfs.authentication-certificate', $data)
            ->setPaper('a4', 'portrait')
            ->setOption('enable_remote', true)
            ->setOption('enable_php', true);

        // Store PDF temporarily
        $filename = 'certificate-' . $certificate->certificate_number . '.pdf';
        $path = 'certificates/temp/' . $filename;
        Storage::disk('local')->put($path, $pdf->output());

        // Restore previous locale
        app()->setLocale($previousLocale);

        return storage_path('app/' . $path);
    }

    /**
     * Get translated value from array or string
     */
    private function getTranslatedValue($value, string $locale = null): string
    {
        if (!$locale) {
            $locale = app()->getLocale();
        }

        if (is_array($value)) {
            return $value[$locale] ?? $value['en'] ?? $value[array_key_first($value)] ?? 'N/A';
        }

        return $value ?? 'N/A';
    }

    /**
     * Extract name from object/array (handles brand, quality, etc.)
     */
    private function extractName($item, string $locale = null): string
    {
        if (!$item) {
            return 'N/A';
        }

        // If it's already a string, return it
        if (is_string($item)) {
            return $item;
        }

        // If it's an array or object, try to get the 'name' field
        if (is_array($item)) {
            if (isset($item['name'])) {
                return $this->getTranslatedValue($item['name'], $locale);
            }
        }

        return 'N/A';
    }

    /**
     * Get primary product image from product_media table
     */
    private function getPrimaryProductImage(string $productId): ?string
    {
        $media = DB::table('product_media')
            ->where('product_id', $productId)
            ->where('is_primary', true)
            ->first();

        return $media ? $media->file_path : null;
    }

    /**
     * Get brand name from brands table
     */
    private function getBrandName(string $productId, string $locale): string
    {
        // Get brand_id from products table
        $product = DB::table('products')
            ->where('id', $productId)
            ->first();

        if (!$product || !$product->brand_id) {
            return 'N/A';
        }

        // Get brand from brands table
        $brand = DB::table('brands')
            ->where('id', $product->brand_id)
            ->first();

        if (!$brand || !$brand->name) {
            return 'N/A';
        }

        // Parse JSON name field
        $names = json_decode($brand->name, true);

        if (!is_array($names)) {
            return 'N/A';
        }

        // Return name in requested locale, fallback to English, then first available
        if (isset($names[$locale])) {
            return $names[$locale];
        }

        if (isset($names['en'])) {
            return $names['en'];
        }

        return reset($names) ?: 'N/A';
    }
}
