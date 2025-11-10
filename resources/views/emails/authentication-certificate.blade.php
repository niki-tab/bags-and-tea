@extends('emails.layouts.base')

@section('title', __('certificates.email_title') . ' - ' . $certificate->certificate_number)

@section('email-title', __('certificates.email_title'))

@section('email-reason', __('certificates.automated_email'))

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 30px 20px;">
    <tr>
        <td>
            <!-- Greeting -->
            <h3 style="margin: 0 0 20px 0; color: #482626; font-size: 20px; font-weight: bold; font-family: 'Arial', sans-serif;">
                {!! __('certificates.dear', ['name' => $certificate->customer_name]) !!}
            </h3>

            <!-- Introduction -->
            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ __('certificates.thank_you', ['app_name' => config('app.name')]) }}
            </p>

            <!-- Certificate Information Box -->
            <div style="background-color: #F8F3F0; padding: 20px; margin: 20px 0; border-left: 4px solid #482626;">
                <p style="margin: 0; color: #482626; font-size: 16px; font-family: 'Arial', sans-serif;">
                    <strong>{{ __('certificates.certificate_number') }}</strong> {{ $certificate->certificate_number }}<br>
                    <strong>{{ __('certificates.order_number') }}</strong> {{ $certificate->order_number }}<br>
                    <strong>{{ __('certificates.issue_date') }}</strong> {{ $certificate->sent_at->format('F j, Y') }}
                </p>
            </div>

            <!-- Certificate Description -->
            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ __('certificates.confirmation_text') }}
            </p>

            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ __('certificates.keep_safe') }}
            </p>

            <!-- Questions Section -->
            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ __('certificates.questions') }}
            </p>

            <!-- Closing -->
            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                <strong>{{ __('certificates.with_appreciation') }}</strong>
            </p>
            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ __('certificates.team', ['app_name' => config('app.name')]) }}
            </p>
        </td>
    </tr>
</table>
@endsection
