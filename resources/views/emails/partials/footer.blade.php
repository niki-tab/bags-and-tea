<!-- Footer Section -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #F8F3F0; margin-top: 30px;">
    <tr>
        <td align="center" style="padding: 30px 20px;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 560px;">
                <!-- Contact Information -->
                <tr>
                    <td align="center" style="padding-bottom: 20px;">
                        <h3 style="margin: 0 0 15px 0; color: #482626; font-size: 18px; font-weight: bold; text-align: center; font-family: 'Arial', sans-serif;">
                            {{ trans('emails.footer.contact_us') }}
                        </h3>
                        <p style="margin: 0; color: #482626; font-size: 14px; text-align: center; line-height: 1.6; font-family: 'Arial', sans-serif;">
                            <strong>{{ trans('emails.footer.email') }}:</strong> info@bagsandtea.com<br>
                            <strong>{{ trans('emails.footer.website') }}:</strong> <a href="{{ url('/') }}" style="color: #482626; text-decoration: underline;">www.bagsandtea.com</a>
                        </p>
                    </td>
                </tr>
                
                <!-- Divider -->
                <tr>
                    <td style="padding: 15px 0;">
                        <hr style="border: none; border-top: 1px solid #482626; opacity: 0.3; margin: 0;">
                    </td>
                </tr>
                
                <!-- Social Links & Legal -->
                <tr>
                    <td align="center" style="padding-top: 15px;">
                        <!-- Social Media Links -->
                        <table cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto 20px auto;">
                            <tr>
                                <td style="padding: 0 15px;">
                                    <a href="https://www.instagram.com/bags.and.tea?igsh=NTgwcGU2a21paGxk&utm_source=qr" target="_blank" style="color: #482626; text-decoration: none; font-size: 14px; font-family: 'Arial', sans-serif;">Instagram</a>
                                </td>
                            </tr>
                        </table>
                        
                        <!-- Copyright & Unsubscribe -->
                        <p style="margin: 0 0 10px 0; color: #482626; font-size: 12px; text-align: center; line-height: 1.5; font-family: 'Arial', sans-serif;">
                            {!! trans('emails.footer.copyright', ['year' => date('Y')]) !!}
                        </p>
                        <p style="margin: 0; color: #482626; font-size: 12px; text-align: center; font-family: 'Arial', sans-serif;">
                            @yield('email-reason', trans('emails.order_confirmation.reason'))<br>
                            <a href="#" style="color: #482626; text-decoration: underline;">{{ trans('emails.footer.unsubscribe') }}</a> | 
                            <a href="{{ app()->getLocale() === 'es' ? url('/es/privacidad') : url('/en/privacy') }}" style="color: #482626; text-decoration: underline;">{{ trans('emails.footer.privacy_policy') }}</a>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Dark Footer -->
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #482626;">
    <tr>
        <td align="center" style="padding: 15px 20px;">
            <p style="margin: 0; color: #F8F3F0; font-size: 11px; text-align: center; font-family: 'Arial', sans-serif;">
                {{ trans('emails.footer.no_reply') }}
            </p>
        </td>
    </tr>
</table>