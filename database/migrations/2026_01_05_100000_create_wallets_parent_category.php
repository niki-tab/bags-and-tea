<?php

use Illuminate\Support\Str;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create parent category "Wallets"
        $walletsCategoryId = (string) Str::uuid();

        DB::table('categories')->insert([
            'id' => $walletsCategoryId,
            'name' => json_encode([
                'en' => 'Wallets',
                'es' => 'Carteras'
            ]),
            'slug' => json_encode([
                'en' => 'wallets',
                'es' => 'carteras'
            ]),
            'description_1' => json_encode([
                'en' => 'At Bags & Tea, we give you access to a carefully curated selection of pre-owned luxury wallets and small leather goods, all authentic, verified, and full of character. We work exclusively with the world\'s leading luxury houses to offer iconic wallets, timeless designs, and exclusive pieces that combine functionality and elegance. Each wallet is meticulously selected for its authenticity, condition, and aesthetic value, ensuring quality and refined style in every detail. If you\'re looking to buy a second-hand designer wallet with guaranteed authenticity, sophistication, and exclusivity, you\'re in the right place.',
                'es' => 'En Bags & Tea te damos acceso a una cuidada selección de carteras y wallets de lujo de segunda mano, originales, verificados y con carácter. Trabajamos exclusivamente con las grandes casas del lujo para ofrecerte carteras icónicas, diseños atemporales y piezas exclusivas que combinan funcionalidad y elegancia. Cada wallet ha sido seleccionado minuciosamente por su autenticidad, estado de conservación y valor estético, garantizando calidad y estilo en cada detalle. Si buscas comprar una cartera de marca de segunda mano con garantías, sofisticación y exclusividad, estás en el lugar adecuado.'
            ]),
            'description_2' => json_encode([
                'en' => 'From timeless classics to contemporary designs, find the perfect wallet for every style.',
                'es' => 'Desde clásicos atemporales hasta diseños contemporáneos, encuentra la cartera perfecta para cada estilo.'
            ]),
            'parent_id' => null,
            'display_order' => 2,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Brand-specific wallet descriptions adapted from bag descriptions
        $walletDescriptions = [
            'Gucci' => [
                'en' => 'Discover our carefully curated selection of second-hand Gucci wallets, where each piece tells a story of style and sophistication. At Bags & Tea, we offer you the opportunity to buy authentic Gucci wallets, backed by our commitment to quality and authenticity. From iconic models to rare editions, our luxury Gucci wallets are selected for their design, condition, and exclusivity. If you\'re looking to buy a Gucci wallet with confidence, you\'re in the right place.',
                'es' => 'Descubre nuestra cuidada selección de carteras Gucci de segunda mano, donde cada pieza cuenta una historia de estilo y sofisticación. En Bags & Tea te ofrecemos la oportunidad de comprar carteras Gucci auténticas, con la garantía de calidad y autenticidad que nos define. Desde modelos icónicos hasta ediciones difíciles de encontrar, nuestras carteras Gucci de lujo han sido seleccionadas por su diseño, estado y exclusividad. Si estás buscando comprar una cartera Gucci con confianza, estás en el lugar adecuado.'
            ],
            'Fendi' => [
                'en' => 'Discover our exclusive selection of second-hand Fendi wallets, where cutting-edge design meets Italian tradition. From legendary styles to more contemporary designs, each Fendi wallet has been carefully selected for its authenticity, style, and impeccable condition. At Bags & Tea, we offer you the opportunity to buy unique and original Fendi wallets, with the peace of mind that you\'re investing in a true luxury piece—timeless and full of history.',
                'es' => 'En nuestra colección de carteras Fendi de segunda mano descubrirás diseños icónicos que han marcado tendencia generación tras generación. Desde piezas emblemáticas hasta diseños más contemporáneos, cada cartera Fendi destaca por su carácter audaz, su calidad impecable y su herencia romana. En Bags & Tea seleccionamos carteras Fendi auténticas, únicas y en excelente estado, para que puedas comprar lujo con confianza y estilo.'
            ],
            'Loewe' => [
                'en' => 'Immerse yourself in the world of second-hand Loewe wallets, where Spanish craftsmanship and modernity come together in unmistakable pieces. At Bags & Tea, we curate authentic Loewe wallets that stand out for their timeless design, impeccable quality, and exclusive character. Here you can buy original Loewe wallets with complete confidence. A conscious, elegant, and accessible way to enjoy the most refined luxury.',
                'es' => 'Sumérgete en el universo de las carteras Loewe de segunda mano, donde la artesanía española y la modernidad se unen en piezas inconfundibles. En Bags & Tea seleccionamos carteras Loewe auténticas que destacan por su diseño atemporal, calidad impecable y carácter exclusivo. Aquí podrás comprar carteras Loewe originales con total confianza. Una forma consciente, elegante y accesible de disfrutar del lujo más refinado.'
            ],
            'Christian Dior' => [
                'en' => 'Explore our carefully curated selection of second-hand Dior wallets, a symbol of Parisian elegance and timeless sophistication. At Bags & Tea, we offer you the opportunity to buy authentic Dior wallets—full of history, charm, and designs that transcend trends. Each piece has been selected for its condition, originality, and style. An exclusive and conscious way to access the most coveted luxury.',
                'es' => 'Explora nuestra cuidada selección de carteras Dior de segunda mano, símbolo de elegancia parisina y sofisticación atemporal. En Bags & Tea te ofrecemos la posibilidad de comprar carteras Dior auténticas, con historia, encanto y un diseño que trasciende modas. Cada pieza ha sido seleccionada por su estado, originalidad y estilo. Una forma exclusiva y consciente de acceder al lujo más deseado.'
            ],
            'Louis Vuitton' => [
                'en' => 'Discover the timeless elegance of second-hand Louis Vuitton wallets—true collector\'s pieces that combine history, design, and impeccable craftsmanship. At Bags & Tea, we curate original Louis Vuitton wallets that have been thoroughly verified, guaranteeing authenticity and exceptional condition. Each model embodies the travel spirit of the maison. Buying a second-hand Louis Vuitton wallet is a smart way to enjoy luxury with a signature touch.',
                'es' => 'Descubre la elegancia atemporal de las carteras Louis Vuitton de segunda mano, auténticas piezas de coleccionista que combinan historia, diseño y artesanía impecable. En Bags & Tea seleccionamos carteras Louis Vuitton originales que han sido verificadas minuciosamente, garantizando autenticidad y estado excepcional. Cada modelo encierra el espíritu viajero de la maison. Comprar una cartera Louis Vuitton de segunda mano es apostar por el lujo inteligente y con sello propio.'
            ],
            'Balenciaga' => [
                'en' => 'In our selection of second-hand Balenciaga wallets, you\'ll find unique pieces that combine the maison\'s avant-garde legacy with an unmistakable urban style. Each Balenciaga wallet has been carefully authenticated and selected for its quality and uniqueness. At Bags & Tea, we offer you the chance to buy original Balenciaga wallets with complete confidence—embracing conscious luxury with personality.',
                'es' => 'En nuestra selección de carteras Balenciaga de segunda mano encontrarás piezas únicas que combinan el legado vanguardista de la maison con un estilo urbano inconfundible. Cada cartera Balenciaga ha sido cuidadosamente autenticada y seleccionada por su calidad y singularidad. En Bags & Tea te ofrecemos la posibilidad de comprar carteras Balenciaga originales con total confianza, apostando por el lujo consciente y con carácter.'
            ],
            'Chanel' => [
                'en' => 'Second-hand Chanel wallets embody the essence of eternal luxury. At Bags & Tea, we offer an exclusive selection of original Chanel wallets, carefully verified and chosen for their elegance, condition, and timeless value. Each piece is a masterpiece that transcends trends and generations. Buying a second-hand Chanel wallet means entering a world of sophistication with history and soul.',
                'es' => 'Las carteras Chanel de segunda mano representan la esencia del lujo eterno. En Bags & Tea te ofrecemos una selección exclusiva de carteras Chanel originales, cuidadosamente verificadas y elegidas por su elegancia, estado y valor atemporal. Cada pieza es una obra maestra que trasciende modas y generaciones. Comprar una cartera Chanel de segunda mano es acceder a un universo de sofisticación con historia y alma.'
            ],
            'Polène' => [
                'en' => 'Second-hand Polène wallets are the perfect balance of contemporary design, minimalism, and exquisite craftsmanship. At Bags & Tea, we curate original Polène models that stand out for their serene elegance and clean lines. Each wallet has been carefully verified so you can shop with complete confidence. If you\'re looking for an authentic, refined Polène wallet with personality, here you\'ll find unique pieces ready to accompany you.',
                'es' => 'Las carteras Polène de segunda mano son el equilibrio perfecto entre diseño contemporáneo, minimalismo y artesanía exquisita. En Bags & Tea seleccionamos modelos Polène originales que destacan por su elegancia serena y líneas depuradas. Cada cartera ha sido cuidadosamente verificada para que puedas comprar con plena confianza. Si buscas una cartera Polène auténtica, refinada y con personalidad, aquí encontrarás piezas únicas listas para acompañarte.'
            ],
            'Prada' => [
                'en' => 'In our collection of second-hand Prada wallets, you\'ll find pieces that combine functionality, style, and an unmistakable Italian flair. Each Prada wallet has been carefully authenticated and selected for its quality and timeless appeal. At Bags & Tea, we offer you the opportunity to buy original Prada wallets with complete confidence—embracing intelligent, refined luxury with a character of its own.',
                'es' => 'En nuestra colección de carteras Prada de segunda mano encontrarás piezas que combinan funcionalidad, estilo y un inconfundible sello italiano. Cada cartera Prada ha sido cuidadosamente autenticada y seleccionada por su calidad y atractivo atemporal. En Bags & Tea te ofrecemos la posibilidad de comprar carteras Prada originales con total confianza, y apostar así por un lujo inteligente, refinado y con carácter propio.'
            ],
            'Hermès' => [
                'en' => 'Second-hand Hermès wallets are much more than accessories—they are soulful collector\'s pieces. At Bags & Tea, we offer a carefully curated selection of original Hermès wallets, verified with the utmost precision. Each model represents the pinnacle of craftsmanship and exclusivity. Buying an authentic second-hand Hermès wallet means accessing the highest form of luxury—with history, distinction, and lasting value.',
                'es' => 'Las carteras Hermès de segunda mano son mucho más que accesorios: son piezas de colección con alma. En Bags & Tea te ofrecemos una cuidada selección de carteras Hermès originales, verificadas con el máximo rigor. Cada modelo representa el pináculo de la artesanía y la exclusividad. Comprar una cartera Hermès auténtica de segunda mano es acceder al lujo más elevado, con historia, distinción y valor duradero.'
            ],
            'Bottega Veneta' => [
                'en' => 'Discover our collection of second-hand Bottega Veneta wallets, a symbol of discretion, quiet luxury, and innovative design. At Bags & Tea, we select original pieces featuring the iconic intrecciato weave and impeccable artisanal quality. Each Bottega Veneta wallet has been verified and chosen for its authenticity and refined style. Buying an authentic second-hand Bottega Veneta wallet is a sophisticated and conscious choice.',
                'es' => 'Descubre nuestra colección de carteras Bottega Veneta de segunda mano, símbolo de discreción, lujo silencioso y diseño innovador. En Bags & Tea seleccionamos piezas originales con el emblemático intrecciato y una calidad artesanal impecable. Cada cartera Bottega Veneta ha sido verificada y elegida por su autenticidad y estilo depurado. Comprar una cartera Bottega Veneta auténtica de segunda mano es una elección sofisticada y consciente.'
            ],
            'Miu Miu' => [
                'en' => 'Second-hand Miu Miu wallets reflect a youthful, bold, and sophisticated spirit. At Bags & Tea, we offer original models with that distinctive blend of femininity, irreverence, and Italian design. Each Miu Miu wallet has been carefully authenticated and selected. Buying a second-hand Miu Miu wallet is a way to embrace luxury with a unique personality and style.',
                'es' => 'Las carteras Miu Miu de segunda mano reflejan un espíritu joven, atrevido y sofisticado. En Bags & Tea te ofrecemos modelos originales con ese toque distintivo que mezcla feminidad, irreverencia y diseño italiano. Cada cartera Miu Miu ha sido autenticada y seleccionada con sumo cuidado. Comprar una cartera Miu Miu de segunda mano es apostar por el lujo con personalidad propia y un estilo único.'
            ],
            'Céline' => [
                'en' => 'Second-hand Céline wallets are the ultimate expression of Parisian minimalism elevated to luxury. At Bags & Tea, we curate original Céline wallets that stand out for their clean design, impeccable quality, and understated elegance. Each piece has been precisely verified and chosen for its condition and authenticity. Buying a second-hand Céline wallet is a commitment to timeless style with distinctive character.',
                'es' => 'Las carteras Céline de segunda mano son la máxima expresión del minimalismo parisino elevado al lujo. En Bags & Tea seleccionamos carteras Céline originales que destacan por su diseño depurado, su calidad impecable y su elegancia discreta. Cada pieza ha sido verificada con precisión y elegida por su estado y autenticidad. Comprar una cartera Céline de segunda mano es una apuesta por el estilo atemporal con carácter propio.'
            ],
            'Yves Saint Laurent' => [
                'en' => 'Explore our collection of second-hand Saint Laurent wallets, where the heritage of French fashion meets a modern and refined aesthetic. At Bags & Tea, we select original Saint Laurent wallets that stand out for their sophisticated character and timeless design. Each piece has been carefully verified and maintained. Buying a second-hand Saint Laurent wallet means embracing Parisian luxury with confidence and style.',
                'es' => 'Explora nuestra colección de carteras Saint Laurent de segunda mano, donde la herencia de la moda francesa se une a una estética moderna y refinada. En Bags & Tea seleccionamos carteras Saint Laurent originales que destacan por su carácter sofisticado y su diseño atemporal. Cada pieza ha sido verificada y cuidada al detalle. Comprar una cartera Saint Laurent de segunda mano es acceder al lujo parisino con confianza y estilo.'
            ],
            'Goyard' => [
                'en' => 'Second-hand Goyard wallets are true treasures of the most discreet and exclusive luxury. With their iconic hand-painted monogram and century-old heritage, each Goyard wallet reflects distinction, discretion, and impeccable craftsmanship. At Bags & Tea, we offer a selection of original Goyard wallets, carefully verified and chosen for their rarity and condition. Buying a second-hand Goyard wallet is stepping into a selective and deeply artisanal universe.',
                'es' => 'Las carteras Goyard de segunda mano son auténticas joyas del lujo más reservado y exclusivo. Con su emblemático monograma pintado a mano y una historia centenaria, cada cartera Goyard refleja distinción, discreción y artesanía impecable. En Bags & Tea te ofrecemos una selección de carteras Goyard originales, cuidadosamente verificadas y seleccionadas por su rareza y estado. Comprar una cartera Goyard de segunda mano es adentrarse en un universo selecto y profundamente artesanal.'
            ],
        ];

        // Get all brands from the database
        $brands = DB::table('brands')->select('name')->get();

        // Create child categories for each brand
        $displayOrder = 1;
        foreach ($brands as $brand) {
            $brandName = json_decode($brand->name, true);
            $brandNameEn = $brandName['en'];
            $brandNameEs = $brandName['es'];

            // Create slug from brand name
            $slugEn = Str::slug($brandNameEn . ' wallets');
            $slugEs = Str::slug('carteras ' . $brandNameEs);

            // Get brand-specific description or use default
            $description1 = $walletDescriptions[$brandNameEn] ?? [
                'en' => 'Explore our curated selection of ' . $brandNameEn . ' wallets, featuring iconic designs and exceptional craftsmanship.',
                'es' => 'Explora nuestra selección curada de carteras ' . $brandNameEs . ', con diseños icónicos y artesanía excepcional.'
            ];

            DB::table('categories')->insert([
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => $brandNameEn . ' Wallets',
                    'es' => 'Carteras ' . $brandNameEs
                ]),
                'slug' => json_encode([
                    'en' => $slugEn,
                    'es' => $slugEs
                ]),
                'description_1' => json_encode($description1),
                'description_2' => json_encode([
                    'en' => 'Each ' . $brandNameEn . ' wallet represents the pinnacle of luxury and style.',
                    'es' => 'Cada cartera ' . $brandNameEs . ' representa la cúspide del lujo y el estilo.'
                ]),
                'parent_id' => $walletsCategoryId,
                'display_order' => $displayOrder,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $displayOrder++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First get the Wallets parent category ID
        $walletsCategory = DB::table('categories')
            ->whereRaw('JSON_UNQUOTE(JSON_EXTRACT(name, "$.en")) = ?', ['Wallets'])
            ->first();

        if ($walletsCategory) {
            // Delete all children first
            DB::table('categories')
                ->where('parent_id', $walletsCategory->id)
                ->delete();

            // Then delete the parent
            DB::table('categories')
                ->where('id', $walletsCategory->id)
                ->delete();
        }
    }
};
