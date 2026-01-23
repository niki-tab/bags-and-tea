<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $now = now();

        // Find brand bag parent categories
        $louisVuittonBags = DB::table('categories')
            ->whereRaw("JSON_EXTRACT(name, '$.en') = ?", ['Louis Vuitton Bags'])
            ->first();

        $gucciBags = DB::table('categories')
            ->whereRaw("JSON_EXTRACT(name, '$.en') = ?", ['Gucci Bags'])
            ->first();

        $fendiBags = DB::table('categories')
            ->whereRaw("JSON_EXTRACT(name, '$.en') = ?", ['Fendi Bags'])
            ->first();

        // If parent categories don't exist yet (fresh migration before seeding), skip gracefully
        if (!$louisVuittonBags || !$gucciBags || !$fendiBags) {
            echo "  Skipping bag model categories - parent categories not found yet. Run seeders first, then run this migration again.\n";
            return;
        }

        // =====================================================
        // Create "Bag Models" intermediate parent categories
        // =====================================================

        $lvModelsId = (string) Str::uuid();
        DB::table('categories')->insert([
            'id' => $lvModelsId,
            'name' => json_encode([
                'en' => 'Louis Vuitton Bag Models',
                'es' => 'Modelos Bolsos Louis Vuitton',
            ]),
            'slug' => json_encode([
                'en' => 'louis-vuitton-bag-models',
                'es' => 'modelos-bolsos-louis-vuitton',
            ]),
            'description_1' => json_encode([
                'en' => 'Explore our collection of iconic Louis Vuitton bag models. From the timeless Speedy to the elegant Neverfull, discover authenticated pre-owned pieces from the most coveted Louis Vuitton designs.',
                'es' => 'Explora nuestra colección de icónicos modelos de bolsos Louis Vuitton. Desde el atemporal Speedy hasta el elegante Neverfull, descubre piezas de segunda mano autenticadas de los diseños más codiciados de Louis Vuitton.',
            ]),
            'description_2' => json_encode(['en' => '', 'es' => '']),
            'meta_title' => json_encode([
                'en' => 'Louis Vuitton Bag Models | Shop Iconic LV Handbag Styles',
                'es' => 'Modelos de Bolsos Louis Vuitton | Compra Estilos Icónicos de Bolsos LV',
            ]),
            'meta_description' => json_encode([
                'en' => 'Discover our curated selection of Louis Vuitton bag models. Speedy, Neverfull, Alma, and more iconic designs. Authenticated pre-owned luxury.',
                'es' => 'Descubre nuestra selección de modelos de bolsos Louis Vuitton. Speedy, Neverfull, Alma y más diseños icónicos. Lujo de segunda mano autenticado.',
            ]),
            'parent_id' => $louisVuittonBags->id,
            'display_order' => 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $gucciModelsId = (string) Str::uuid();
        DB::table('categories')->insert([
            'id' => $gucciModelsId,
            'name' => json_encode([
                'en' => 'Gucci Bag Models',
                'es' => 'Modelos Bolsos Gucci',
            ]),
            'slug' => json_encode([
                'en' => 'gucci-bag-models',
                'es' => 'modelos-bolsos-gucci',
            ]),
            'description_1' => json_encode([
                'en' => 'Explore our collection of iconic Gucci bag models. From the legendary Horsebit to the classic Pelham, discover authenticated pre-owned pieces from the most coveted Gucci designs.',
                'es' => 'Explora nuestra colección de icónicos modelos de bolsos Gucci. Desde el legendario Horsebit hasta el clásico Pelham, descubre piezas de segunda mano autenticadas de los diseños más codiciados de Gucci.',
            ]),
            'description_2' => json_encode(['en' => '', 'es' => '']),
            'meta_title' => json_encode([
                'en' => 'Gucci Bag Models | Shop Iconic Gucci Handbag Styles',
                'es' => 'Modelos de Bolsos Gucci | Compra Estilos Icónicos de Bolsos Gucci',
            ]),
            'meta_description' => json_encode([
                'en' => 'Discover our curated selection of Gucci bag models. Horsebit, Pelham, and more iconic designs. Authenticated pre-owned luxury.',
                'es' => 'Descubre nuestra selección de modelos de bolsos Gucci. Horsebit, Pelham y más diseños icónicos. Lujo de segunda mano autenticado.',
            ]),
            'parent_id' => $gucciBags->id,
            'display_order' => 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $fendiModelsId = (string) Str::uuid();
        DB::table('categories')->insert([
            'id' => $fendiModelsId,
            'name' => json_encode([
                'en' => 'Fendi Bag Models',
                'es' => 'Modelos Bolsos Fendi',
            ]),
            'slug' => json_encode([
                'en' => 'fendi-bag-models',
                'es' => 'modelos-bolsos-fendi',
            ]),
            'description_1' => json_encode([
                'en' => 'Explore our collection of iconic Fendi bag models. From the legendary Baguette to timeless classics, discover authenticated pre-owned pieces from the most coveted Fendi designs.',
                'es' => 'Explora nuestra colección de icónicos modelos de bolsos Fendi. Desde el legendario Baguette hasta clásicos atemporales, descubre piezas de segunda mano autenticadas de los diseños más codiciados de Fendi.',
            ]),
            'description_2' => json_encode(['en' => '', 'es' => '']),
            'meta_title' => json_encode([
                'en' => 'Fendi Bag Models | Shop Iconic Fendi Handbag Styles',
                'es' => 'Modelos de Bolsos Fendi | Compra Estilos Icónicos de Bolsos Fendi',
            ]),
            'meta_description' => json_encode([
                'en' => 'Discover our curated selection of Fendi bag models. Baguette and more iconic designs. Authenticated pre-owned luxury.',
                'es' => 'Descubre nuestra selección de modelos de bolsos Fendi. Baguette y más diseños icónicos. Lujo de segunda mano autenticado.',
            ]),
            'parent_id' => $fendiBags->id,
            'display_order' => 1,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // =====================================================
        // Louis Vuitton bag models
        // =====================================================

        $louisVuittonModels = [
            [
                'name_en' => 'Louis Vuitton Speedy 25',
                'name_es' => 'Louis Vuitton Speedy 25',
                'slug_en' => 'louis-vuitton-speedy-25',
                'slug_es' => 'louis-vuitton-speedy-25',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Speedy 25 Bags | Shop Authentic Louis Vuitton Speedy 25 Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Speedy 25 de Segunda Mano | Compra Bolsos Louis Vuitton Speedy 25 Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Speedy 25 bags. Iconic, verified, and exclusive pieces with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Speedy 25 originales de segunda mano. Piezas icónicas, verificadas y exclusivas con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Speedy 25 is an iconic bag that combines sophistication and functionality with a timeless design. Crafted in highly durable monogram canvas, it stands out for its refined leather finishes that add an exclusive and elegant touch. Its spacious and practical interior makes it the ideal choice for both daily use and special events. A must-have classic for fashion lovers, the Speedy 25 elevates any look with unmistakable luxury.',
                'description_1_es' => 'El Speedy 25 de Louis Vuitton es un bolso emblemático que combina sofisticación y funcionalidad con un diseño atemporal. Elaborado en lona monograma de alta resistencia, destaca por sus refinados acabados en piel que aportan un aire exclusivo y elegante. Su interior amplio y práctico lo convierte en la opción ideal tanto para el uso diario como para eventos especiales. Un clásico imprescindible para amantes de la moda, el Speedy 25 eleva cualquier look con un toque de lujo inconfundible.',
            ],
            [
                'name_en' => 'Louis Vuitton Speedy 30',
                'name_es' => 'Louis Vuitton Speedy 30',
                'slug_en' => 'louis-vuitton-speedy-30',
                'slug_es' => 'louis-vuitton-speedy-30',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Speedy 30 Bags | Shop Authentic Louis Vuitton Speedy 30 Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Speedy 30 de Segunda Mano | Compra Bolsos Louis Vuitton Speedy 30 Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Speedy 30 bags. Iconic, verified, and exclusive pieces with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Speedy 30 originales de segunda mano. Piezas icónicas, verificadas y exclusivas con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Speedy 30 is a timeless icon that perfectly balances elegance and practicality. Slightly larger than its sibling, this bag offers ample space without sacrificing style. Made with the signature monogram canvas and luxurious leather trim, the Speedy 30 has been a favorite among fashion enthusiasts for decades. Its versatile design makes it perfect for everyday errands or sophisticated outings.',
                'description_1_es' => 'El Louis Vuitton Speedy 30 es un icono atemporal que equilibra perfectamente elegancia y practicidad. Ligeramente más grande que su hermano, este bolso ofrece amplio espacio sin sacrificar estilo. Fabricado con la distintiva lona monograma y acabados de piel de lujo, el Speedy 30 ha sido favorito entre los entusiastas de la moda durante décadas. Su diseño versátil lo hace perfecto tanto para el día a día como para salidas sofisticadas.',
            ],
            [
                'name_en' => 'Louis Vuitton Noé Petit',
                'name_es' => 'Louis Vuitton Noé Petit',
                'slug_en' => 'louis-vuitton-noe-petit',
                'slug_es' => 'louis-vuitton-noe-petit',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Noé Petit Bags | Shop Authentic Louis Vuitton Petit Noé Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Noé Petit de Segunda Mano | Compra Bolsos Louis Vuitton Petit Noé Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Noé Petit bags. Iconic bucket bags, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Noé Petit originales de segunda mano. Bolsos tipo cubo icónicos, verificados y exclusivos con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Petit Noé is a charming compact version of the legendary bucket bag originally designed to carry champagne bottles. This smaller iteration maintains all the elegance of the original while offering a more manageable size for daily use. With its distinctive drawstring closure and adjustable shoulder strap, the Petit Noé combines heritage craftsmanship with modern convenience.',
                'description_1_es' => 'El Louis Vuitton Petit Noé es una encantadora versión compacta del legendario bolso tipo cubo diseñado originalmente para transportar botellas de champán. Esta versión más pequeña mantiene toda la elegancia del original mientras ofrece un tamaño más manejable para el uso diario. Con su distintivo cierre de cordón y correa de hombro ajustable, el Petit Noé combina artesanía tradicional con conveniencia moderna.',
            ],
            [
                'name_en' => 'Louis Vuitton Noé',
                'name_es' => 'Louis Vuitton Noé',
                'slug_en' => 'louis-vuitton-noe',
                'slug_es' => 'louis-vuitton-noe',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Noé Bags | Shop Authentic Louis Vuitton Noé Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Noé de Segunda Mano | Compra Bolsos Louis Vuitton Noé Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Noé bags. The iconic bucket bag, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Noé originales de segunda mano. El icónico bolso tipo cubo, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Noé is the original bucket bag that started a revolution in handbag design. Created in 1932 to transport champagne bottles, this iconic design has transcended its original purpose to become a symbol of timeless luxury. Crafted in monogram canvas with a spacious interior, the Noé features a leather drawstring closure that adds both functionality and elegance.',
                'description_1_es' => 'El Louis Vuitton Noé es el bolso tipo cubo original que inició una revolución en el diseño de bolsos. Creado en 1932 para transportar botellas de champán, este diseño icónico ha trascendido su propósito original para convertirse en un símbolo de lujo atemporal. Elaborado en lona monograma con un interior espacioso, el Noé presenta un cierre de cordón de piel que añade funcionalidad y elegancia.',
            ],
            [
                'name_en' => 'Louis Vuitton Noé BB',
                'name_es' => 'Louis Vuitton Noé BB',
                'slug_en' => 'louis-vuitton-noe-bb',
                'slug_es' => 'louis-vuitton-noe-bb',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Noé BB Bags | Shop Authentic Louis Vuitton Noé BB Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Noé BB de Segunda Mano | Compra Bolsos Louis Vuitton Noé BB Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Noé BB bags. The mini bucket bag, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Noé BB originales de segunda mano. El mini bolso tipo cubo, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Noé BB is the most compact member of the iconic Noé family. Perfect for those who love the bucket bag silhouette but prefer a mini size, this adorable piece carries all your essentials with undeniable style. Featuring the classic drawstring closure and crossbody strap, the Noé BB brings vintage charm to contemporary fashion.',
                'description_1_es' => 'El Louis Vuitton Noé BB es el miembro más compacto de la icónica familia Noé. Perfecto para quienes aman la silueta del bolso tipo cubo pero prefieren un tamaño mini, esta adorable pieza lleva todos tus esenciales con un estilo innegable. Con el clásico cierre de cordón y correa crossbody, el Noé BB aporta encanto vintage a la moda contemporánea.',
            ],
            [
                'name_en' => 'Louis Vuitton Métis',
                'name_es' => 'Louis Vuitton Métis',
                'slug_en' => 'louis-vuitton-metis',
                'slug_es' => 'louis-vuitton-metis',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Métis Bags | Shop Authentic Louis Vuitton Pochette Métis Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Métis de Segunda Mano | Compra Bolsos Louis Vuitton Pochette Métis Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Métis bags. Iconic satchel style, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Métis originales de segunda mano. Estilo satchel icónico, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Pochette Métis is a modern classic that has captured the hearts of fashion lovers worldwide. This sophisticated satchel features a structured silhouette with the iconic S-lock closure, combining vintage inspiration with contemporary elegance. With multiple carrying options including a removable strap, the Métis transitions effortlessly from day to night.',
                'description_1_es' => 'El Louis Vuitton Pochette Métis es un clásico moderno que ha cautivado los corazones de los amantes de la moda en todo el mundo. Este sofisticado satchel presenta una silueta estructurada con el icónico cierre S-lock, combinando inspiración vintage con elegancia contemporánea. Con múltiples opciones de uso incluyendo correa desmontable, el Métis transiciona sin esfuerzo del día a la noche.',
            ],
            [
                'name_en' => 'Louis Vuitton Neverfull',
                'name_es' => 'Louis Vuitton Neverfull',
                'slug_en' => 'louis-vuitton-neverfull',
                'slug_es' => 'louis-vuitton-neverfull',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Neverfull Bags | Shop Authentic Louis Vuitton Neverfull Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Neverfull de Segunda Mano | Compra Bolsos Louis Vuitton Neverfull Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Neverfull bags. The ultimate tote bag, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Neverfull originales de segunda mano. El tote definitivo, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Neverfull lives up to its name as the ultimate everyday tote. This legendary bag features side laces that can be cinched for a more compact look or loosened for maximum capacity. Crafted in durable monogram canvas with natural leather trim that develops a beautiful patina over time, the Neverfull includes a removable pochette that doubles as a clutch or organizer.',
                'description_1_es' => 'El Louis Vuitton Neverfull hace honor a su nombre como el tote definitivo para el día a día. Este legendario bolso presenta lazos laterales que pueden ajustarse para un look más compacto o soltarse para máxima capacidad. Elaborado en duradera lona monograma con acabados de piel natural que desarrolla una hermosa pátina con el tiempo, el Neverfull incluye un pochette desmontable que funciona como clutch u organizador.',
            ],
            [
                'name_en' => 'Louis Vuitton Favorite',
                'name_es' => 'Louis Vuitton Favorite',
                'slug_en' => 'louis-vuitton-favorite',
                'slug_es' => 'louis-vuitton-favorite',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Favorite Bags | Shop Authentic Louis Vuitton Favorite Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Favorite de Segunda Mano | Compra Bolsos Louis Vuitton Favorite Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Favorite bags. Sleek crossbody style, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Favorite originales de segunda mano. Elegante estilo crossbody, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Favorite is the ultimate sleek crossbody bag for the modern fashionista. This elegant pochette features a slim profile that sits perfectly against the body, making it ideal for hands-free convenience without sacrificing style. Crafted in the iconic monogram canvas with a magnetic snap closure, the Favorite includes a removable chain strap that allows for versatile styling options.',
                'description_1_es' => 'El Louis Vuitton Favorite es el bolso crossbody elegante definitivo para la fashionista moderna. Esta elegante pochette presenta un perfil delgado que se asienta perfectamente contra el cuerpo, haciéndolo ideal para la comodidad de manos libres sin sacrificar estilo. Elaborado en la icónica lona monograma con cierre magnético, el Favorite incluye una correa de cadena desmontable que permite opciones de estilo versátiles.',
            ],
            [
                'name_en' => 'Louis Vuitton Félicie',
                'name_es' => 'Louis Vuitton Félicie',
                'slug_en' => 'louis-vuitton-felicie',
                'slug_es' => 'louis-vuitton-felicie',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Félicie Bags | Shop Authentic Louis Vuitton Félicie Pochette',
                'meta_title_es' => 'Bolsos Louis Vuitton Félicie de Segunda Mano | Compra Pochette Louis Vuitton Félicie Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Félicie pochettes. Versatile and elegant, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra pochettes Louis Vuitton Félicie originales de segunda mano. Versátil y elegante, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Félicie Pochette is a sophisticated three-in-one accessory that epitomizes versatility and elegance. This clever design includes a main envelope-style pochette with two removable inserts: a flat card holder and a zipped pocket. Whether worn as a clutch, crossbody, or shoulder bag with its detachable chain, the Félicie adapts effortlessly to any occasion.',
                'description_1_es' => 'El Louis Vuitton Félicie Pochette es un sofisticado accesorio tres en uno que personifica la versatilidad y la elegancia. Este ingenioso diseño incluye una pochette principal estilo sobre con dos insertos desmontables: un tarjetero plano y un bolsillo con cremallera. Ya sea usado como clutch, crossbody o bolso de hombro con su cadena desmontable, el Félicie se adapta sin esfuerzo a cualquier ocasión.',
            ],
            [
                'name_en' => 'Louis Vuitton Alma',
                'name_es' => 'Louis Vuitton Alma',
                'slug_en' => 'louis-vuitton-alma',
                'slug_es' => 'louis-vuitton-alma',
                'meta_title_en' => 'Pre-Owned Louis Vuitton Alma Bags | Shop Authentic Louis Vuitton Alma Handbags',
                'meta_title_es' => 'Bolsos Louis Vuitton Alma de Segunda Mano | Compra Bolsos Louis Vuitton Alma Auténticos',
                'meta_description_en' => 'Shop original pre-owned Louis Vuitton Alma bags. Iconic dome-shaped silhouette, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Louis Vuitton Alma originales de segunda mano. Icónica silueta abovedada, verificada y exclusiva con total garantía de calidad y estilo.',
                'description_1_en' => 'The Louis Vuitton Alma is a true icon of luxury fashion, featuring a distinctive dome-shaped silhouette that has graced the arms of style icons since its debut. Originally inspired by the Art Deco movement, this structured bag offers a timeless elegance with its clean lines and double zipper closure. Available in various sizes and materials, the Alma seamlessly transitions from day to evening.',
                'description_1_es' => 'El Louis Vuitton Alma es un verdadero icono de la moda de lujo, presentando una distintiva silueta abovedada que ha adornado los brazos de iconos del estilo desde su debut. Originalmente inspirado en el movimiento Art Deco, este bolso estructurado ofrece una elegancia atemporal con sus líneas limpias y doble cierre de cremallera. Disponible en varios tamaños y materiales, el Alma transiciona sin esfuerzo del día a la noche.',
            ],
        ];

        $displayOrder = 100;
        foreach ($louisVuittonModels as $model) {
            DB::table('categories')->insert([
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => $model['name_en'],
                    'es' => $model['name_es'],
                ]),
                'slug' => json_encode([
                    'en' => $model['slug_en'],
                    'es' => $model['slug_es'],
                ]),
                'description_1' => json_encode([
                    'en' => $model['description_1_en'],
                    'es' => $model['description_1_es'],
                ]),
                'description_2' => json_encode(['en' => '', 'es' => '']),
                'meta_title' => json_encode([
                    'en' => $model['meta_title_en'],
                    'es' => $model['meta_title_es'],
                ]),
                'meta_description' => json_encode([
                    'en' => $model['meta_description_en'],
                    'es' => $model['meta_description_es'],
                ]),
                'parent_id' => $lvModelsId,
                'display_order' => $displayOrder,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $displayOrder++;
        }

        // =====================================================
        // Gucci bag models
        // =====================================================

        $gucciModels = [
            [
                'name_en' => 'Gucci Pelham',
                'name_es' => 'Gucci Pelham',
                'slug_en' => 'gucci-pelham',
                'slug_es' => 'gucci-pelham',
                'meta_title_en' => 'Pre-Owned Gucci Pelham Bags | Shop Authentic Gucci Pelham Handbags',
                'meta_title_es' => 'Bolsos Gucci Pelham de Segunda Mano | Compra Bolsos Gucci Pelham Auténticos',
                'meta_description_en' => 'Shop original pre-owned Gucci Pelham bags. Elegant hobo style, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Gucci Pelham originales de segunda mano. Elegante estilo hobo, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Gucci Pelham is a sophisticated hobo bag that exemplifies Italian luxury craftsmanship. Featuring the iconic GG canvas or sumptuous leather with distinctive horsebit details, this bag offers a relaxed yet refined silhouette. Its generous interior and comfortable shoulder strap make the Pelham perfect for those who appreciate understated elegance with everyday functionality.',
                'description_1_es' => 'El Gucci Pelham es un sofisticado bolso hobo que ejemplifica la artesanía de lujo italiana. Con la icónica lona GG o lujosa piel con distintivos detalles de horsebit, este bolso ofrece una silueta relajada pero refinada. Su generoso interior y cómoda correa de hombro hacen del Pelham perfecto para quienes aprecian la elegancia discreta con funcionalidad diaria.',
            ],
            [
                'name_en' => 'Gucci Horsebit',
                'name_es' => 'Gucci Horsebit',
                'slug_en' => 'gucci-horsebit',
                'slug_es' => 'gucci-horsebit',
                'meta_title_en' => 'Pre-Owned Gucci Horsebit Bags | Shop Authentic Gucci Horsebit Handbags',
                'meta_title_es' => 'Bolsos Gucci Horsebit de Segunda Mano | Compra Bolsos Gucci Horsebit Auténticos',
                'meta_description_en' => 'Shop original pre-owned Gucci Horsebit bags. Iconic equestrian-inspired design, verified and exclusive with full assurance of quality and style.',
                'meta_description_es' => 'Compra bolsos Gucci Horsebit originales de segunda mano. Icónico diseño de inspiración ecuestre, verificado y exclusivo con total garantía de calidad y estilo.',
                'description_1_en' => 'The Gucci Horsebit collection represents one of the house\'s most enduring symbols of heritage and craftsmanship. Inspired by equestrian traditions, the distinctive double-ring hardware has graced Gucci designs since 1953. These bags combine classic Italian elegance with bold statement-making details, creating pieces that are both timeless and contemporary.',
                'description_1_es' => 'La colección Gucci Horsebit representa uno de los símbolos más perdurables de herencia y artesanía de la casa. Inspirado en las tradiciones ecuestres, el distintivo herraje de doble anillo ha adornado los diseños de Gucci desde 1953. Estos bolsos combinan la elegancia italiana clásica con detalles audaces, creando piezas que son tanto atemporales como contemporáneas.',
            ],
        ];

        $displayOrder = 100;
        foreach ($gucciModels as $model) {
            DB::table('categories')->insert([
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => $model['name_en'],
                    'es' => $model['name_es'],
                ]),
                'slug' => json_encode([
                    'en' => $model['slug_en'],
                    'es' => $model['slug_es'],
                ]),
                'description_1' => json_encode([
                    'en' => $model['description_1_en'],
                    'es' => $model['description_1_es'],
                ]),
                'description_2' => json_encode(['en' => '', 'es' => '']),
                'meta_title' => json_encode([
                    'en' => $model['meta_title_en'],
                    'es' => $model['meta_title_es'],
                ]),
                'meta_description' => json_encode([
                    'en' => $model['meta_description_en'],
                    'es' => $model['meta_description_es'],
                ]),
                'parent_id' => $gucciModelsId,
                'display_order' => $displayOrder,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $displayOrder++;
        }

        // =====================================================
        // Fendi bag models
        // =====================================================

        DB::table('categories')->insert([
            'id' => (string) Str::uuid(),
            'name' => json_encode([
                'en' => 'Fendi Baguette',
                'es' => 'Fendi Baguette',
            ]),
            'slug' => json_encode([
                'en' => 'fendi-baguette',
                'es' => 'fendi-baguette',
            ]),
            'description_1' => json_encode([
                'en' => 'The Fendi Baguette is one of the most iconic "It" bags in fashion history. Introduced in 1997 by Silvia Venturini Fendi, this compact shoulder bag revolutionized the accessory world and became an instant cult classic. Named for the way it\'s carried under the arm like a French baguette, this bag has been reimagined in countless materials, colors, and embellishments while maintaining its signature shape and distinctive FF clasp.',
                'es' => 'El Fendi Baguette es uno de los bolsos "It" más icónicos en la historia de la moda. Introducido en 1997 por Silvia Venturini Fendi, este compacto bolso de hombro revolucionó el mundo de los accesorios y se convirtió instantáneamente en un clásico de culto. Nombrado por la forma en que se lleva bajo el brazo como una baguette francesa, este bolso ha sido reimaginado en innumerables materiales, colores y adornos manteniendo su forma característica y distintivo cierre FF.',
            ]),
            'description_2' => json_encode(['en' => '', 'es' => '']),
            'meta_title' => json_encode([
                'en' => 'Pre-Owned Fendi Baguette Bags | Shop Authentic Fendi Baguette Handbags',
                'es' => 'Bolsos Fendi Baguette de Segunda Mano | Compra Bolsos Fendi Baguette Auténticos',
            ]),
            'meta_description' => json_encode([
                'en' => 'Shop original pre-owned Fendi Baguette bags. The iconic "It" bag, verified and exclusive with full assurance of quality and style.',
                'es' => 'Compra bolsos Fendi Baguette originales de segunda mano. El icónico bolso "It", verificado y exclusivo con total garantía de calidad y estilo.',
            ]),
            'parent_id' => $fendiModelsId,
            'display_order' => 100,
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete all bag model categories
        $slugsToDelete = [
            // Louis Vuitton models
            'louis-vuitton-speedy-25',
            'louis-vuitton-speedy-30',
            'louis-vuitton-noe-petit',
            'louis-vuitton-noe',
            'louis-vuitton-noe-bb',
            'louis-vuitton-metis',
            'louis-vuitton-neverfull',
            'louis-vuitton-favorite',
            'louis-vuitton-felicie',
            'louis-vuitton-alma',
            // Gucci models
            'gucci-pelham',
            'gucci-horsebit',
            // Fendi models
            'fendi-baguette',
            // Parent categories
            'louis-vuitton-bag-models',
            'gucci-bag-models',
            'fendi-bag-models',
        ];

        foreach ($slugsToDelete as $slug) {
            DB::table('categories')
                ->whereRaw("JSON_EXTRACT(slug, '$.en') = ?", [$slug])
                ->delete();
        }
    }
};
