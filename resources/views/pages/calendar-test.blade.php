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

<!-- Calendar Widget Scripts -->
<script src="http://channel-manager-api.guides-portal-ble.local/api/calendar-widget/script"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new BLECalendarWidget({
            apiBaseUrl: 'http://channel-manager-api.guides-portal-ble.local/api/calendar-widget',
            containerId: 'calendar-widget',
            productId: '876524_01FZXY6W4DT1AZKZEMXFD8VN4G',
            primaryColor: '#EF4444',
            displayMode: 'modal',
            onReservationComplete: function(reservation) {
                console.log('Reservation completed:', reservation);
                // Customize: redirect to booking page with selected date
                // window.location.href = '/booking?date=' + reservation.date;
            },
            onError: function(error) {
                console.error('Calendar error:', error);
            }
        });
    });
</script>
@endsection