<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, we need to create the Brand model, but since it doesn't exist yet,
        // we'll use DB facade for now
        $brands = [
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Louis Vuitton',
                    'es' => 'Louis Vuitton'
                ]),
                'slug' => json_encode([
                    'en' => 'louis-vuitton',
                    'es' => 'louis-vuitton'
                ]),
                'description_1' => json_encode([
                    'en' => 'Discover the world of Louis Vuitton, French luxury leather goods, fashion and accessories.',
                    'es' => 'Descubre el mundo de Louis Vuitton, artículos de lujo franceses de piel, moda y accesorios.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Exceptional craftsmanship and timeless elegance since 1854.',
                    'es' => 'Artesanía excepcional y elegancia atemporal desde 1854.'
                ]),
                'logo_url' => null,
                'display_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Chanel',
                    'es' => 'Chanel'
                ]),
                'slug' => json_encode([
                    'en' => 'chanel',
                    'es' => 'chanel'
                ]),
                'description_1' => json_encode([
                    'en' => 'CHANEL luxury fashion, fragrance, makeup and skincare.',
                    'es' => 'CHANEL moda de lujo, fragancias, maquillaje y cuidado de la piel.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Timeless elegance and revolutionary spirit.',
                    'es' => 'Elegancia atemporal y espíritu revolucionario.'
                ]),
                'logo_url' => null,
                'display_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Hermès',
                    'es' => 'Hermès'
                ]),
                'slug' => json_encode([
                    'en' => 'hermes',
                    'es' => 'hermes'
                ]),
                'description_1' => json_encode([
                    'en' => 'Hermès leather goods, silk scarves, watches, perfumes and luxury accessories.',
                    'es' => 'Artículos de piel Hermès, pañuelos de seda, relojes, perfumes y accesorios de lujo.'
                ]),
                'description_2' => json_encode([
                    'en' => 'French luxury craftsmanship since 1837.',
                    'es' => 'Artesanía francesa de lujo desde 1837.'
                ]),
                'logo_url' => null,
                'display_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Gucci',
                    'es' => 'Gucci'
                ]),
                'slug' => json_encode([
                    'en' => 'gucci',
                    'es' => 'gucci'
                ]),
                'description_1' => json_encode([
                    'en' => 'Gucci luxury fashion and leather goods.',
                    'es' => 'Gucci moda de lujo y artículos de piel.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Italian luxury since 1921.',
                    'es' => 'Lujo italiano desde 1921.'
                ]),
                'logo_url' => null,
                'display_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Prada',
                    'es' => 'Prada'
                ]),
                'slug' => json_encode([
                    'en' => 'prada',
                    'es' => 'prada'
                ]),
                'description_1' => json_encode([
                    'en' => 'Prada luxury fashion, leather goods and accessories.',
                    'es' => 'Prada moda de lujo, artículos de piel y accesorios.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Innovation meets tradition.',
                    'es' => 'La innovación se encuentra con la tradición.'
                ]),
                'logo_url' => null,
                'display_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Christian Dior',
                    'es' => 'Christian Dior'
                ]),
                'slug' => json_encode([
                    'en' => 'christian-dior',
                    'es' => 'christian-dior'
                ]),
                'description_1' => json_encode([
                    'en' => 'Dior luxury fashion, perfumes and accessories.',
                    'es' => 'Dior moda de lujo, perfumes y accesorios.'
                ]),
                'description_2' => json_encode([
                    'en' => 'French elegance since 1946.',
                    'es' => 'Elegancia francesa desde 1946.'
                ]),
                'logo_url' => null,
                'display_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Fendi',
                    'es' => 'Fendi'
                ]),
                'slug' => json_encode([
                    'en' => 'fendi',
                    'es' => 'fendi'
                ]),
                'description_1' => json_encode([
                    'en' => 'Fendi luxury fashion and leather goods.',
                    'es' => 'Fendi moda de lujo y artículos de piel.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Roman luxury since 1925.',
                    'es' => 'Lujo romano desde 1925.'
                ]),
                'logo_url' => null,
                'display_order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Saint Laurent',
                    'es' => 'Saint Laurent'
                ]),
                'slug' => json_encode([
                    'en' => 'saint-laurent',
                    'es' => 'saint-laurent'
                ]),
                'description_1' => json_encode([
                    'en' => 'Saint Laurent luxury fashion and accessories.',
                    'es' => 'Saint Laurent moda de lujo y accesorios.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Parisian luxury and rock \'n\' roll spirit.',
                    'es' => 'Lujo parisino y espíritu rock \'n\' roll.'
                ]),
                'logo_url' => null,
                'display_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Balenciaga',
                    'es' => 'Balenciaga'
                ]),
                'slug' => json_encode([
                    'en' => 'balenciaga',
                    'es' => 'balenciaga'
                ]),
                'description_1' => json_encode([
                    'en' => 'Balenciaga luxury fashion and avant-garde design.',
                    'es' => 'Balenciaga moda de lujo y diseño vanguardista.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Spanish couture and innovation.',
                    'es' => 'Couture español e innovación.'
                ]),
                'logo_url' => null,
                'display_order' => 9,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Bottega Veneta',
                    'es' => 'Bottega Veneta'
                ]),
                'slug' => json_encode([
                    'en' => 'bottega-veneta',
                    'es' => 'bottega-veneta'
                ]),
                'description_1' => json_encode([
                    'en' => 'Bottega Veneta luxury leather goods and fashion.',
                    'es' => 'Bottega Veneta artículos de lujo de piel y moda.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Italian craftsmanship and discretion.',
                    'es' => 'Artesanía italiana y discreción.'
                ]),
                'logo_url' => null,
                'display_order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Céline',
                    'es' => 'Céline'
                ]),
                'slug' => json_encode([
                    'en' => 'celine',
                    'es' => 'celine'
                ]),
                'description_1' => json_encode([
                    'en' => 'Céline luxury fashion and leather goods.',
                    'es' => 'Céline moda de lujo y artículos de piel.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Parisian elegance and modernity.',
                    'es' => 'Elegancia parisina y modernidad.'
                ]),
                'logo_url' => null,
                'display_order' => 11,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Loewe',
                    'es' => 'Loewe'
                ]),
                'slug' => json_encode([
                    'en' => 'loewe',
                    'es' => 'loewe'
                ]),
                'description_1' => json_encode([
                    'en' => 'Loewe luxury leather goods and fashion.',
                    'es' => 'Loewe artículos de lujo de piel y moda.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Spanish luxury since 1846.',
                    'es' => 'Lujo español desde 1846.'
                ]),
                'logo_url' => null,
                'display_order' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Miu Miu',
                    'es' => 'Miu Miu'
                ]),
                'slug' => json_encode([
                    'en' => 'miu-miu',
                    'es' => 'miu-miu'
                ]),
                'description_1' => json_encode([
                    'en' => 'Miu Miu luxury fashion and accessories.',
                    'es' => 'Miu Miu moda de lujo y accesorios.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Playful luxury and sophistication.',
                    'es' => 'Lujo divertido y sofisticación.'
                ]),
                'logo_url' => null,
                'display_order' => 13,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Goyard',
                    'es' => 'Goyard'
                ]),
                'slug' => json_encode([
                    'en' => 'goyard',
                    'es' => 'goyard'
                ]),
                'description_1' => json_encode([
                    'en' => 'Goyard luxury trunks and leather goods.',
                    'es' => 'Goyard baúles de lujo y artículos de piel.'
                ]),
                'description_2' => json_encode([
                    'en' => 'French craftsmanship since 1853.',
                    'es' => 'Artesanía francesa desde 1853.'
                ]),
                'logo_url' => null,
                'display_order' => 14,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => (string) Str::uuid(),
                'name' => json_encode([
                    'en' => 'Polène',
                    'es' => 'Polène'
                ]),
                'slug' => json_encode([
                    'en' => 'polene',
                    'es' => 'polene'
                ]),
                'description_1' => json_encode([
                    'en' => 'Polène contemporary luxury handbags.',
                    'es' => 'Polène bolsos de lujo contemporáneos.'
                ]),
                'description_2' => json_encode([
                    'en' => 'Modern Spanish leather craftsmanship.',
                    'es' => 'Artesanía moderna española en piel.'
                ]),
                'logo_url' => null,
                'display_order' => 15,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($brands as $brand) {
            \DB::table('brands')->insert($brand);
        }
    }
}