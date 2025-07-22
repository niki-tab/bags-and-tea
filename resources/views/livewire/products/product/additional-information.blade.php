<div class="w-full max-w-6xl mx-auto mt-8 mb-8">
    <!-- Product Information Container -->
    <div class="bg-background-color-4 overflow-hidden">
        
        <!-- Pago Section -->
        <div class="border-b border-color-2">
            <button 
                wire:click="toggleSection('pago')"
                class="w-full px-6 py-5 flex justify-between items-center text-left hover:bg-color-1 hover:bg-opacity-20 transition-colors duration-200 focus:outline-none {{ $openSection === 'pago' ? 'bg-color-1 bg-opacity-20' : '' }}"
            >
                <h3 class="text-lg font-medium text-color-2 text-robotoCondensed">
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
                        <p class="font-medium text-color-2 text-robotoCondensed mt-2 lg:mt-4">{{ app()->getLocale() === 'es' ? 'Métodos de pago seguros:' : 'Secure payment methods:' }}</p>
                        <ul class="space-y-2 ml-4">
                            <li class="flex items-center text-robotoCondensed">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                {{ app()->getLocale() === 'es' ? 'Tarjetas de crédito y débito.' : 'Credit and debit cards.' }}
                            </li>
                            <li class="flex items-center text-robotoCondensed">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                PayPal.
                            </li>
                        </ul>
                        <p class="text-sm text-gray-600 mt-3 text-robotoCondensed">
                            <svg class="w-4 h-4 text-green-600 inline mr-1 mb-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            {{ app()->getLocale() === 'es' ? 'Todas las transacciones están protegidas con certificado SSL' : 'All transactions are protected with SSL certificate' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Envío Section -->
        <div class="border-b border-color-2">
            <button 
                wire:click="toggleSection('envio')"
                class="w-full px-6 py-5 flex justify-between items-center text-left hover:bg-color-1 hover:bg-opacity-20 transition-colors duration-200 focus:outline-none {{ $openSection === 'envio' ? 'bg-color-1 bg-opacity-20' : '' }}"
            >
                <h3 class="text-lg font-medium text-color-2 text-robotoCondensed">
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
                            <p class="font-medium text-color-2 mb-2 text-robotoCondensed mt-2 lg:mt-4">{{ app()->getLocale() === 'es' ? 'Opciones de envío:' : 'Shipping options:' }}</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="bg-white p-3 rounded border">
                                    <p class="font-medium text-sm text-robotoCondensed">{{ app()->getLocale() === 'es' ? 'Envío estándar' : 'Standard shipping' }}</p>
                                    <p class="text-sm text-gray-600 text-robotoCondensed">{{ app()->getLocale() === 'es' ? '5-7 días laborables' : '5-7 business days' }}</p>
                                    <p class="text-sm text-color-3 font-medium text-robotoCondensed">{{ app()->getLocale() === 'es' ? 'Gratis' : 'Free' }}</p>
                                </div>
                                <div class="bg-white p-3 rounded border">
                                    <p class="font-medium text-sm text-robotoCondensed">{{ app()->getLocale() === 'es' ? 'Envío express' : 'Express shipping' }}</p>
                                    <p class="text-sm text-gray-600 text-robotoCondensed">{{ app()->getLocale() === 'es' ? '2-3 días laborables' : '2-3 business days' }}</p>
                                    <p class="text-sm text-color-3 font-medium text-robotoCondensed">€15.00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Devoluciones Section -->
        <div class="border-b border-color-2">
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
                            <p class="font-medium text-color-2 mb-2 text-robotoCondensed mt-2 lg:mt-4">{{ app()->getLocale() === 'es' ? 'Política de devoluciones:' : 'Return policy:' }}</p>
                            <ul class="space-y-2 text-sm text-robotoCondensed">
                                <li class="flex items-start pl-2">
                                   - {{ app()->getLocale() === 'es' ? 'Solo se aceptan devoluciones en caso de defectos que no hayan sido visibles o detallados en las fotografías del producto.' : 'Only returns are accepted in case of defects that have not been visible or detailed in the product photos.' }}
                                </li>
                                <li class="flex items-start pl-2 text-robotoCondensed">
                                    - {{ app()->getLocale() === 'es' ? 'El cliente debe contactar con nosotros para obtener una etiqueta de envío de devolución gratuita.' : 'The customer must contact us to obtain a free return shipping label.' }}
                                </li>
                                <li class="flex items-start pl-2 text-robotoCondensed">
                                    - {{ app()->getLocale() === 'es' ? 'Envío de devolución gratuito.' : 'Free return shipping.' }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>