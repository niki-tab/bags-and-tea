<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Authenticity</title>
    <style>
        @page {
            margin: 0;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%);
        }
        .certificate {
            width: 210mm;
            height: 297mm;
            padding: 15mm;
            position: relative;
            background: #ffffff;
        }
        .border-outer {
            border: 3px solid #000000;
            padding: 10mm;
            height: 100%;
            position: relative;
        }
        .border-inner {
            border: 1px solid #000000;
            padding: 8mm;
            height: 100%;
        }
        .header {
            text-align: center;
            margin-bottom: 10mm;
            padding-bottom: 6mm;
            border-bottom: 2px solid #000000;
        }
        .logo {
            max-width: 60mm;
            max-height: 25mm;
            margin-bottom: 5mm;
        }
        .title {
            font-size: 24pt;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
            color: #000000;
        }
        .subtitle {
            font-size: 10pt;
            letter-spacing: 1px;
            color: #666666;
            margin-top: 3mm;
        }
        .content {
            margin: 6mm 0;
        }
        .intro-text {
            text-align: center;
            font-size: 10pt;
            line-height: 1.5;
            margin-bottom: 6mm;
            color: #333333;
        }
        .product-section {
            margin: 8mm 0;
        }
        .product-image {
            width: 35mm;
            height: 35mm;
            object-fit: cover;
            border: 1px solid #dddddd;
            float: left;
            margin-right: 4mm;
            margin-bottom: 4mm;
        }
        .product-details {
            overflow: hidden;
        }
        .product-name {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 2mm;
            color: #000000;
        }
        .detail-row {
            font-size: 9pt;
            margin: 1.5mm 0;
            color: #333333;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 22mm;
            color: #000000;
        }
        .certificate-details {
            background: #f8f8f8;
            padding: 4mm;
            margin: 6mm 0;
            border-left: 3px solid #000000;
        }
        .cert-detail-row {
            font-size: 9pt;
            margin: 1.5mm 0;
            display: flex;
            justify-content: space-between;
        }
        .cert-label {
            font-weight: bold;
            color: #000000;
        }
        .cert-value {
            color: #333333;
            text-align: right;
        }
        .qr-section {
            text-align: center;
            margin: 6mm 0;
            padding: 3mm;
            background: #ffffff;
        }
        .qr-code {
            width: 25mm;
            height: 25mm;
            margin: 0 auto;
        }
        .qr-text {
            font-size: 8pt;
            color: #666666;
            margin-top: 2mm;
            font-style: italic;
        }
        .footer {
            position: absolute;
            bottom: 10mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #666666;
        }
        .signature-line {
            width: 60mm;
            border-top: 1px solid #000000;
            margin: 5mm auto 2mm auto;
        }
        .signature-text {
            font-size: 9pt;
            color: #333333;
        }
        .disclaimer {
            font-size: 8pt;
            color: #999999;
            margin-top: 3mm;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="border-outer">
            <div class="border-inner">
                <!-- Header -->
                <div class="header">
                    @if(config('app.logo_1_path'))
                        <img src="{{ public_path(config('app.logo_1_path')) }}" alt="{{ config('app.name') }}" class="logo">
                    @endif
                    <h1 class="title">{!! __('certificates.title') !!}</h1>
                    <div class="subtitle">{!! __('certificates.subtitle') !!}</div>
                </div>

                <!-- Content -->
                <div class="content">
                    <div class="intro-text">
                        {!! __('certificates.intro', ['app_name' => config('app.name')]) !!}
                    </div>

                    <!-- Product Section -->
                    <div class="product-section">
                        @if($productImage)
                            <img src="{{ public_path($productImage) }}" alt="{{ $productName }}" class="product-image">
                        @endif
                        <div class="product-details">
                            <div class="product-name">{{ $productName }}</div>
                            <div class="detail-row">
                                <span class="detail-label">{{ __('certificates.brand') }}</span>
                                <span>{{ $brandName }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">{{ __('certificates.sku') }}</span>
                                <span>{{ $sku }}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">{{ __('certificates.condition') }}</span>
                                <span>{{ $qualityName }}</span>
                            </div>
                            @if(!empty($categories))
                                <div class="detail-row">
                                    <span class="detail-label">{{ __('certificates.category') }}</span>
                                    <span>
                                        @php
                                            if (is_array($categories)) {
                                                $categoryNames = [];
                                                foreach ($categories as $cat) {
                                                    if (isset($cat['name'])) {
                                                        if (is_array($cat['name'])) {
                                                            $categoryNames[] = $cat['name'][$locale] ?? $cat['name']['en'] ?? $cat['name'][array_key_first($cat['name'])] ?? '';
                                                        } else {
                                                            $categoryNames[] = $cat['name'];
                                                        }
                                                    }
                                                }
                                                echo implode(', ', array_filter($categoryNames));
                                            } else {
                                                echo $categories;
                                            }
                                        @endphp
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div style="clear: both;"></div>
                    </div>

                    <!-- Certificate Details -->
                    <div class="certificate-details">
                        <div class="cert-detail-row">
                            <span class="cert-label">{{ __('certificates.certificate_number') }}</span>
                            <span class="cert-value">{{ $certificate->certificate_number }}</span>
                        </div>
                        <div class="cert-detail-row">
                            <span class="cert-label">{{ __('certificates.order_number') }}</span>
                            <span class="cert-value">{{ $certificate->order_number }}</span>
                        </div>
                        <div class="cert-detail-row">
                            <span class="cert-label">{{ __('certificates.authenticated_for') }}</span>
                            <span class="cert-value">{{ $certificate->customer_name }}</span>
                        </div>
                        <div class="cert-detail-row">
                            <span class="cert-label">{{ __('certificates.issue_date') }}</span>
                            <span class="cert-value">{{ $certificate->sent_at->format('F j, Y') }}</span>
                        </div>
                    </div>

                    <!-- QR Code Section -->
                    <div class="qr-section">
                        <img src="data:image/svg+xml;base64,{{ $qrCode }}" alt="QR Code" class="qr-code">
                        <div class="qr-text">
                            {{ __('certificates.scan_to_verify') }}<br>
                            {{ $certificate->product_qr_url }}
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="footer">
                    <div class="signature-line"></div>
                    <div class="signature-text">{{ __('certificates.authentication_team', ['app_name' => config('app.name')]) }}</div>
                    <div class="disclaimer">
                        {!! __('certificates.disclaimer', ['app_name' => config('app.name')]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
