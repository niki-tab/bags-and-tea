@extends('layouts.app')

@section('content')

<div class="w-full">
    <div class="flex flex-col md:flex-row h-auto md:h-96">
        <div class="w-full md:w-1/2 px-8 md:px-28 py-12 md:pt-20 bg-[#CB4853] text-white">
            <h1 class="text-4xl md:text-5xl font-['Lovera'] md:w-[55%]">
                Vende tu bolso de lujo
            </h1>
            <p class="mt-6 md:mt-8 font-mixed">
                Bienvenida a B&T, el lugar perfecto par vender, comprar o alquilar artículos de lujo exclusivos, desde un icono atemporal hasta el último it bag.
            </p>
            <button class="mt-6 md:mt-8 bg-black text-white px-8 md:px-12 py-2 md:py-3 rounded-full font-medium">
                Ir al formulario &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>
            </button>
        </div>
        <div class="w-full md:w-1/2 bg-[#DEA3A5] py-12 md:py-0">
            <img src="{{ asset('images/we_buy_your_bag/Bolso_YSL1.svg') }}" 
                alt="Luxury YSL Bag" 
                class="w-2/3 md:w-1/2 mx-auto md:mt-16">
        </div>
    </div>
    <div class="py-8 md:py-16 bg-[#F8F3F0]">
        <h2 class="text-center text-2xl md:text-4xl mb-8 md:mb-14 font-['Lovera']">COMPRAMOS TU BOLSO DE LUJO</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 max-w-7xl mx-auto mb-4 px-4 md:px-0">
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Hermes.svg') }}" alt="Luxury Hermes Bag" 
                    class="mx-auto my-8 md:my-10 w-2/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/yves-saint-laurent.svg') }}" alt="Luxury YSL Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Dior.svg') }}" alt="Luxury Dior Bag" 
                    class="mx-auto my-12 md:my-16 w-2/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Louis-Vuitton.svg') }}" alt="Luxury Louis Vuitton Bag" 
                    class="mx-auto my-[20%] md:my-[24%] w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Gucci.svg') }}" alt="Luxury Gucci Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Prada.svg') }}" alt="Luxury Prada Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Goyard.svg') }}" alt="Luxury Goyard Bag" 
                    class="mx-auto my-12 md:my-16 w-3/5">
            </div>
            <div class="bg-[#E3D4CB] text-center h-32 md:h-40">
                <img src="{{ asset('images/we_buy_your_bag/Chanel.svg') }}" alt="Luxury Chanel Bag" 
                    class="mx-auto my-8 md:my-10 w-2/5">
            </div>
        </div>
    </div>
    <div class="bg-[#C8928A] text-white pb-14 text-center">
        <h2 class="relative top-12 text-center text-4xl font-regular font-['Lovera']">ES MUY SENCILLO</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 max-w-7xl mx-auto px-4 md:px-0">
            <div class="text-center relative">
                <div class="flex">
                    <span class="text-[245px] text-[#482626] font-['Lovelina'] relative bottom-[44px]">1</span>
                    <h3 class="text-robotoCondensed font-regular text-4xl w-1/2 text-left mt-28">
                        Busca ese bolso que ya no usas
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular relative bottom-[110px]">
                    ¿Tienes un bolso de marca que ya no usas? Es el momento perfecto para darle una nueva vida. En B&T te lo ponemos fácil: busca esa pieza especial en tu armario y contacta con nosotros. Estaremos encantados de ayudarte a venderlo de forma segura, rápida y con la discreción que mereces.
                </p>
            </div>

            <div class="text-center">
                <div class="flex">
                    <span class="text-[245px] text-[#482626] font-['Lovelina'] relative bottom-[44px]">2</span>
                    <h3 class="text-robotoCondensed font-regular text-4xl w-1/2 text-left mt-28">
                        Envíanos fotos y toda su historia
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular relative bottom-[110px]">
                    Envíanos fotos del bolso desde distintos ángulos: frontal, trasera, base, interior y detalles clave. Cuanta más información tengamos, mejor. Y si puedes, cuéntanos su historia: dónde lo compraste, cómo lo has cuidado o si tiene algún detalle especial. Cada bolso tiene un pasado, y nos encanta conocerlo para valorarlo como merece.
                </p>
            </div>

            <div class="text-center">
                <div class="flex">
                    <span class="text-[245px] text-[#482626] font-['Lovelina'] relative bottom-[44px]">3</span>
                    <h3 class="text-robotoCondensed font-regular text-4xl w-1/2 text-left mt-28">
                        ¡Te compramos tu bolso!
                    </h3>
                </div>
                <p class="text-left text-[#482626] font-robotoCondensed font-regular relative bottom-[110px]">
                    Una vez recibamos las fotos y detalles, nos encargamos de todo. Nuestro equipo evaluará tu bolso con el máximo rigor y, si encaja en nuestra selección, te haremos una propuesta de compra justa y transparente. Sin complicaciones, sin esperas innecesarias. Así de fácil: tú nos contactas, y nosotros hacemos el resto.
                </p>
            </div>
        </div>
        <button class="bg-black text-white px-12 py-3 rounded-full font-medium mx-auto">Ir al formulario &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;></button>
        
    </div>
        <!-- FAQs Section -->
        <div class="bg-[#3A1515] text-white py-12">
        <h2 class="text-center text-4xl font-regular mb-8">FAQs</h2>
        <div class="max-w-7xl mx-auto space-y-4 pb-10 px-4 md:px-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 gap-x-32 max-w-8xl mx-auto mb-4 mt-12 w-full">
            <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Qué marcas de bolsos compráis?</h3></summary>
                        <p class="mt-2">Compramos bolsos de Chanel, Hermès, Louis Vuitton, Dior, Saint Laurent, Fendi, Loewe, Prada, Gucci, Balenciaga, Céline, Bottega Veneta y Polène. Estas son las marcas en las que somos expertos. Siempre estamos evolucionando, y esperamos poder ampliar esta lista en el futuro.</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Cómo tasáis los bolsos para la compra?</h3></summary>
                        <p class="mt-2">Valoramos cada bolso en función del estado, año de fabricación, marca, y si incluye funda, caja o factura original. También tenemos en cuenta las fotos que nos envías. A partir de ahí, comparamos con precios reales de venta en nuestras fuentes especializadas y realizamos una tasación ajustada al mercado actual.</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Qué fotos necesitáis para llevar a cabo vuestra tasación?</h3></summary>
                        <p class="mt-2">Necesitamos una foto frontal del bolso, una de la etiqueta interior, una del logo o marca, y fotos de los grabados en los herrajes (como cremalleras, hebillas, adornos,…). También puedes añadir imágenes de cualquier imperfección, daño o detalle que consideres relevante a la hora de llevar a cabo la tasación</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Cuánto tardáis en hacer una oferta? ¿Y cuánto tiempo tarda en completarse la venta?</h3></summary>
                        <p class="mt-2">Respondemos lo antes posible para confirmar que estamos analizando tu bolso, y la oferta suele enviarse en un plazo de 24 a 72 horas. Desde el primer contacto hasta la compra, el proceso completo puede durar entre 5 y 7 días.</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Aceptáis bolsos con signos de uso o pequeñas imperfecciones?</h3></summary>
                        <p class="mt-2">Sí, aceptamos bolsos con signos de uso, siempre que estén en buen estado general. Eso sí, las imperfecciones pueden afectar al valor final de la tasación.</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Puedo vender un bolso aunque no tenga la factura o el packaging original?</h3></summary>
                        <p class="mt-2">Sí, puedes venderlo siempre que sea un bolso auténtico. Realizaremos las verificaciones necesarias para confirmar su autenticidad. Aunque no es obligatorio, contar con la factura o el packaging original facilita el proceso y puede aumentar el valor de la tasación.</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">Si acepto vuestra oferta, ¿cómo se realiza el pago?</h3></summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Tenéis servicio de recogida o tengo que enviarlo yo?</h3></summary>
                        <p class="mt-2">Answer to FAQ 1</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">¿Puedo vender más de un bolso a la vez?</h3></summary>
                        <p class="mt-2">Sí, puedes vender todos los bolsos que desees. Cada uno será verificado individualmente para asegurar su autenticidad antes de proceder con la compra.</p>
                    </details>  
                </div>
                <div class="md:col-span-1">
                    <details class="pb-4 md:pb-6 border-b border-white">
                        <summary class="cursor-pointer"><h3 class="">Si tengo dudas durante el proceso de venta, ¿a quién me dirijo?</h3></summary>
                        <p class="mt-2">Puedes contactarnos tanto por email como por WhatsApp, como te resulte más cómodo. Estaremos encantados de ayudarte y responder a todas tus preguntas.</p>
                    </details>  
                </div>
            </div>
            
            <!-- Repeat for other FAQs -->
        </div>
    </div>

     <!-- Sell Your Bag Section -->
     <div>
        <div class="bg-[#F6F0ED] py-8">
        </div>
        <div class="relative">
        <!-- Top half background -->
        <div class="absolute top-0 left-0 right-0 h-3/4 bg-[#DEA3A5]"></div>
        <!-- Bottom half background -->
        <div class="absolute bottom-0 left-0 right-0 h-1/4 bg-[#F6F0ED]"></div>
        
        <!-- Content -->
        <div class="relative z-10">
            <div class="max-w-7xl">
                <div class="flex flex-col md:flex-row items-center">
                    <!-- Image Section -->
                    <div class="w-full md:w-4/5 pt-8">
                        <img src="{{ asset('images/we_buy_your_bag/lv-bag-we-buy-your-bags.svg') }}" 
                            alt="Louis Vuitton Bag" 
                            class="w-full">
                    </div>
                    
                    <!-- Text Section -->
                    <div class="w-full md:w-[60%] bg-white p-8 md:p-12 relative md:bottom-[269px] md:left-[8%] bg-[#F6F0ED] border-l-[12px] border-b-[12px] border-[#BE6F62]">
                        <h2 class="text-3xl md:text-4xl font-['Lovera'] text-[#3A1515] mb-6">
                            VENDE TU BOLSO DE MARCA
                        </h2>
                        <p class="text-[#3A1515] mb-6">
                            No esperes más y vende ese bolso de marca que llevas tiempo queriendo dejar ir. Rellena el formulario o, si lo prefieres, escríbenos directamente por WhatsApp al +34XXXXXXXXX. Estamos aquí para ayudarte a darle una nueva vida a esa pieza especial, de forma rápida, segura y sin complicaciones.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@metadata