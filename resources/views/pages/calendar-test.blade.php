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
<script src="https://cm-staging.barcelonalocalexperiences.com/api/calendar-widget/script"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const widget = new BLECalendarWidget({
            apiBaseUrl: 'https://cm-staging.barcelonalocalexperiences.com/api/calendar-widget',
            containerId: 'calendar-widget',
            productId: '1048163',
            primaryColor: '#ff6b35', // Brand color
            onReservationComplete: function(reservation) {
                window.location.href = '/booking-success?id=' + reservation.reservation_id;
            },
            onError: function(error) {
                alert('Booking error: ' + error.message);
            }
        });
    });
</script>
@endsection