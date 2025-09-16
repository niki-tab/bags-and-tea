@extends('layouts.app')
<meta name="robots" content="noindex, nofollow" />

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-end">
        <!-- Right Sidebar with Calendar Widget -->
        <div class="w-full max-w-md">
            <div class="sticky top-8">
                <!-- Calendar Widget Container -->
                <div id="calendar-widget" style="max-width: 100%; margin: 0;"></div>
            </div>
        </div>
    </div>
</div>


<!-- BLE Calendar Widget -->
<div id="calendar-widget"></div>

<!-- Calendar Widget Scripts -->
<script src="http://channel-manager-api.guides-portal-ble.local/api/calendar-widget/script"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new BLECalendarWidget({
            apiBaseUrl: 'http://channel-manager-api.guides-portal-ble.local/api/calendar-widget',
            containerId: 'calendar-widget',
            productId: '876524_all',
            primaryColor: '#8B5CF6',
            displayMode: 'modal',
            
            // Stripe Payment Configuration
            stripePublicKey: 'pk_test_51S6J0P42BHxseI9p5wJD5Mc6AcYdLWVCN2uaxGRett5eDQbcyOXeWUPXp8G3OBTqbjYQEwuQBgQdoecKc3ELz0YQ00WsQqlQje',
            paymentMode: 'stripe',
            
            onReservationComplete: function(reservation) {
                console.log('Reservation completed:', reservation);
                if (reservation.status === 'paid') {
                    // Payment successful - redirect to success page
                    alert('Payment successful! 🎉\nSession ID: ' + reservation.sessionId);
                    // window.location.href = '/booking-success?session=' + reservation.sessionId;
                } else {
                    // Regular reservation - redirect to booking page
                    alert('Reservation completed! ID: ' + reservation.reservation_id);
                    // window.location.href = '/booking?date=' + reservation.date;
                }
            },
            onError: function(error) {
                console.error('Calendar error:', error);
                alert('Booking error: ' + error.message);
            }
        });
    });
</script>

@endsection