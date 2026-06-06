<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ─────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@ecavo.com'],
            [
                'name'     => 'Mohamed EL GAROUANI',
                'password' => bcrypt('Admin@1234'),
                'role'     => 'admin',
                'phone'    => '+212600000000',
            ]
        );

        // ── Categories ─────────────────────────────────────────────────
        $categories = [
            ['name_ar' => 'الأجهزة الكهربائية',        'name_en' => 'Appliances',    'name_fr' => 'Électroménager', 'slug' => 'appliances', 'image' => '/images/products/category-1.jpg'],
            ['name_ar' => 'الأدوات المنزلية',           'name_en' => 'Houseware',     'name_fr' => 'Articles ménagers', 'slug' => 'houseware', 'image' => '/images/products/category-2.jpg'],
            ['name_ar' => 'الموبايلات واكسسواراتها',   'name_en' => 'Mobiles',       'name_fr' => 'Mobiles', 'slug' => 'mobiles', 'image' => '/images/products/category-3.jpg'],
            ['name_ar' => 'الملابس',                     'name_en' => 'Clothes',       'name_fr' => 'Vêtements', 'slug' => 'clothes', 'image' => '/images/products/category-1.jpg'],
            ['name_ar' => 'مستحضرات الجمال',            'name_en' => 'Beauty',        'name_fr' => 'Beauté', 'slug' => 'beauty', 'image' => '/images/products/category-2.jpg'],
            ['name_ar' => 'المفروشات',                   'name_en' => 'Furniture',     'name_fr' => 'Meubles', 'slug' => 'furniture', 'image' => '/images/products/category-3.jpg'],
            ['name_ar' => 'التلفزيونات',                 'name_en' => 'TVs',           'name_fr' => 'Téléviseurs', 'slug' => 'tvs', 'image' => '/images/products/category-1.jpg'],
            ['name_ar' => 'الأحذية',                     'name_en' => 'Shoes',         'name_fr' => 'Chaussures', 'slug' => 'shoes', 'image' => '/images/products/category-2.jpg'],
            ['name_ar' => 'اكسسوارات',                   'name_en' => 'Accessories',   'name_fr' => 'Accessoires', 'slug' => 'accessories', 'image' => '/images/products/category-3.jpg'],
            ['name_ar' => 'العاب الأطفال',               'name_en' => 'Toys',          'name_fr' => 'Jouets', 'slug' => 'toys', 'image' => '/images/products/category-1.jpg'],
        ];

        // ── Wipe old tables cleanly ──────────────────────────────────────
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Review::truncate();
        Category::truncate();
        Product::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $createdCats = [];
        foreach ($categories as $i => $cat) {
            $createdCats[$cat['slug']] = Category::create(array_merge($cat, ['sort_order' => $i + 1]));
        }

        // ── Scan the images directory dynamically ──────────────────────
        $imagesPath = public_path('images/products');
        
        // Ensure there are images
        if (!File::exists($imagesPath)) {
            $this->command->error("Directory not found: $imagesPath");
            return;
        }

        $allFiles = File::files($imagesPath);
        
        // Filter only actual product image files (skip generic categories, logos etc if possible, but the prompt says use all for products, so we'll use most of them!)
        $validExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $productFiles = [];
        
        foreach ($allFiles as $file) {
            if (in_array(strtolower($file->getExtension()), $validExtensions)) {
                $productFiles[] = $file;
            }
        }

        $this->command->info("Found " . count($productFiles) . " images to process.");

        foreach ($productFiles as $index => $file) {
            $filename = $file->getFilename();
            // Skip things that are explicitly banners or users
            if (Str::contains($filename, ['user-', 'category-', 'banner', 'logo'])) {
                continue;
            }

            // Figure out a contextual category based on the filename
            $catSlug = 'accessories'; // default
            if (Str::contains($filename, ['shoe'])) $catSlug = 'shoes';
            elseif (Str::contains($filename, ['apparel', 'f1', 'f2', 'f3', 'n1', 'n2', 'n3'])) $catSlug = 'clothes';
            elseif (Str::contains($filename, ['electronic', 'tv'])) $catSlug = 'appliances';
            elseif (Str::contains($filename, ['home', 'house', 'furniture'])) $catSlug = 'furniture';
            elseif (Str::contains($filename, ['mobile', 'phone', 'g3'])) $catSlug = 'mobiles';
            elseif (Str::contains($filename, ['beauty', 'cosmetic', 'parlour'])) $catSlug = 'beauty';

            $category = $createdCats[$catSlug] ?? $createdCats['accessories'];

            // Generate an English name from the filename
            $baseName = pathinfo($filename, PATHINFO_FILENAME);
            $cleanName = Str::headline($baseName); // "Product 10 1" or "Electronic 3"

            // Seed a realistic price
            $originalPrice = rand(20, 500);
            $discountPercent = rand(0, 100) > 30 ? rand(10, 40) : null;
            $price = $discountPercent ? round($originalPrice * (1 - ($discountPercent / 100))) : $originalPrice;

            Product::create([
                'name_en'          => 'Premium ' . $cleanName,
                'name_ar'          => 'منتج ممتاز ' . $cleanName,
                'name_fr'          => 'Produit Premium ' . $cleanName,
                
                'description_en'   => 'Experience the best quality with this ' . $cleanName . '.',
                'description_ar'   => 'اختبر أفضل جودة مع هذا المنتج.',
                'description_fr'   => 'Découvrez la meilleure qualité avec ce produit.',
                
                'price'            => $price,
                'original_price'   => $originalPrice,
                'discount_percent' => $discountPercent,
                'stock'            => rand(5, 100),
                'category_id'      => $category->id,
                'images'           => ['/images/products/' . rawurlencode($filename)],
                
                'is_featured'      => ($index % 5 === 0) ? true : false,
                'deal_ends_at'     => ($index % 8 === 0) ? now()->addDays(rand(1, 7)) : null,
                'slug'             => Str::slug($cleanName) . '-' . Str::random(5),
            ]);
        }

        $this->command->info("All products imported successfully from images directory.");

        // Attach Rich Data (Descriptions, Specs, Reviews) to the new dynamic products
        $this->call(ProductRichDataSeeder::class);
    }
}
