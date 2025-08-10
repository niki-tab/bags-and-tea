<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\ProductModel;
use Illuminate\Database\Seeder;
use Src\Shared\Domain\Criteria\Order;
use Src\Shared\Domain\Criteria\Filter;
use Src\Shared\Domain\Criteria\Filters;
use Src\Shared\Domain\Criteria\Criteria;
use App\Models\ProductSizeVariationModel;
use Src\Shared\Domain\Criteria\FilterField;
use Src\Shared\Domain\Criteria\FilterValue;
use App\Models\ProductQuantityVariationModel;
use Src\Shared\Domain\Criteria\FilterOperator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Src\Products\Product\Application\AddCategoryToProduct;
use Src\Products\Product\Application\AddAttributeToProduct;
use Src\Vendors\Infrastructure\Eloquent\VendorEloquentModel;
use Src\Categories\Infrastructure\EloquentCategoryRepository;
use App\Models\ProducSizeVariationQuantityVariationPriceModel;
use Src\Attributes\Infrastructure\EloquentAttributeRepository;
use Src\Products\Product\Infrastructure\EloquentProductRepository;
use Src\Products\Product\Infrastructure\Eloquent\ProductMediaModel;
use Src\Products\Product\Infrastructure\Eloquent\ProductEloquentModel;
use Src\Products\Quality\Infrastructure\Eloquent\QualityEloquentModel;

class FakeEnvironmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // Get vendors for assignment
        $vendors = VendorEloquentModel::all();
        if ($vendors->isEmpty()) {
            $this->command->error('No vendors found. Please run VendorSeeder first.');
            return;
        }

        $quality1 = QualityEloquentModel::where('code', 'AB')->first();
        $quality1Id = $quality1->id;

        $quality2 = QualityEloquentModel::where('code', 'A')->first();
        $quality2Id = $quality2->id;

        $quality3 = QualityEloquentModel::where('code', 'B')->first();
        $quality3Id = $quality3->id;

        // Get Louis Vuitton brand ID - now using JSON slug format
        $louisVuittonBrand = \DB::table('brands')->where('slug', 'like', '%louis-vuitton%')->first();
        $louisVuittonBrandId = $louisVuittonBrand->id;
        
        $bag1 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Speedy 25",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedente de Francia. Oceano Atlántico",
            'description_2' => "La vieira es un marisco delicado y sabroso, apreciado por su textura suave y su sabor único. En Rutas del Mar, te ofrecemos vieiras francesas frescas y de la mejor calidad, traídas directamente desde las costas más puras. Ideal para quienes buscan un toque gourmet en sus platos, la vieira es el manjar perfecto del mar",
            'slug' => "speedy-25",
            'origin_country' => "FR",
            'quality_id' => $quality1Id,
            'product_type' => "simple",
            'price' => 585,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => true,
            'is_sold_out' => true,
            'featured' => true,
            'featured_position' => 1,

        ]);

        $bag1
        ->setTranslation('name', 'en', 'Speedy 25 Bag')
        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'The scallop is a delicate and flavorful seafood, appreciated for its smooth texture and unique taste. At Rutas del Mar, we offer fresh French scallops of the highest quality, brought directly from the purest coasts. Ideal for those looking for a gourmet touch in their dishes, the scallop is the perfect seafood delicacy.')
        ->setTranslation('slug', 'en', 'speedy-25')
        ->save();

        $bag1CrabProductSizeVariation1 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag1->id,
            'size_name' => "Vieira Mediana",
            'size_description' => "Vieira Mediana",
            'order' => 1,
        ]);

        $bag1CrabProductSizeVariation1
        ->setTranslation('size_name', 'en', 'Medium Size Scallop')
        ->setTranslation('size_description', 'en', 'Medium Size Scallop')
        ->save();

        $bag1CrabProductSizeVariation2 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag1->id,
            'size_name' => "Vieira Grande",
            'size_description' => "Vieira Grande",
            'order' => 2,
        ]);
        
        $bag1CrabProductSizeVariation2
        ->setTranslation('size_name', 'en', 'Big Size Scallop')
        ->setTranslation('size_description', 'en', 'Big Size Scallop')
        ->save();

        $bag1VariationPrices1 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag1->id,
            'product_size_variation_id' => $bag1CrabProductSizeVariation1->id,
            'product_quantity_variation_id' => null,
            'sale_price' => 3.50,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $bag1VariationPrices2 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag1->id,
            'product_size_variation_id' => $bag1CrabProductSizeVariation2->id,
            'product_quantity_variation_id' => null,
            'sale_price' => 4.00,
            'discounted_price' => null,
            'currency' => "€",
        ]);




        $bag2 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Noe",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedente de Francia. Oceano Atlántico",
            'description_2' => "La centolla de Francia es sinónimo de calidad y sabor refinado. Un producto excepcional que puedes disfrutar en cualquier momento del año. En Rutas del Mar, traemos directamente desde las costas francesas las centollas más frescas, seleccionadas cuidadosamente para ofrecerte lo mejor del mar. La centolla francesa es una alternativa exquisita a la centolla gallega.",
            'slug' => "noe",
            'origin_country' => "ES",
            'quality_id' => $quality2Id,
            'product_type' => "simple",
            'price' => 550,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => true,
            'is_sold_out' => true,
            'featured' => true,
            'featured_position' => 2,

        ]);

        $bag2
        ->setTranslation('name', 'en', 'Noe Bag')
        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'The French spider crab is synonymous with quality and refined flavor. An exceptional product that you can enjoy at any time of the year. At Rutas del Mar, we bring the freshest spider crabs directly from the French coasts, carefully selected to offer you the best of the sea. The French spider crab is an exquisite alternative to the Galician spider crab.')
        ->setTranslation('slug', 'en', 'noe')
        ->save();

        $bag2ProductSizeVariation1 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag2->id,
            'size_name' => "Centolla Mediana (0,8-1unit/pieza)",
            'size_description' => "Centolla Mediana (0.8-1unit/pieza)",
            'order' => 1,
        ]);
        
        $bag2ProductSizeVariation1
        ->setTranslation('size_name', 'en', 'Medium Size Spider Crab (1-1,3unit/piece)')
        ->setTranslation('size_description', 'en', 'Medium Size Spider Crab (1-1,3unit/piece)')
        ->save();

        $bag2ProductSizeVariation2 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag2->id,
            'size_name' => "Centolla Grande (1-1,3unit/pieza)",
            'size_description' => "Centolla Grande (1-1,3unit/pieza)",
            'order' => 2,
        ]);
        
        $bag2ProductSizeVariation2
        ->setTranslation('size_name', 'en', 'Big Size Spider Crab (1-1,3unit/piece)')
        ->setTranslation('size_description', 'en', 'Big Size Spider Crab (1-1,3unit/piece)')
        ->save();

        $bag2ProductSizeVariation3 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag2->id,
            'size_name' => "Centolla XL (1,3-1,5unit/pieza)",
            'size_description' => "Centolla XL (1,3-1,5unit/pieza)",
            'order' => 3,
        ]);
        
        $bag2ProductSizeVariation3
        ->setTranslation('size_name', 'en', 'XL Spider Crab (1,3-1,5unit/piece)')
        ->setTranslation('size_description', 'en', 'XL Spider Crab (1,3-1,5unit/piece)')
        ->save();

        $bag2VariationPrices1 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag2->id,
            'product_size_variation_id' => $bag2ProductSizeVariation1->id,
            'product_quantity_variation_id' => null,
            'sale_price' => 23.90,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $bag2VariationPrices2 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag2->id,
            'product_size_variation_id' => $bag2ProductSizeVariation2->id,
            'product_quantity_variation_id' => null,
            'sale_price' => 28.90,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $bag2VariationPrices3 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag2->id,
            'product_size_variation_id' => $bag2ProductSizeVariation3->id,
            'product_quantity_variation_id' => null,
            'sale_price' => 33.90,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $bag3 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Looping GM",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedentes de Francia. Oceano Atlántico",
            'description_2' => "Nuestras ostras frescas son cuidadosamente recolectadas por cultivadores apasionados, quienes mantienen viva la tradición de la recolección sostenible directamente del océano. Estas ostras se destacan por su sabor pronunciado, equilibrado con un toque salino que realza su textura suave y carnosa. La elección perfecta para los amantes del marisco. Disfruta de la frescura y autenticidad de estas joyas marinas, con entrega a domicilio en menos de 24 horas, garantizando su calidad y sabor en su mejor momento.",
            //'description_2' => "La ostra francesa es un verdadero placer para los amantes del marisco, conocida por su delicado sabor y textura inconfundible. <br>En Rutas del Mar, contamos con las ostras de los mejores cultivadores franceses para que disfrutes de este manjar todo el año. Con su frescura incomparable, la ostra francesa se convierte en una opción excepcional para cualquier ocasión especial o para los paladares más exigentes.",
            'slug' => "looping-gm",
            'origin_country' => "FR",
            'quality_id' => $quality3Id,
            'product_type' => "simple",
            'price' => 390,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => false,
            'is_sold_out' => true,
            'featured' => true,
            'featured_position' => 3,

        ]);

        $bag3
        ->setTranslation('name', 'en', 'Looping GM Bag')

        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'Our fresh oysters are carefully harvested by passionate cultivators who uphold the tradition of sustainable gathering directly from the ocean. These oysters stand out for their pronounced flavor, balanced with a salty touch that enhances their smooth and meaty texture. The perfect choice for seafood lovers. Enjoy the freshness and authenticity of these marine treasures, with home delivery in less than 24 hours, ensuring their quality and flavor at their best.')
        //->setTranslation('description_2', 'en', 'The French oyster is a true delight for seafood lovers, known for its delicate flavor and unmistakable texture. <br>At Rutas del Mar, we source oysters from the finest French farmers so you can enjoy this delicacy all year round. With its unparalleled freshness, the French oyster becomes an exceptional option for any special occasion or for the most discerning palates.')
        ->setTranslation('slug', 'en', 'looping-gm')
        ->save();

        $oisterProductSizeVariation1 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'size_name' => "Talla 1",
            'size_description' => "Talla 1",
            'order' => 1,
        ]);
        
        $oisterProductSizeVariation1
        ->setTranslation('size_name', 'en', 'Size 1')
        ->setTranslation('size_description', 'en', 'Size 1')
        ->save();

        $oisterProductSizeVariation2 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'size_name' => "Talla 2",
            'size_description' => "Talla 2",
            'order' => 2,
        ]);
        
        $oisterProductSizeVariation2
        ->setTranslation('size_name', 'en', 'Size 2')
        ->setTranslation('size_description', 'en', 'Size 2')
        ->save();

        $oisterProductSizeVariation3 = ProductSizeVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'size_name' => "Talla 3",
            'size_description' => "Talla 3",
            'order' => 3,
        ]);
        
        $oisterProductSizeVariation3
        ->setTranslation('size_name', 'en', 'Size 3')
        ->setTranslation('size_description', 'en', 'Size 3')
        ->save();
        


        $oisterProductQuantityVariationModel1 = ProductQuantityVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'quantity_name' => "Caja 12 unidades",
            'quantity_description' => "Caja 12 unidades",
            'order' => 1,
        ]);
        
        $oisterProductQuantityVariationModel1
        ->setTranslation('quantity_name', 'en', 'Box 12 units')
        ->setTranslation('quantity_description', 'en', 'Box 12 units')
        ->save();

        $oisterProductQuantityVariationModel2 = ProductQuantityVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'quantity_name' => "Caja 24 unidades",
            'quantity_description' => "Caja 24 unidades",
            'order' => 2,
        ]);
        
        $oisterProductQuantityVariationModel2
        ->setTranslation('quantity_name', 'en', 'Box 24 units')
        ->setTranslation('quantity_description', 'en', 'Box 24 units')
        ->save();

        $oisterProductQuantityVariationModel3 = ProductQuantityVariationModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'quantity_name' => "Caja 36 unidades",
            'quantity_description' => "Caja 36 unidades",
            'order' => 3,
        ]);
        
        $oisterProductQuantityVariationModel3
        ->setTranslation('quantity_name', 'en', 'Box 36 units')
        ->setTranslation('quantity_description', 'en', 'Box 36 units')
        ->save();

        $oisterVariationPrices1 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation1->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel1->id,
            'sale_price' => 15.6,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices2 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation1->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel2->id,
            'sale_price' => 31.2,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices3 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation1->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel3->id,
            'sale_price' => 46.8,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices4 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation2->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel1->id,
            'sale_price' => 15.6,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices5 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation2->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel2->id,
            'sale_price' => 31.2,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices6 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation2->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel3->id,
            'sale_price' => 46.8,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices7 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation3->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel1->id,
            'sale_price' => 15.6,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices8 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation3->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel2->id,
            'sale_price' => 31.2,
            'discounted_price' => null,
            'currency' => "€",
        ]);

        $oisterVariationPrices9 = ProducSizeVariationQuantityVariationPriceModel::create([
            'id' => (string) Str::uuid(),
            'product_id' => $bag3->id,
            'product_size_variation_id' => $oisterProductSizeVariation3->id,
            'product_quantity_variation_id' => $oisterProductQuantityVariationModel3->id,
            'sale_price' => 46.8,
            'discounted_price' => null,
            'currency' => "€",
        ]);


        /*$grouper = ProductModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Mero",
            'description_1' => "Procedente de España. Oceano Atlántico",
            'description_2' => "El mero es un manjar del mar, apreciado por su carne firme y sabor suave, ideal para los paladares más exigentes. En Rutas del Mar, seleccionamos los mejores ejemplares de mero de las costas españolas, garantizando frescura y calidad en cada pieza. Este pescado versátil es perfecto para una gran variedad de preparaciones culinarias y está disponible durante todo el año para que puedas disfrutar de su inigualable sabor en cualquier momento.",
            'slug' => "mero-islas-canarias",
            'origin_country' => "España",
            'product_type' => "Simple",
            'price' => 25,
            'sell_mode_quantity' => 1,
            'sell_mode' => "per-kilo",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => true,
            'featured' => true,

        ]);
        
        $grouper
        ->setTranslation('name', 'en', 'Grouper')
        ->setTranslation('description_1', 'en', 'Sourced from Spain. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'The grouper is a delicacy of the sea, appreciated for its firm flesh and mild flavor, perfect for the most discerning palates. At Rutas del Mar, we select the finest grouper specimens from the Spanish coasts, ensuring freshness and quality in every piece. This versatile fish is ideal for a wide variety of culinary preparations and is available year-round, so you can enjoy its unparalleled flavor at any time.')
        ->setTranslation('slug', 'en', 'grouper-canary-islands')
        ->save();*/

        $bag4 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Alma",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedentes de Francia. Oceano Atlántico",
            'description_2' => "Nuestras ostras frescas son cuidadosamente recolectadas por cultivadores apasionados, quienes mantienen viva la tradición de la recolección sostenible directamente del océano. Estas ostras se destacan por su sabor pronunciado, equilibrado con un toque salino que realza su textura suave y carnosa. La elección perfecta para los amantes del marisco. Disfruta de la frescura y autenticidad de estas joyas marinas, con entrega a domicilio en menos de 24 horas, garantizando su calidad y sabor en su mejor momento.",
            //'description_2' => "La ostra francesa es un verdadero placer para los amantes del marisco, conocida por su delicado sabor y textura inconfundible. <br>En Rutas del Mar, contamos con las ostras de los mejores cultivadores franceses para que disfrutes de este manjar todo el año. Con su frescura incomparable, la ostra francesa se convierte en una opción excepcional para cualquier ocasión especial o para los paladares más exigentes.",
            'slug' => "alma",
            'origin_country' => "FR",
            'quality_id' => $quality1Id,
            'product_type' => "simple",
            'price' => 0.10,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => false,
            'is_sold_out' => false,
            'featured' => true,
            'featured_position' => 4,

        ]);

        $bag4
        ->setTranslation('name', 'en', 'Alma Bag')
        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'Our fresh oysters are carefully harvested by passionate cultivators who uphold the tradition of sustainable gathering directly from the ocean. These oysters stand out for their pronounced flavor, balanced with a salty touch that enhances their smooth and meaty texture. The perfect choice for seafood lovers. Enjoy the freshness and authenticity of these marine treasures, with home delivery in less than 24 hours, ensuring their quality and flavor at their best.')
        ->setTranslation('slug', 'en', 'alma')
        ->save();

        $bag5 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Petit Noe Epi",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedentes de Francia. Oceano Atlántico",
            'description_2' => "Nuestras ostras frescas son cuidadosamente recolectadas por cultivadores apasionados, quienes mantienen viva la tradición de la recolección sostenible directamente del océano. Estas ostras se destacan por su sabor pronunciado, equilibrado con un toque salino que realza su textura suave y carnosa. La elección perfecta para los amantes del marisco. Disfruta de la frescura y autenticidad de estas joyas marinas, con entrega a domicilio en menos de 24 horas, garantizando su calidad y sabor en su mejor momento.",
            //'description_2' => "La ostra francesa es un verdadero placer para los amantes del marisco, conocida por su delicado sabor y textura inconfundible. <br>En Rutas del Mar, contamos con las ostras de los mejores cultivadores franceses para que disfrutes de este manjar todo el año. Con su frescura incomparable, la ostra francesa se convierte en una opción excepcional para cualquier ocasión especial o para los paladares más exigentes.",
            'slug' => "petit-noe-epi-red",
            'origin_country' => "FR",
            'quality_id' => $quality2Id,
            'product_type' => "simple",
            'price' => 0.20,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => false,
            'is_sold_out' => false,
            'featured' => true,
            'featured_position' => 5,

        ]);

        $bag5
        ->setTranslation('name', 'en', 'Petit Noe Epi Red Bag')
        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'Our fresh oysters are carefully harvested by passionate cultivators who uphold the tradition of sustainable gathering directly from the ocean. These oysters stand out for their pronounced flavor, balanced with a salty touch that enhances their smooth and meaty texture. The perfect choice for seafood lovers. Enjoy the freshness and authenticity of these marine treasures, with home delivery in less than 24 hours, ensuring their quality and flavor at their best.')
        ->setTranslation('slug', 'en', 'petit-noe-epi-red')
        ->save();

        $bag6 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Speedy 25",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedentes de Francia. Oceano Atlántico",
            'description_2' => "Nuestras ostras frescas son cuidadosamente recolectadas por cultivadores apasionados, quienes mantienen viva la tradición de la recolección sostenible directamente del océano. Estas ostras se destacan por su sabor pronunciado, equilibrado con un toque salino que realza su textura suave y carnosa. La elección perfecta para los amantes del marisco. Disfruta de la frescura y autenticidad de estas joyas marinas, con entrega a domicilio en menos de 24 horas, garantizando su calidad y sabor en su mejor momento.",
            //'description_2' => "La ostra francesa es un verdadero placer para los amantes del marisco, conocida por su delicado sabor y textura inconfundible. <br>En Rutas del Mar, contamos con las ostras de los mejores cultivadores franceses para que disfrutes de este manjar todo el año. Con su frescura incomparable, la ostra francesa se convierte en una opción excepcional para cualquier ocasión especial o para los paladares más exigentes.",
            'slug' => "speedy-25-2",
            'origin_country' => "FR",
            'quality_id' => $quality3Id,
            'product_type' => "simple",
            'price' => 550,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => false,
            'is_sold_out' => true,
            'featured' => true,
            'featured_position' => 6,

        ]);

        $bag6
        ->setTranslation('name', 'en', 'Petit Noe Epi Red Bag')

        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'Our fresh oysters are carefully harvested by passionate cultivators who uphold the tradition of sustainable gathering directly from the ocean. These oysters stand out for their pronounced flavor, balanced with a salty touch that enhances their smooth and meaty texture. The perfect choice for seafood lovers. Enjoy the freshness and authenticity of these marine treasures, with home delivery in less than 24 hours, ensuring their quality and flavor at their best.')
        ->setTranslation('slug', 'en', 'speedy-25-2')
        ->save();

        $bag7 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Looping PM",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedentes de Francia. Oceano Atlántico",
            'description_2' => "Nuestras ostras frescas son cuidadosamente recolectadas por cultivadores apasionados, quienes mantienen viva la tradición de la recolección sostenible directamente del océano. Estas ostras se destacan por su sabor pronunciado, equilibrado con un toque salino que realza su textura suave y carnosa. La elección perfecta para los amantes del marisco. Disfruta de la frescura y autenticidad de estas joyas marinas, con entrega a domicilio en menos de 24 horas, garantizando su calidad y sabor en su mejor momento.",
            //'description_2' => "La ostra francesa es un verdadero placer para los amantes del marisco, conocida por su delicado sabor y textura inconfundible. <br>En Rutas del Mar, contamos con las ostras de los mejores cultivadores franceses para que disfrutes de este manjar todo el año. Con su frescura incomparable, la ostra francesa se convierte en una opción excepcional para cualquier ocasión especial o para los paladares más exigentes.",
            'slug' => "looping-pm",
            'origin_country' => "FR",
            'quality_id' => $quality1Id,
            'product_type' => "simple",
            'status' => "pending-review",
            'price' => 380,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => false,
            'is_sold_out' => true,
            'featured' => true,
            'featured_position' => 7,

        ]);

        $bag7
        ->setTranslation('name', 'en', 'Looping PM Bag')

        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'Our fresh oysters are carefully harvested by passionate cultivators who uphold the tradition of sustainable gathering directly from the ocean. These oysters stand out for their pronounced flavor, balanced with a salty touch that enhances their smooth and meaty texture. The perfect choice for seafood lovers. Enjoy the freshness and authenticity of these marine treasures, with home delivery in less than 24 hours, ensuring their quality and flavor at their best.')
        ->setTranslation('slug', 'en', 'looping-pm')
        ->save();

        $bag8 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Noe",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedentes de Francia. Oceano Atlántico",
            'description_2' => "Nuestras ostras frescas son cuidadosamente recolectadas por cultivadores apasionados, quienes mantienen viva la tradición de la recolección sostenible directamente del océano. Estas ostras se destacan por su sabor pronunciado, equilibrado con un toque salino que realza su textura suave y carnosa. La elección perfecta para los amantes del marisco. Disfruta de la frescura y autenticidad de estas joyas marinas, con entrega a domicilio en menos de 24 horas, garantizando su calidad y sabor en su mejor momento.",
            //'description_2' => "La ostra francesa es un verdadero placer para los amantes del marisco, conocida por su delicado sabor y textura inconfundible. <br>En Rutas del Mar, contamos con las ostras de los mejores cultivadores franceses para que disfrutes de este manjar todo el año. Con su frescura incomparable, la ostra francesa se convierte en una opción excepcional para cualquier ocasión especial o para los paladares más exigentes.",
            'slug' => "noe-1",
            'origin_country' => "FR",
            'quality_id' => $quality2Id,
            'product_type' => "simple",
            'price' => 570,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => false,
            'is_sold_out' => true,
            'featured' => true,
            'featured_position' => 8,

        ]);

        $bag8
        ->setTranslation('name', 'en', 'Noe Bag')

        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'Our fresh oysters are carefully harvested by passionate cultivators who uphold the tradition of sustainable gathering directly from the ocean. These oysters stand out for their pronounced flavor, balanced with a salty touch that enhances their smooth and meaty texture. The perfect choice for seafood lovers. Enjoy the freshness and authenticity of these marine treasures, with home delivery in less than 24 hours, ensuring their quality and flavor at their best.')
        ->setTranslation('slug', 'en', 'noe-1')
        ->save();

        $bag9 = ProductEloquentModel::create([

            'id' => (string) Str::uuid(),
            'name' => "Bolso Alma",
            'brand_id' => $louisVuittonBrandId,
            'vendor_id' => $vendors->random()->id,
            'description_1' => "Procedentes de Francia. Oceano Atlántico",
            'description_2' => "Nuestras ostras frescas son cuidadosamente recolectadas por cultivadores apasionados, quienes mantienen viva la tradición de la recolección sostenible directamente del océano. Estas ostras se destacan por su sabor pronunciado, equilibrado con un toque salino que realza su textura suave y carnosa. La elección perfecta para los amantes del marisco. Disfruta de la frescura y autenticidad de estas joyas marinas, con entrega a domicilio en menos de 24 horas, garantizando su calidad y sabor en su mejor momento.",
            //'description_2' => "La ostra francesa es un verdadero placer para los amantes del marisco, conocida por su delicado sabor y textura inconfundible. <br>En Rutas del Mar, contamos con las ostras de los mejores cultivadores franceses para que disfrutes de este manjar todo el año. Con su frescura incomparable, la ostra francesa se convierte en una opción excepcional para cualquier ocasión especial o para los paladares más exigentes.",
            'slug' => "alma-2",
            'origin_country' => "FR",
            'quality_id' => $quality3Id,
            'product_type' => "simple",
            'price' => 400,
            'sell_mode_quantity' => 1,
            'sell_mode' => "unit",
            'stock' => 1,
            'stock_unit' => "unit",
            'out_of_stock' => false,
            'is_sold_out' => true,
            'featured' => true,
            'featured_position' => 9,

        ]);

        $bag9
        ->setTranslation('name', 'en', 'Alma Bag')
        ->setTranslation('description_1', 'en', 'Sourced from France. Atlantic Ocean')
        ->setTranslation('description_2', 'en', 'Our fresh oysters are carefully harvested by passionate cultivators who uphold the tradition of sustainable gathering directly from the ocean. These oysters stand out for their pronounced flavor, balanced with a salty touch that enhances their smooth and meaty texture. The perfect choice for seafood lovers. Enjoy the freshness and authenticity of these marine treasures, with home delivery in less than 24 hours, ensuring their quality and flavor at their best.')
        ->setTranslation('slug', 'en', 'alma-2')
        ->save();

        // Assign random materials to products
        $this->assignRandomMaterials([
            $bag1->id,
            $bag2->id, 
            $bag3->id,
            $bag4->id,
            $bag5->id,
            $bag6->id,
            $bag7->id,
            $bag8->id,
            $bag9->id
        ]);

        // Assign random bag types to products
        $this->assignRandomBagTypes([
            $bag1->id,
            $bag2->id, 
            $bag3->id,
            $bag4->id,
            $bag5->id,
            $bag6->id,
            $bag7->id,
            $bag8->id,
            $bag9->id
        ]);

        // Assign random manufacture year to products
        $this->assignRandomManufacturedYears([
            $bag1->id,
            $bag2->id, 
            $bag3->id,
            $bag4->id,
            $bag5->id,
            $bag6->id,
            $bag7->id,
            $bag8->id,
            $bag9->id
        ]);

        // Assign louis vuitton category
        $this->assignLouisVuittonCategory([
            $bag1->id,
            $bag2->id, 
            $bag3->id,
            $bag4->id,
            $bag5->id,
            $bag6->id,
            $bag7->id,
            $bag8->id,
            $bag9->id
        ]);

        // Create product media entries for all products
        $this->createProductMediaEntries([
            ['product' => $bag1, 'image_path' => 'images/product/speedy-25-imp-3-1.webp'],
            ['product' => $bag2, 'image_path' => 'images/product/noe-imp-3-1.webp'],
            ['product' => $bag3, 'image_path' => 'images/product/looping-gm-imp-3-1.webp'],
            ['product' => $bag4, 'image_path' => 'images/product/alma-imp-3-1.webp'],
            ['product' => $bag5, 'image_path' => 'images/product/petit-noe-epi-red-imp-3-1.webp'],
            ['product' => $bag6, 'image_path' => 'images/product/speedy-25-imp-3-2.webp'],
            ['product' => $bag7, 'image_path' => 'images/product/looping-pm-imp-3-1.webp'],
            ['product' => $bag8, 'image_path' => 'images/product/noe-imp-3-2.webp'],
            ['product' => $bag9, 'image_path' => 'images/product/alma-imp-3-2.webp']
        ]);
    }

    /**
     * Assign random materials to products
     */
    private function assignRandomMaterials(array $productIds): void
    {
        $categoryRepository = new EloquentCategoryRepository();
        $categories = $categoryRepository->findByParentName('Material');
        
        // If no materials found, return early
        if (empty($categories)) {
            return;
        }

        foreach ($productIds as $productId) {
            // Get one random material from the categories
            $randomMaterial = collect($categories)->random();
            
            // Manually create dependencies instead of using DI container
            $productRepository = new EloquentProductRepository();
            $addCategoryToProductUseCase = new AddCategoryToProduct(
                $productRepository,
                $categoryRepository
            );
            
            $addCategoryToProductUseCase->execute(
                $productId,
                $randomMaterial->id
            );
        }
    }

    /**
     * Assign random bag types to products
     */
    private function assignRandomBagTypes(array $productIds): void
    {
        $categoryRepository = new EloquentCategoryRepository();
        $categories = $categoryRepository->findByParentName('Bag Type');
        
        // If no bag types found, return early
        if (empty($categories)) {
            return;
        }

        foreach ($productIds as $productId) {
            // Get one random bag type from the categories
            $randomBagType = collect($categories)->random();
            
            // Manually create dependencies instead of using DI container
            $productRepository = new EloquentProductRepository();
            $addCategoryToProductUseCase = new AddCategoryToProduct(
                $productRepository,
                $categoryRepository
            );
            
            $addCategoryToProductUseCase->execute(
                $productId,
                $randomBagType->id
            );
        }
    }

    private function assignRandomManufacturedYears(array $productIds): void
    {
        $attributeRepository = new EloquentAttributeRepository();
        $attributes = $attributeRepository->findByParentName('Year of manufacture');
        
        // If no attributes found, return early
        if (empty($attributes) || $attributes === null) {

            return;
        }

        foreach ($productIds as $productId) {
            // Get one random bag type from the categories
            $randomManufacturedYear = collect($attributes)->random();
            
            // Manually create dependencies instead of using DI container
            $productRepository = new EloquentProductRepository();
            $addAttributeToProductUseCase = new AddAttributeToProduct(
                $productRepository,
                $attributeRepository
            );
            
            $addAttributeToProductUseCase->execute(
                $productId,
                $randomManufacturedYear->id
            );
        }
    }

    private function assignLouisVuittonCategory(array $productIds): void
    {
        $categoryRepository = new EloquentCategoryRepository();

        $filters = [
            new Filter(
                new FilterField('name->en'),
                new FilterOperator('='),
                new FilterValue('Louis Vuitton Bags')
            )
        ];

        $criteria = new Criteria(new Filters($filters), Order::none(), null, null);
        $category = $categoryRepository->searchByCriteria($criteria);

        // If no attributes found, return early
        if (empty($category) || $category === null) {

            return;
        }

        $categoryId = $category[0]->id;

        foreach ($productIds as $productId) {

            // Manually create dependencies instead of using DI container
            $productRepository = new EloquentProductRepository();
            $addCategoryToProductUseCase = new AddCategoryToProduct(
                $productRepository,
                $categoryRepository
            );
            
            $addCategoryToProductUseCase->execute(
                $productId,
                $categoryId
            );
        }
    }

    /**
     * Create product media entries for products
     */
    private function createProductMediaEntries(array $productData): void
    {
        foreach ($productData as $data) {
            $product = $data['product'];
            $imagePath = $data['image_path'];
            $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
            
            // Determine MIME type based on file extension
            $mimeType = match($extension) {
                'webp' => 'image/webp',
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                default => 'image/webp'
            };
            
            ProductMediaModel::create([
                'id' => (string) Str::uuid(),
                'product_id' => $product->id,
                'file_path' => $imagePath,
                'file_name' => basename($imagePath),
                'file_type' => 'image',
                'mime_type' => $mimeType,
                'file_size' => 150000, // Default 150KB for seeder data
                'sort_order' => 1,
                'is_primary' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
