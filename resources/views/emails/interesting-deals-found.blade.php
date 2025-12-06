@extends('emails.layouts.base')

@section('title', 'Vinted Deals Found!')

@section('email-title', 'Vinted Deals Alert')

@section('email-reason', 'New interesting deals matching your search criteria')

@section('content')
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="padding: 30px 20px;">
    <tr>
        <td>
            <h3 style="margin: 0 0 20px 0; color: #482626; font-size: 20px; font-weight: bold; font-family: 'Arial', sans-serif;">
                New Vinted Deals Found!
            </h3>

            <p style="margin: 0 0 20px 0; color: #482626; font-size: 16px; line-height: 1.6; font-family: 'Arial', sans-serif;">
                We found <strong>{{ $listings->count() }}</strong> interesting {{ $listings->count() === 1 ? 'deal' : 'deals' }} matching your search criteria.
            </p>

            @foreach($listings as $listing)
            <div style="background-color: #ffffff; border: 1px solid #E5E7EB; margin: 20px 0; border-radius: 8px; overflow: hidden;">
                <!-- Product Header -->
                <div style="background-color: #F9FAFB; padding: 15px 20px; border-bottom: 1px solid #E5E7EB;">
                    <h4 style="margin: 0; color: #482626; font-size: 18px; font-weight: bold; font-family: 'Arial', sans-serif;">
                        {{ $listing->title ?? 'Listing' }}
                    </h4>
                    <div style="margin-top: 5px;">
                        <span style="background-color: #10B981; color: #ffffff; font-size: 14px; font-weight: bold; padding: 4px 10px; border-radius: 4px; font-family: 'Arial', sans-serif;">
                            {{ number_format($listing->price, 2) }} {{ $listing->currency }}
                        </span>
                        @if($listing->uploaded_text)
                        <span style="color: #6B7280; font-size: 13px; margin-left: 10px; font-family: 'Arial', sans-serif;">
                            {{ $listing->uploaded_text }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Product Images -->
                @if($listing->images && count($listing->images) > 0)
                <div style="padding: 15px 20px;">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            @foreach(array_slice($listing->images, 0, 3) as $index => $image)
                            <td style="width: 33.33%; padding: {{ $index > 0 ? '0 0 0 10px' : '0' }}; vertical-align: top;">
                                <img src="{{ $image }}"
                                     alt="Product image {{ $index + 1 }}"
                                     style="width: 100%; height: 150px; object-fit: cover; border-radius: 6px; border: 1px solid #E5E7EB;">
                            </td>
                            @endforeach
                        </tr>
                    </table>

                    @if(count($listing->images) > 3)
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 10px;">
                        <tr>
                            @foreach(array_slice($listing->images, 3, 2) as $index => $image)
                            <td style="width: 50%; padding: {{ $index > 0 ? '0 0 0 10px' : '0' }}; vertical-align: top;">
                                <img src="{{ $image }}"
                                     alt="Product image {{ $index + 4 }}"
                                     style="width: 100%; height: 120px; object-fit: cover; border-radius: 6px; border: 1px solid #E5E7EB;">
                            </td>
                            @endforeach
                        </tr>
                    </table>
                    @endif
                </div>
                @elseif($listing->main_image_url)
                <div style="padding: 15px 20px;">
                    <img src="{{ $listing->main_image_url }}"
                         alt="Product image"
                         style="width: 100%; max-width: 300px; height: auto; border-radius: 6px; border: 1px solid #E5E7EB;">
                </div>
                @endif

                <!-- View Button -->
                <div style="padding: 15px 20px; text-align: center; border-top: 1px solid #E5E7EB;">
                    <a href="{{ $listing->url }}"
                       style="background-color: #482626; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; display: inline-block; font-family: 'Arial', sans-serif;">
                        View on Vinted
                    </a>
                </div>
            </div>
            @endforeach

            <p style="margin: 30px 0 0 0; color: #6B7280; font-size: 13px; font-family: 'Arial', sans-serif; text-align: center;">
                This is an automated notification from your Vinted deal scanner.
            </p>
        </td>
    </tr>
</table>
@endsection
