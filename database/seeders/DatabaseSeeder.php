<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ─────────────────────────────────────────────────
        User::create([
            'name'     => 'Mohamed EL GAROUANI',
            'email'    => 'admin@ecavo.com',
            'password' => bcrypt('Admin@1234'),
            'role'     => 'admin',
            'phone'    => '+212600000000',
        ]);

        // ── Categories ─────────────────────────────────────────────────
        $categories = [
            ['name_ar' => 'الأجهزة الكهربائية',        'name_en' => 'Appliances',    'slug' => 'appliances'],
            ['name_ar' => 'الأدوات المنزلية',           'name_en' => 'Houseware',     'slug' => 'houseware'],
            ['name_ar' => 'الموبايلات واكسسواراتها',   'name_en' => 'Mobiles',       'slug' => 'mobiles'],
            ['name_ar' => 'الملابس',                     'name_en' => 'Clothes',       'slug' => 'clothes'],
            ['name_ar' => 'مستحضرات الجمال',            'name_en' => 'Beauty',        'slug' => 'beauty'],
            ['name_ar' => 'المفروشات',                   'name_en' => 'Furniture',     'slug' => 'furniture'],
            ['name_ar' => 'التلفزيونات',                 'name_en' => 'TVs',           'slug' => 'tvs'],
            ['name_ar' => 'الأحذية',                     'name_en' => 'Shoes',         'slug' => 'shoes'],
            ['name_ar' => 'اكسسوارات',                   'name_en' => 'Accessories',   'slug' => 'accessories'],
            ['name_ar' => 'العاب الأطفال',               'name_en' => 'Toys',          'slug' => 'toys'],
        ];

        $createdCats = [];
        foreach ($categories as $i => $cat) {
            $createdCats[] = Category::create(array_merge($cat, ['sort_order' => $i + 1]));
        }

        // ── Sample Products ────────────────────────────────────────────
        $mobilesCategory = collect($createdCats)->firstWhere('slug', 'mobiles');
        $appliancesCategory = collect($createdCats)->firstWhere('slug', 'appliances');

        $sampleProducts = [
            [
                'name_ar'          => 'هاتف أندرويد ذكي ثنائي الشريحة G3',
                'name_en'          => 'Original Mobile Android Dual SIM Smart Phone G3',
                'description_ar'   => 'هاتف ذكي عالي الأداء بشريحتين، شاشة AMOLED 6.5 بوصة، بطارية 5000mAh.',
                'description_en'   => 'High-performance dual SIM smartphone with 6.5" AMOLED display and 5000mAh battery.',
                'price'            => 120.00,
                'original_price'   => 155.00,
                'discount_percent' => 23,
                'stock'            => 50,
                'category_id'      => $mobilesCategory->id,
                'images'           => [
                    '/storage/products/product-1.jpg',
                    '/storage/products/product-2.jpg',
                ],
                'specifications'   => [
                    ['label' => 'الشاشة', 'value' => '6.5 بوصة AMOLED'],
                    ['label' => 'البطارية', 'value' => '5000mAh'],
                    ['label' => 'الذاكرة', 'value' => '128GB'],
                    ['label' => 'الكاميرا', 'value' => '48MP'],
                ],
                'is_featured'   => true,
                'deal_ends_at'  => now()->addDays(3),
                'slug'          => 'android-dual-sim-g3',
            ],
            [
                'name_ar'          => 'غسالة أوتوماتيك 7 كيلو',
                'name_en'          => 'Automatic Washing Machine 7KG',
                'description_ar'   => 'غسالة فعالة بسعة 7 كيلو مع برنامج غسيل متعدد.',
                'description_en'   => 'Energy-efficient 7KG washing machine with multiple wash programs.',
                'price'            => 350.00,
                'original_price'   => 420.00,
                'discount_percent' => 17,
                'stock'            => 15,
                'category_id'      => $appliancesCategory->id,
                'images'           => ['/storage/products/product-3.jpg'],
                'is_featured'      => true,
                'slug'             => 'washing-machine-7kg',
            ],
        ];

        foreach ($sampleProducts as $productData) {
            $slug = $productData['slug'];
            unset($productData['slug']);
            Product::create(array_merge($productData, ['slug' => $slug]));
        }

        // ── Sample Coupon ──────────────────────────────────────────────
        \App\Models\Coupon::create([
            'code'             => 'ECAVO10',
            'discount_type'    => 'percent',
            'value'            => 10,
            'max_uses'         => 100,
            'min_order_amount' => 50,
            'expires_at'       => now()->addMonths(3),
        ]);

        $this->command->info('✅ ECAVO seeded: 1 admin, ' . count($categories) . ' categories, ' . count($sampleProducts) . ' products, 1 coupon.');
    }
}
