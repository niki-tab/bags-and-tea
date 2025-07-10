@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Content -->
        <div class="lg:w-2/3">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Calendar Widget Test</h1>
            
            <div class="prose max-w-none">
                <p class="text-gray-700 mb-4">
                    Esta es una página de prueba para mostrar el widget de calendario. El widget se posiciona en la parte derecha de la pantalla para una mejor experiencia de usuario.
                </p>
                
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Información del Widget</h2>
                <ul class="list-disc pl-6 text-gray-700 space-y-2">
                    <li>Widget de calendario interactivo</li>
                    <li>Reservas en tiempo real</li>
                    <li>Integración con sistema de pagos</li>
                    <li>Personalización de colores</li>
                </ul>
                
                <div class="mt-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Características</h3>
                    <p class="text-gray-700">
                        El widget permite a los usuarios seleccionar fechas disponibles, ver precios y completar reservas de forma intuitiva. 
                        Está diseñado para integrarse perfectamente con el flujo de trabajo existente.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Right Sidebar with Calendar Widget -->
        <div class="lg:w-1/3">
            <div class="sticky top-8">
                <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Reserva tu cita</h3>
                    
                    <!-- Calendar Widget Container -->
                    <div id="calendar-widget" style="max-width: 100%; margin: 0;"></div>
                </div>
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