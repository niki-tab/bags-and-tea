@extends('emails.layouts.base')

@section('title', trans('emails.welcome.title') . ' - Bags and Tea')

@section('email-title', trans('emails.welcome.title'))

@section('email-reason', trans('emails.welcome.reason'))

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 30px 20px;">
    <tr>
        <td>
            <!-- Welcome Message -->
            <h3 style="margin: 0 0 20px 0; color: #482626; font-size: 20px; font-weight: bold; font-family: 'Arial', sans-serif;">
                {!! trans('emails.welcome.hello', ['name' => $userName]) !!}
            </h3>

            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ trans('emails.welcome.thank_you') }}
            </p>

            <!-- Welcome Info Box -->
            <div style="background-color: #F8F3F0; padding: 20px; margin: 20px 0; border-left: 4px solid #482626;">
                <p style="margin: 0; color: #482626; font-size: 16px; font-family: 'Arial', sans-serif;">
                    <strong>{{ trans('emails.welcome.email') }}:</strong> {{ $userEmail }}<br>
                    <strong>{{ trans('emails.welcome.join_date') }}:</strong> {{ date('F j, Y') }}
                </p>
            </div>

            <!-- Getting Started -->
            <h4 style="margin: 30px 0 15px 0; color: #482626; font-size: 18px; font-weight: bold; font-family: 'Arial', sans-serif;">
                {{ trans('emails.welcome.getting_started') }}
            </h4>

            <ul style="margin: 0 0 20px 20px; color: #482626; font-size: 14px; line-height: 1.8; font-family: 'Arial', sans-serif;">
                @foreach(trans('emails.welcome.next_steps') as $step)
                <li>{{ $step }}</li>
                @endforeach
            </ul>

            <!-- Call to Action -->
            <div style="text-align: center; margin: 30px 0;">
                @php
                    $shopUrl = $locale === 'es'
                        ? url('/es/tienda')
                        : url('/en/shop');
                @endphp
                <a href="{{ $shopUrl }}"
                   style="background-color: #482626; color: #ffffff; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 16px; font-weight: bold; display: inline-block; font-family: 'Arial', sans-serif;">
                    {{ trans('emails.welcome.start_shopping') }}
                </a>
            </div>

            <!-- Social Media -->
            <div style="text-align: center; margin: 30px 0; padding: 20px; background-color: #F9FAFB; border-radius: 8px;">
                <h5 style="margin: 0 0 15px 0; color: #482626; font-size: 16px; font-weight: bold; font-family: 'Arial', sans-serif;">
                    {{ trans('emails.welcome.follow_us') }}
                </h5>
                <p style="margin: 0 0 15px 0; color: #6B7280; font-size: 14px; font-family: 'Arial', sans-serif;">
                    {{ trans('emails.welcome.social_text') }}
                </p>
                <!-- Add social media links here if needed -->
            </div>

            <p style="margin: 20px 0 0 0; color: #482626; font-size: 14px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                {{ trans('emails.welcome.questions') }}
                <a href="mailto:info@bagsandtea.com" style="color: #482626; text-decoration: underline;">info@bagsandtea.com</a>.
            </p>

            <p style="margin: 20px 0 0 0; color: #482626; font-size: 14px; font-family: 'Arial', sans-serif;">
                {{ trans('emails.welcome.thank_you_closing') }}
            </p>
        </td>
    </tr>
</table>
@endsection