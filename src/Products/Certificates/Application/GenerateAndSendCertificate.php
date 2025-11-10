<?php

declare(strict_types=1);

namespace Src\Products\Certificates\Application;

use Src\Products\Certificates\Domain\CertificateRepository;
use Src\Products\Certificates\Infrastructure\Eloquent\CertificateEloquentModel;
use Src\Products\Certificates\Infrastructure\PDF\CertificatePDFGenerator;
use App\Mail\AuthenticationCertificateMail;
use Illuminate\Support\Facades\Mail;

final class GenerateAndSendCertificate
{
    public function __construct(
        private CertificateRepository $certificateRepository,
        private CertificatePDFGenerator $pdfGenerator
    ) {}

    /**
     * Execute the use case to generate and send certificate
     *
     * @param array $data Contains order_number, order_item_id, product_id, customer_name, customer_email, product_url, product_snapshot, sent_by_user_id, locale
     * @return CertificateEloquentModel
     */
    public function execute(array $data): CertificateEloquentModel
    {
        $locale = $data['locale'] ?? 'en';

        // Check if certificate already exists for this order item
        $existingCertificates = $this->certificateRepository->findByOrderItem($data['order_item_id']);

        if (!empty($existingCertificates)) {
            // Use the existing certificate (most recent one)
            $certificate = $existingCertificates[0];
        } else {
            // Create new certificate record
            $certificate = new CertificateEloquentModel([
                'certificate_number' => $this->certificateRepository->generateCertificateNumber(),
                'order_number' => $data['order_number'],
                'order_item_id' => $data['order_item_id'],
                'product_id' => $data['product_id'],
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'product_qr_url' => $data['product_url'],
                'product_snapshot' => $data['product_snapshot'],
                'sent_at' => now(),
                'sent_by_user_id' => $data['sent_by_user_id'] ?? null,
            ]);

            $this->certificateRepository->save($certificate);
        }

        // Generate PDF with specified locale
        $pdfPath = $this->pdfGenerator->generate($certificate, $locale);

        // Send email with PDF attachment (use the email from $data in case it's a test email)
        Mail::to($data['customer_email'])
            ->send(new AuthenticationCertificateMail($certificate, $pdfPath, $locale));

        return $certificate;
    }
}
