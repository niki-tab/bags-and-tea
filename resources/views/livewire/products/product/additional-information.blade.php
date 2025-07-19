<div class="w-full max-w-6xl mx-auto mt-8 mb-8">
    <!-- Product Information Container -->
    <div class="bg-background-color-4 rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Pago Section -->
        <div class="border-b border-gray-300">
            <button 
                wire:click="toggleSection('pago')"
                class="w-full px-6 py-5 flex justify-between items-center text-left hover:bg-color-1 hover:bg-opacity-20 transition-colors duration-200 focus:outline-none {{ $openSection === 'pago' ? 'bg-color-1 bg-opacity-20' : '' }}"
            >
                <h3 class="text-lg font-medium text-color-2">
                    {{ app()->getLocale() === 'es' ? 'Pago' : 'Payment' }}
                </h3>
                <div class="transform transition-transform duration-300 {{ $openSection === 'pago' ? 'rotate-45' : '' }}">
                    <svg class="w-5 h-5 text-color-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </button>
            
            <div 
                class="overflow-hidden transition-all duration-300 ease-in-out {{ $openSection === 'pago' ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}"
            >
                <div class="px-6 pb-5 text-gray-700 leading-relaxed">
                    <div class="space-y-3">
                        <p class="font-medium text-color-2">{{ app()->getLocale() === 'es' ? 'Métodos de pago seguros:' : 'Secure payment methods:' }}</p>
                        <ul class="space-y-2 ml-4">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ app()->getLocale() === 'es' ? 'Tarjetas de crédito y débito' : 'Credit and debit cards' }}
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                PayPal
                            </li>
                        </ul>
                        <p class="text-sm text-gray-600 mt-3">
                            <svg class="w-4 h-4 text-green-600 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ app()->getLocale() === 'es' ? 'Todas las transacciones están protegidas con certificado SSL' : 'All transactions are protected with SSL certificate' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Envío Section -->
        <div class="border-b border-gray-300">
            <button 
                wire:click="toggleSection('envio')"
                class="w-full px-6 py-5 flex justify-between items-center text-left hover:bg-color-1 hover:bg-opacity-20 transition-colors duration-200 focus:outline-none {{ $openSection === 'envio' ? 'bg-color-1 bg-opacity-20' : '' }}"
            >
                <h3 class="text-lg font-medium text-color-2">
                    {{ app()->getLocale() === 'es' ? 'Envío' : 'Shipping' }}
                </h3>
                <div class="transform transition-transform duration-300 {{ $openSection === 'envio' ? 'rotate-45' : '' }}">
                    <svg class="w-5 h-5 text-color-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </button>
            
            <div 
                class="overflow-hidden transition-all duration-300 ease-in-out {{ $openSection === 'envio' ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}"
            >
                <div class="px-6 pb-5 text-gray-700 leading-relaxed">
                    <div class="space-y-4">
                        <div>
                            <p class="font-medium text-color-2 mb-2">{{ app()->getLocale() === 'es' ? 'Opciones de envío:' : 'Shipping options:' }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="bg-white p-3 rounded border">
                                    <p class="font-medium text-sm">{{ app()->getLocale() === 'es' ? 'Envío estándar' : 'Standard shipping' }}</p>
                                    <p class="text-sm text-gray-600">{{ app()->getLocale() === 'es' ? '5-7 días laborables' : '5-7 business days' }}</p>
                                    <p class="text-sm text-color-3 font-medium">{{ app()->getLocale() === 'es' ? 'Gratis' : 'Free' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded border">
                                    <p class="font-medium text-sm">{{ app()->getLocale() === 'es' ? 'Envío express' : 'Express shipping' }}</p>
                                    <p class="text-sm text-gray-600">{{ app()->getLocale() === 'es' ? '2-3 días laborables' : '2-3 business days' }}</p>
                                    <p class="text-sm text-color-3 font-medium">€15.00</p>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p class="flex items-start">
                                <svg class="w-4 h-4 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                {{ app()->getLocale() === 'es' ? 'Los pedidos se procesan en 1-2 días laborables antes del envío' : 'Orders are processed within 1-2 business days before shipping' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devoluciones Section -->
        <div class="border-b border-gray-300">
            <button 
                wire:click="toggleSection('devoluciones')"
                class="w-full px-6 py-5 flex justify-between items-center text-left hover:bg-color-1 hover:bg-opacity-20 transition-colors duration-200 focus:outline-none {{ $openSection === 'devoluciones' ? 'bg-color-1 bg-opacity-20' : '' }}"
            >
                <h3 class="text-lg font-medium text-color-2">
                    {{ app()->getLocale() === 'es' ? 'Devoluciones' : 'Returns' }}
                </h3>
                <div class="transform transition-transform duration-300 {{ $openSection === 'devoluciones' ? 'rotate-45' : '' }}">
                    <svg class="w-5 h-5 text-color-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </button>
            
            <div 
                class="overflow-hidden transition-all duration-300 ease-in-out {{ $openSection === 'devoluciones' ? 'max-h-96 opacity-100' : 'max-h-0 opacity-0' }}"
            >
                <div class="px-6 pb-5 text-gray-700 leading-relaxed">
                    <div class="space-y-4">
                        <div>
                            <p class="font-medium text-color-2 mb-2">{{ app()->getLocale() === 'es' ? 'Política de devoluciones:' : 'Return policy:' }}</p>
                            <ul class="space-y-2 text-sm">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ app()->getLocale() === 'es' ? '30 días para devoluciones sin preguntas' : '30-day no-questions-asked returns' }}
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ app()->getLocale() === 'es' ? 'Artículos deben estar en condiciones originales' : 'Items must be in original condition' }}
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ app()->getLocale() === 'es' ? 'Envío de devolución gratuito' : 'Free return shipping' }}
                                </li>
                            </ul>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-200">
                            <p class="text-sm font-medium text-blue-800 mb-1">{{ app()->getLocale() === 'es' ? 'Proceso de devolución:' : 'Return process:' }}</p>
                            <p class="text-sm text-blue-700">
                                {{ app()->getLocale() === 'es' ? 'Contáctanos para obtener una etiqueta de envío de devolución gratuita. Procesamos todos los reembolsos dentro de 5-7 días laborables después de recibir el artículo.' : 'Contact us for a free return shipping label. We process all refunds within 5-7 business days after receiving the item.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>