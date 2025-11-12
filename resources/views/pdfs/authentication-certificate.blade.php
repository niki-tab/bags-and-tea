<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('certificates.title') }}</title>
    <style>
        @page {
            margin: 0;
            size: 210mm 270mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            color: #482626;
            background: #ffffff;
            padding: 0;
            margin: 0;
        }

        .certificate-container {
            width: 130mm;
            margin: 15mm auto;
            border: 4px solid #482626;
            padding: 10mm;
            background: #ffffff;
        }

        /* Header */
        .header {
            text-align: center;
            padding-bottom: 8mm;
            border-bottom: 2px solid #482626;
            margin-bottom: 8mm;
        }

        .logo {
            max-width: 60mm;
            height: auto;
            margin-bottom: 4mm;
        }

        .main-title {
            font-size: 18pt;
            font-weight: bold;
            color: #482626;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 2mm;
            line-height: 1.2;
        }

        .subtitle {
            font-size: 11pt;
            color: #666666;
            letter-spacing: 0.5px;
        }

        /* Intro Section */
        .intro {
            text-align: center;
            font-size: 10pt;
            line-height: 1.5;
            color: #482626;
            margin: 5mm 0;
            padding: 0 3mm;
        }

        /* Product Section */
        .product-section {
            background: #F8F3F0;
            padding: 6mm;
            margin: 5mm 0;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
        }

        .product-image-cell {
            width: 45mm;
            vertical-align: middle;
            text-align: center;
            padding-right: 5mm;
        }

        .product-image {
            width: 25mm;
            height: 25mm;
            object-fit: cover;
            border: 2px solid #482626;
        }

        .product-info-cell {
            vertical-align: middle;
            text-align: left;
        }

        .product-title {
            font-size: 12pt;
            font-weight: bold;
            color: #482626;
            margin-bottom: 3mm;
        }

        .product-meta {
            font-size: 10pt;
            color: #666666;
            line-height: 1.8;
        }

        .product-meta-label {
            font-weight: bold;
            color: #482626;
        }

        /* Certificate Details */
        .cert-section {
            margin: 6mm auto;
            padding: 5mm 0;
            border-top: 2px solid #482626;
            border-bottom: 2px solid #482626;
            max-width: 90mm;
            text-align: left;
        }

        .cert-row {
            padding: 1.5mm 0;
            font-size: 10pt;
            text-align: left;
        }

        .cert-label {
            display: inline-block;
            width: 45mm;
            font-weight: bold;
            color: #482626;
        }

        .cert-value {
            color: #666666;
        }

        /* QR Section */
        .qr-section {
            text-align: center;
            margin: 6mm 0;
        }

        .qr-code {
            width: 25mm;
            height: 25mm;
        }

        .qr-label {
            font-size: 9pt;
            color: #666666;
            margin-top: 2mm;
        }

        /* Footer */
        .footer {
            text-align: center;
            margin-top: 6mm;
            padding-top: 5mm;
            border-top: 1px solid #ddd;
        }

        .signature {
            font-size: 10pt;
            font-weight: bold;
            color: #482626;
            margin-bottom: 2mm;
        }

        .disclaimer {
            font-size: 8pt;
            color: #999999;
            line-height: 1.3;
            padding: 0 3mm;
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ public_path('images/logo/bags_and_tea_logo_mobile.svg') }}" alt="{{ config('app.name') }}" class="logo">
            <div class="main-title">{!! __('certificates.title') !!}</div>
            <div class="subtitle">{!! __('certificates.subtitle') !!}</div>
        </div>

        <!-- Intro Text -->
        <div class="intro">
            {!! __('certificates.intro', ['app_name' => config('app.name')]) !!}
        </div>

        <!-- Product Section -->
        <div class="product-section">
            <table class="product-table">
                <tr>
                    <td class="product-image-cell">
                        @if($productImage && !empty($productImage))
                            <img src="{{ $productImage }}" alt="{{ $productName }}" class="product-image">
                        @else
                            <div style="width: 25mm; height: 25mm; border: 2px solid #482626; display: flex; align-items: center; justify-content: center; background: #f0f0f0; font-size: 8pt; color: #999;">No Image</div>
                        @endif
                    </td>
                    <td class="product-info-cell">
                        <div class="product-title">{{ $productName }}</div>
                        <div class="product-meta">
                            @if($brandName && $brandName !== 'N/A')
                                <div><span class="product-meta-label">{{ __('certificates.brand') }}</span> {{ $brandName }}</div>
                            @endif
                            @if($sku && $sku !== 'N/A')
                                <div><span class="product-meta-label">{{ __('certificates.sku') }}</span> {{ $sku }}</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Certificate Details -->
        <div class="cert-section">
            <div class="cert-row">
                <span class="cert-label">{{ __('certificates.certificate_number') }}</span>
                <span class="cert-value">{{ $certificate->certificate_number }}</span>
            </div>
            <div class="cert-row">
                <span class="cert-label">{{ __('certificates.order_number') }}</span>
                <span class="cert-value">{{ $certificate->order_number }}</span>
            </div>
            <div class="cert-row">
                <span class="cert-label">{{ __('certificates.authenticated_for') }}</span>
                <span class="cert-value">{{ $certificate->customer_name }}</span>
            </div>
            <div class="cert-row">
                <span class="cert-label">{{ __('certificates.issue_date') }}</span>
                <span class="cert-value">{{ $certificate->sent_at->format('F j, Y') }}</span>
            </div>
        </div>

        <!-- QR Code -->
        <div class="qr-section">
            <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
            <div class="qr-label">{{ __('certificates.scan_to_verify') }}</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="signature">{{ __('certificates.authentication_team', ['app_name' => config('app.name')]) }}</div>
            <div class="disclaimer">{!! __('certificates.disclaimer', ['app_name' => config('app.name')]) !!}</div>
        </div>
    </div>
</body>
</html>
