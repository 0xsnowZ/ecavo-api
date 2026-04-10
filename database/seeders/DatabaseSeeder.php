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
            ['name_ar' => 'الأجهزة الكهربائية',        'name_en' => 'Appliances',    'name_fr' => 'Électroménager', 'slug' => 'appliances'],
            ['name_ar' => 'الأدوات المنزلية',           'name_en' => 'Houseware',     'name_fr' => 'Articles ménagers', 'slug' => 'houseware'],
            ['name_ar' => 'الموبايلات واكسسواراتها',   'name_en' => 'Mobiles',       'name_fr' => 'Mobiles', 'slug' => 'mobiles'],
            ['name_ar' => 'الملابس',                     'name_en' => 'Clothes',       'name_fr' => 'Vêtements', 'slug' => 'clothes'],
            ['name_ar' => 'مستحضرات الجمال',            'name_en' => 'Beauty',        'name_fr' => 'Beauté', 'slug' => 'beauty'],
            ['name_ar' => 'المفروشات',                   'name_en' => 'Furniture',     'name_fr' => 'Meubles', 'slug' => 'furniture'],
            ['name_ar' => 'التلفزيونات',                 'name_en' => 'TVs',           'name_fr' => 'Téléviseurs', 'slug' => 'tvs'],
            ['name_ar' => 'الأحذية',                     'name_en' => 'Shoes',         'name_fr' => 'Chaussures', 'slug' => 'shoes'],
            ['name_ar' => 'اكسسوارات',                   'name_en' => 'Accessories',   'name_fr' => 'Accessoires', 'slug' => 'accessories'],
            ['name_ar' => 'العاب الأطفال',               'name_en' => 'Toys',          'name_fr' => 'Jouets', 'slug' => 'toys'],
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
                'name_ar'          => 'منتج إيكو الصديق للبيئة 10',
                'name_en'          => 'Premium Eco Product 10',
                'name_fr'          => 'Produit Écologique Premium 10',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 18,
                'original_price'   => 23,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-10.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-10',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 11',
                'name_en'          => 'Premium Eco Product 11',
                'name_fr'          => 'Produit Écologique Premium 11',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 25,
                'original_price'   => 32,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-11.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-11',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 12',
                'name_en'          => 'Premium Eco Product 12',
                'name_fr'          => 'Produit Écologique Premium 12',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 30,
                'original_price'   => 39,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-12.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-12',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 13',
                'name_en'          => 'Premium Eco Product 13',
                'name_fr'          => 'Produit Écologique Premium 13',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 45,
                'original_price'   => 58,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-13.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-13',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 14',
                'name_en'          => 'Premium Eco Product 14',
                'name_fr'          => 'Produit Écologique Premium 14',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 20,
                'original_price'   => 26,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-14.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-14',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 15',
                'name_en'          => 'Premium Eco Product 15',
                'name_fr'          => 'Produit Écologique Premium 15',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 35,
                'original_price'   => 45,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-15.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-15',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 16',
                'name_en'          => 'Premium Eco Product 16',
                'name_fr'          => 'Produit Écologique Premium 16',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 45,
                'original_price'   => 58,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-16.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-16',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 4',
                'name_en'          => 'Premium Eco Product 4',
                'name_fr'          => 'Produit Écologique Premium 4',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 15,
                'original_price'   => 19,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-4.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-4',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 5',
                'name_en'          => 'Premium Eco Product 5',
                'name_fr'          => 'Produit Écologique Premium 5',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 35,
                'original_price'   => 45,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-5.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-5',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 6',
                'name_en'          => 'Premium Eco Product 6',
                'name_fr'          => 'Produit Écologique Premium 6',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 18,
                'original_price'   => 23,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-6.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-6',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 7',
                'name_en'          => 'Premium Eco Product 7',
                'name_fr'          => 'Produit Écologique Premium 7',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 15,
                'original_price'   => 19,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-7.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-7',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 8',
                'name_en'          => 'Premium Eco Product 8',
                'name_fr'          => 'Produit Écologique Premium 8',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 30,
                'original_price'   => 39,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-8.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-8',
            ],
            [
                'name_ar'          => 'منتج إيكو الصديق للبيئة 9',
                'name_en'          => 'Premium Eco Product 9',
                'name_fr'          => 'Produit Écologique Premium 9',
                'description_ar'   => 'High quality eco-friendly product.',
                'description_en'   => 'High quality eco-friendly product.',
                'description_fr'   => 'High quality eco-friendly product. (FR)',
                'price'            => 35,
                'original_price'   => 45,
                'discount_percent' => 15,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        '/storage/products/product-9.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'eco-product-9',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 1',
                'name_en'          => 'Featured Floral Shirt 1',
                'name_fr'          => 'Chemise florale en vedette 1',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 35,
                'original_price'   => 45,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f1.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f1',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 2',
                'name_en'          => 'Featured Floral Shirt 2',
                'name_fr'          => 'Chemise florale en vedette 2',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 25,
                'original_price'   => 32,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f2.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f2',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 3',
                'name_en'          => 'Featured Floral Shirt 3',
                'name_fr'          => 'Chemise florale en vedette 3',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 18,
                'original_price'   => 23,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f3.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f3',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 4',
                'name_en'          => 'Featured Floral Shirt 4',
                'name_fr'          => 'Chemise florale en vedette 4',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 15,
                'original_price'   => 19,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f4.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f4',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 5',
                'name_en'          => 'Featured Floral Shirt 5',
                'name_fr'          => 'Chemise florale en vedette 5',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 10,
                'original_price'   => 13,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f5.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f5',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 6',
                'name_en'          => 'Featured Floral Shirt 6',
                'name_fr'          => 'Chemise florale en vedette 6',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 55,
                'original_price'   => 71,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f6.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f6',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 7',
                'name_en'          => 'Featured Floral Shirt 7',
                'name_fr'          => 'Chemise florale en vedette 7',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 55,
                'original_price'   => 71,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f7.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f7',
            ],
            [
                'name_ar'          => 'قميص مشجر مميز 8',
                'name_en'          => 'Featured Floral Shirt 8',
                'name_fr'          => 'Chemise florale en vedette 8',
                'description_ar'   => 'Comfortable floral shirt, ideal for summer.',
                'description_en'   => 'Comfortable floral shirt, ideal for summer.',
                'description_fr'   => 'Comfortable floral shirt, ideal for summer. (FR)',
                'price'            => 35,
                'original_price'   => 45,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/f8.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-f8',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 1',
                'name_en'          => 'New Arrival Summer Wear 1',
                'name_fr'          => 'Nouvelle collection d\'été 1',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 12,
                'original_price'   => 15,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n1.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n1',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 2',
                'name_en'          => 'New Arrival Summer Wear 2',
                'name_fr'          => 'Nouvelle collection d\'été 2',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 30,
                'original_price'   => 39,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n2.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n2',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 3',
                'name_en'          => 'New Arrival Summer Wear 3',
                'name_fr'          => 'Nouvelle collection d\'été 3',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 35,
                'original_price'   => 45,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n3.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n3',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 4',
                'name_en'          => 'New Arrival Summer Wear 4',
                'name_fr'          => 'Nouvelle collection d\'été 4',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 35,
                'original_price'   => 45,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n4.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n4',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 5',
                'name_en'          => 'New Arrival Summer Wear 5',
                'name_fr'          => 'Nouvelle collection d\'été 5',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 55,
                'original_price'   => 71,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n5.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n5',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 6',
                'name_en'          => 'New Arrival Summer Wear 6',
                'name_fr'          => 'Nouvelle collection d\'été 6',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 45,
                'original_price'   => 58,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n6.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n6',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 7',
                'name_en'          => 'New Arrival Summer Wear 7',
                'name_fr'          => 'Nouvelle collection d\'été 7',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 55,
                'original_price'   => 71,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n7.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n7',
            ],
            [
                'name_ar'          => 'تشكيلة الصيف الجديدة 8',
                'name_en'          => 'New Arrival Summer Wear 8',
                'name_fr'          => 'Nouvelle collection d\'été 8',
                'description_ar'   => 'Fresh new arrival for the summer collection.',
                'description_en'   => 'Fresh new arrival for the summer collection.',
                'description_fr'   => 'Fresh new arrival for the summer collection. (FR)',
                'price'            => 15,
                'original_price'   => 19,
                'discount_percent' => 20,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/n8.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'summer-item-n8',
            ],
            [
                'name_ar'          => 'test',
                'name_en'          => 'test',
                'name_fr'          => 'test (FR)',
                'description_ar'   => '',
                'description_en'   => '',
                'description_fr'   => ' (FR)',
                'price'            => 100,
                'original_price'   => 90,
                'discount_percent' => null,
                'stock'            => 5,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        '/storage/products/3d3e0456-c415-4db1-b910-15d5c481bcac.jpg',
                        '/storage/products/456c09aa-7909-4bbc-be9f-3c94a3876762.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'test-KpGjF',
            ],
            [
                'name_ar'          => 'zefz',
                'name_en'          => 'formula',
                'name_fr'          => 'formula (FR)',
                'description_ar'   => '',
                'description_en'   => '',
                'description_fr'   => ' (FR)',
                'price'            => 5,
                'original_price'   => 10,
                'discount_percent' => null,
                'stock'            => 1,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'appliances')->id,
                'images'           => [
                        'http://localhost:8001/storage/products/64f9ef35-8f41-4d39-8a5d-5624f5dad4d3.jpg',
                        'http://localhost:8001/storage/products/616ccbe1-8353-4ffc-98da-f9394e7bf983.jpg'
                ],
                'is_featured'      => false,
                'slug'             => 'formula-caeXj',
            ],
            [
                'name_ar'          => 'هاتف أندرويد ذكي ثنائي الشريحة G3',
                'name_en'          => 'Android Dual SIM Smart Phone G3',
                'name_fr'          => 'Android Dual SIM Smart Phone G3 (FR)',
                'description_ar'   => 'هاتف ذكي عالي الأداء بشريحتين، شاشة AMOLED 6.5 بوصة، بطارية 5000mAh.',
                'description_en'   => 'High-performance dual SIM smartphone with 6.5" AMOLED and 5000mAh battery.',
                'description_fr'   => 'High-performance dual SIM smartphone with 6.5" AMOLED and 5000mAh battery. (FR)',
                'price'            => 120,
                'original_price'   => 155,
                'discount_percent' => 23,
                'stock'            => 50,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'mobiles')->id,
                'images'           => [
                        '/storage/products/product-1.jpg',
                        '/storage/products/product-2.jpg'
                ],
                'specifications'   => [
                    ['label' => 'الشاشة', 'value' => '6.5 بوصة AMOLED'],
                    ['label' => 'البطارية', 'value' => '5000mAh'],
                    ['label' => 'الذاكرة', 'value' => '128GB'],
                    ['label' => 'الكاميرا', 'value' => '48MP'],
                ],
                'is_featured'      => true,
                'deal_ends_at'     => \Carbon\Carbon::parse('2026-04-13 01:31:55'),
                'slug'             => 'android-dual-sim-g3',
            ],
            [
                'name_ar'          => 'هاتف سامسونج جالاكسي A54',
                'name_en'          => 'Samsung Galaxy A54 5G Smartphone',
                'name_fr'          => 'Samsung Galaxy A54 5G Smartphone (FR)',
                'description_ar'   => 'شاشة Super AMOLED 6.4 بوصة، كاميرا 50MP، ذاكرة 128GB.',
                'description_en'   => '6.4" Super AMOLED, 50MP camera, 128GB storage, 5G ready.',
                'description_fr'   => '6.4" Super AMOLED, 50MP camera, 128GB storage, 5G ready. (FR)',
                'price'            => 249,
                'original_price'   => 299,
                'discount_percent' => 17,
                'stock'            => 30,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'mobiles')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600&q=80',
                        'https://images.unsplash.com/photo-1565536421961-1f6a44c10bf6?w=600&q=80'
                ],
                'is_featured'      => true,
                'deal_ends_at'     => \Carbon\Carbon::parse('2026-04-15 01:31:55'),
                'slug'             => 'samsung-galaxy-a54',
            ],
            [
                'name_ar'          => 'سماعات لاسلكية بلوتوث Pro',
                'name_en'          => 'Wireless Bluetooth Earbuds Pro',
                'name_fr'          => 'Wireless Bluetooth Earbuds Pro (FR)',
                'description_ar'   => 'صوت نقي، إلغاء الضجيج النشط، بطارية 30 ساعة.',
                'description_en'   => 'Pure sound, active noise cancellation, 30-hour battery life.',
                'description_fr'   => 'Pure sound, active noise cancellation, 30-hour battery life. (FR)',
                'price'            => 45,
                'original_price'   => 75,
                'discount_percent' => 40,
                'stock'            => 100,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'mobiles')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600&q=80',
                        'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'wireless-earbuds-pro',
            ],
            [
                'name_ar'          => 'شاحن لاسلكي سريع 20W',
                'name_en'          => 'Fast Wireless Charger 20W',
                'name_fr'          => 'Fast Wireless Charger 20W (FR)',
                'description_ar'   => 'شاحن لاسلكي سريع متوافق مع جميع الهواتف.',
                'description_en'   => 'Fast wireless charger compatible with all Qi devices.',
                'description_fr'   => 'Fast wireless charger compatible with all Qi devices. (FR)',
                'price'            => 18,
                'original_price'   => 30,
                'discount_percent' => 40,
                'stock'            => 200,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1601972599748-ef8a8a2d0f3c?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'wireless-charger-20w',
            ],
            [
                'name_ar'          => 'كابل USB-C سريع 2م',
                'name_en'          => 'Fast USB-C Cable 2m Braided',
                'name_fr'          => 'Fast USB-C Cable 2m Braided (FR)',
                'description_ar'   => 'كابل مجدول متين بطول متري وشحن سريع 60W.',
                'description_en'   => 'Durable braided 2m cable supporting 60W fast charging.',
                'description_fr'   => 'Durable braided 2m cable supporting 60W fast charging. (FR)',
                'price'            => 8,
                'original_price'   => 15,
                'discount_percent' => 47,
                'stock'            => 500,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1612198273689-a27f6a6dca82?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'usb-c-cable-2m',
            ],
            [
                'name_ar'          => 'حافظة هاتف جلد فاخر',
                'name_en'          => 'Premium Leather Phone Case',
                'name_fr'          => 'Premium Leather Phone Case (FR)',
                'description_ar'   => 'حافظة جلد طبيعي فاخرة بجيب للبطاقات.',
                'description_en'   => 'Genuine leather case with card slot, premium finish.',
                'description_fr'   => 'Genuine leather case with card slot, premium finish. (FR)',
                'price'            => 22,
                'original_price'   => 35,
                'discount_percent' => 37,
                'stock'            => 80,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1541877590-a1c5dba8a42c?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'premium-leather-case',
            ],
            [
                'name_ar'          => 'غسالة أوتوماتيك 7 كيلو',
                'name_en'          => 'Automatic Washing Machine 7KG',
                'name_fr'          => 'Automatic Washing Machine 7KG (FR)',
                'description_ar'   => 'غسالة فعالة بسعة 7 كيلو مع برامج غسيل متعددة.',
                'description_en'   => 'Energy-efficient 7KG washing machine with multiple wash programs.',
                'description_fr'   => 'Energy-efficient 7KG washing machine with multiple wash programs. (FR)',
                'price'            => 350,
                'original_price'   => 420,
                'discount_percent' => 17,
                'stock'            => 15,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'appliances')->id,
                'images'           => [
                        '/storage/products/product-3.jpg'
                ],
                'is_featured'      => true,
                'slug'             => 'washing-machine-7kg',
            ],
            [
                'name_ar'          => 'مكيف سبليت 12000 BTU إنفرتر',
                'name_en'          => 'Inverter Split AC 12000 BTU',
                'name_fr'          => 'Inverter Split AC 12000 BTU (FR)',
                'description_ar'   => 'مكيف انفرتر موفر للطاقة بقدرة تبريد عالية.',
                'description_en'   => 'Energy-saving inverter AC with high cooling capacity.',
                'description_fr'   => 'Energy-saving inverter AC with high cooling capacity. (FR)',
                'price'            => 480,
                'original_price'   => 580,
                'discount_percent' => 17,
                'stock'            => 10,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'appliances')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'inverter-split-ac-12000',
            ],
            [
                'name_ar'          => 'مكنسة كهربائية لاسلكية',
                'name_en'          => 'Cordless Vacuum Cleaner',
                'name_fr'          => 'Cordless Vacuum Cleaner (FR)',
                'description_ar'   => 'مكنسة خفيفة وقوية بشفط 22kPa وبطارية 60 دقيقة.',
                'description_en'   => 'Lightweight powerful vacuum, 22kPa suction, 60-min battery.',
                'description_fr'   => 'Lightweight powerful vacuum, 22kPa suction, 60-min battery. (FR)',
                'price'            => 95,
                'original_price'   => 130,
                'discount_percent' => 27,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'appliances')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1558317374-067fb5f30001?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'cordless-vacuum-cleaner',
            ],
            [
                'name_ar'          => 'فرن كهربائي متعدد الوظائف 45L',
                'name_en'          => 'Multi-Function Electric Oven 45L',
                'name_fr'          => 'Multi-Function Electric Oven 45L (FR)',
                'description_ar'   => 'فرن كهربائي 45 لتر لطهي الأطباق المتنوعة.',
                'description_en'   => '45L electric oven with grill, convection and bake modes.',
                'description_fr'   => '45L electric oven with grill, convection and bake modes. (FR)',
                'price'            => 85,
                'original_price'   => 110,
                'discount_percent' => 23,
                'stock'            => 20,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'appliances')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'multi-function-oven-45l',
            ],
            [
                'name_ar'          => 'تلفزيون سمارت 55 بوصة 4K OLED',
                'name_en'          => '55" 4K OLED Smart TV',
                'name_fr'          => '55" 4K OLED Smart TV (FR)',
                'description_ar'   => 'شاشة OLED 55 بوصة 4K مع نظام Dolby Atmos وSmartThings.',
                'description_en'   => '55" 4K OLED, Dolby Atmos, SmartThings built-in.',
                'description_fr'   => '55" 4K OLED, Dolby Atmos, SmartThings built-in. (FR)',
                'price'            => 699,
                'original_price'   => 899,
                'discount_percent' => 22,
                'stock'            => 8,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'tvs')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1593359677879-a4bb92f4284e?w=600&q=80',
                        'https://images.unsplash.com/photo-1571415060716-baff5f717c6d?w=600&q=80'
                ],
                'is_featured'      => true,
                'deal_ends_at'     => \Carbon\Carbon::parse('2026-04-17 01:31:55'),
                'slug'             => 'oled-smart-tv-55',
            ],
            [
                'name_ar'          => 'تلفزيون LED 43 بوصة Full HD',
                'name_en'          => '43" Full HD LED Smart TV',
                'name_fr'          => '43" Full HD LED Smart TV (FR)',
                'description_ar'   => 'شاشة LED 43 بوصة Full HD مع Android TV.',
                'description_en'   => '43" Full HD LED with Android TV, Netflix & YouTube built-in.',
                'description_fr'   => '43" Full HD LED with Android TV, Netflix & YouTube built-in. (FR)',
                'price'            => 199,
                'original_price'   => 249,
                'discount_percent' => 20,
                'stock'            => 20,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'tvs')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1601944177325-f8867652837f?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'led-tv-43-fhd',
            ],
            [
                'name_ar'          => 'تيشيرت قطن بريميوم للرجال',
                'name_en'          => 'Men\'s Premium Cotton T-Shirt',
                'name_fr'          => 'Men\'s Premium Cotton T-Shirt (FR)',
                'description_ar'   => 'تيشيرت قطن 100% مريح بألوان متعددة.',
                'description_en'   => '100% premium cotton, comfortable fit, multiple colors.',
                'description_fr'   => '100% premium cotton, comfortable fit, multiple colors. (FR)',
                'price'            => 15,
                'original_price'   => 22,
                'discount_percent' => 32,
                'stock'            => 150,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600&q=80',
                        'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'mens-premium-tshirt',
            ],
            [
                'name_ar'          => 'جاكيت شتوي دافئ للرجال',
                'name_en'          => 'Men\'s Winter Warm Jacket',
                'name_fr'          => 'Men\'s Winter Warm Jacket (FR)',
                'description_ar'   => 'جاكيت شتوي دافئ بحشوة قطن وقماش مقاوم للماء.',
                'description_en'   => 'Warm winter jacket with cotton fill and water-resistant shell.',
                'description_fr'   => 'Warm winter jacket with cotton fill and water-resistant shell. (FR)',
                'price'            => 55,
                'original_price'   => 75,
                'discount_percent' => 27,
                'stock'            => 40,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1548883354-94bcfe321cbb?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'mens-winter-jacket',
            ],
            [
                'name_ar'          => 'فستان صيفي للنساء - زهري',
                'name_en'          => 'Women\'s Summer Floral Dress',
                'name_fr'          => 'Women\'s Summer Floral Dress (FR)',
                'description_ar'   => 'فستان صيفي خفيف بنقشة زهرية أنيقة.',
                'description_en'   => 'Light floral summer dress, elegant and breathable.',
                'description_fr'   => 'Light floral summer dress, elegant and breathable. (FR)',
                'price'            => 28,
                'original_price'   => 42,
                'discount_percent' => 33,
                'stock'            => 60,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'clothes')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'womens-summer-dress',
            ],
            [
                'name_ar'          => 'حذاء رياضي نايك AIR MAX',
                'name_en'          => 'Nike AIR MAX Sport Shoes',
                'name_fr'          => 'Nike AIR MAX Sport Shoes (FR)',
                'description_ar'   => 'حذاء رياضي مريح مناسب للجري واللياقة البدنية.',
                'description_en'   => 'Comfortable sport shoe ideal for running and fitness.',
                'description_fr'   => 'Comfortable sport shoe ideal for running and fitness. (FR)',
                'price'            => 75,
                'original_price'   => 110,
                'discount_percent' => 32,
                'stock'            => 35,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'shoes')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&q=80',
                        'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'nike-air-max-sport',
            ],
            [
                'name_ar'          => 'حذاء كلاسيكي جلد للرجال',
                'name_en'          => 'Men\'s Classic Leather Oxford Shoes',
                'name_fr'          => 'Men\'s Classic Leather Oxford Shoes (FR)',
                'description_ar'   => 'حذاء أكسفورد جلد طبيعي للمناسبات الرسمية.',
                'description_en'   => 'Genuine leather Oxford shoes for formal occasions.',
                'description_fr'   => 'Genuine leather Oxford shoes for formal occasions. (FR)',
                'price'            => 60,
                'original_price'   => 85,
                'discount_percent' => 29,
                'stock'            => 25,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'shoes')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1614252369475-531eba835eb1?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'men-leather-oxford',
            ],
            [
                'name_ar'          => 'كرسي مكتبي مريح Ergonomic',
                'name_en'          => 'Ergonomic Office Chair',
                'name_fr'          => 'Ergonomic Office Chair (FR)',
                'description_ar'   => 'كرسي مكتبي مريح مع دعم قطني وارتفاع قابل للضبط.',
                'description_en'   => 'Comfortable ergonomic chair with lumbar support and adjustable height.',
                'description_fr'   => 'Comfortable ergonomic chair with lumbar support and adjustable height. (FR)',
                'price'            => 120,
                'original_price'   => 160,
                'discount_percent' => 25,
                'stock'            => 12,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'furniture')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1580480055273-228ff5388ef8?w=600&q=80',
                        'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'ergonomic-office-chair',
            ],
            [
                'name_ar'          => 'مجموعة عناية بالبشرة الكورية',
                'name_en'          => 'Korean Skin Care Set 6-Piece',
                'name_fr'          => 'Korean Skin Care Set 6-Piece (FR)',
                'description_ar'   => 'مجموعة عناية كاملة من 6 قطع للبشرة المشرقة.',
                'description_en'   => 'Complete 6-piece Korean skin care set for glowing skin.',
                'description_fr'   => 'Complete 6-piece Korean skin care set for glowing skin. (FR)',
                'price'            => 38,
                'original_price'   => 55,
                'discount_percent' => 31,
                'stock'            => 45,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'beauty')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=600&q=80',
                        'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'korean-skincare-set',
            ],
            [
                'name_ar'          => 'عطر رجالي بلاك أوبيوم EDP',
                'name_en'          => 'Black Opium EDP Perfume 100ml',
                'name_fr'          => 'Black Opium EDP Perfume 100ml (FR)',
                'description_ar'   => 'عطر فاخر بنوتات القهوة والفانيليا، 100ml.',
                'description_en'   => 'Luxury fragrance with coffee & vanilla notes, 100ml EDP.',
                'description_fr'   => 'Luxury fragrance with coffee & vanilla notes, 100ml EDP. (FR)',
                'price'            => 65,
                'original_price'   => 90,
                'discount_percent' => 28,
                'stock'            => 30,
                'category_id'      => collect($createdCats)->firstWhere('slug', 'beauty')->id,
                'images'           => [
                        'https://images.unsplash.com/photo-1619994403073-2cec844b8e63?w=600&q=80'
                ],
                'is_featured'      => false,
                'slug'             => 'black-opium-edp-100ml',
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
