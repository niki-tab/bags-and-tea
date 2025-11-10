<?php

declare(strict_types=1);

namespace Src\Products\Certificates\Infrastructure\Eloquent;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CertificateEloquentModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'authentication_certificates';

    protected $fillable = [
        'certificate_number',
        'order_number',
        'order_item_id',
        'product_id',
        'customer_name',
        'customer_email',
        'product_qr_url',
        'product_snapshot',
        'sent_at',
        'sent_by_user_id',
    ];

    protected $casts = [
        'product_snapshot' => 'array',
        'sent_at' => 'datetime',
    ];

    /**
     * Generate a unique certificate number
     */
    public static function generateCertificateNumber(): string
    {
        $year = date('Y');
        $lastCertificate = self::whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCertificate) {
            $lastNumber = (int)substr($lastCertificate->certificate_number, -6);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'CERT-' . $year . '-' . str_pad((string)$newNumber, 6, '0', STR_PAD_LEFT);
    }
}
