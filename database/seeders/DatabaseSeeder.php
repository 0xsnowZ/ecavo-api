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
            ['name_ar' => 'الأجهزة الكهربائية',        'name_en' => 'Appliances',    'name_fr' => 'Électroménager', 'slug' => 'appliances', 'image' => '/storage/products/category-1.jpg'],
            ['name_ar' => 'الأدوات المنزلية',           'name_en' => 'Houseware',     'name_fr' => 'Articles ménagers', 'slug' => 'houseware', 'image' => '/storage/products/category-2.jpg'],
            ['name_ar' => 'الموبايلات واكسسواراتها',   'name_en' => 'Mobiles',       'name_fr' => 'Mobiles', 'slug' => 'mobiles', 'image' => '/storage/products/category-3.jpg'],
            ['name_ar' => 'الملابس',                     'name_en' => 'Clothes',       'name_fr' => 'Vêtements', 'slug' => 'clothes', 'image' => '/storage/products/category-1.jpg'],
            ['name_ar' => 'مستحضرات الجمال',            'name_en' => 'Beauty',        'name_fr' => 'Beauté', 'slug' => 'beauty', 'image' => '/storage/products/category-2.jpg'],
            ['name_ar' => 'المفروشات',                   'name_en' => 'Furniture',     'name_fr' => 'Meubles', 'slug' => 'furniture', 'image' => '/storage/products/category-3.jpg'],
            ['name_ar' => 'التلفزيونات',                 'name_en' => 'TVs',           'name_fr' => 'Téléviseurs', 'slug' => 'tvs', 'image' => '/storage/products/category-1.jpg'],
            ['name_ar' => 'الأحذية',                     'name_en' => 'Shoes',         'name_fr' => 'Chaussures', 'slug' => 'shoes', 'image' => '/storage/products/category-2.jpg'],
            ['name_ar' => 'اكسسوارات',                   'name_en' => 'Accessories',   'name_fr' => 'Accessoires', 'slug' => 'accessories', 'image' => '/storage/products/category-3.jpg'],
            ['name_ar' => 'العاب الأطفال',               'name_en' => 'Toys',          'name_fr' => 'Jouets', 'slug' => 'toys', 'image' => '/storage/products/category-1.jpg'],
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

        // Append the new rich media products
        $sampleProducts[] = [
            'name_ar'          => 'سماعات رأس لاسلكية برتقالية',
            'name_en'          => 'Stylish Orange Headphones',
            'name_fr'          => 'Casque Orange Élégant',
            'description_ar'   => 'سماعات حديثة بجودة صوت ممتازة وتصميم عصري.',
            'description_en'   => 'Modern headphones with crystal clear sound and stylish design.',
            'description_fr'   => 'Casque moderne avec un son clair et un design élégant.',
            'price'            => 89,
            'original_price'   => 120,
            'discount_percent' => 25,
            'stock'            => 40,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
            'images'           => [
                    '/storage/products/headphones.png',
                    '/storage/products/gallery-1.jpg'
            ],
            'is_featured'      => true,
            'slug'             => 'stylish-orange-headphones',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'مجموعة مستحضرات تجميل فاخرة',
            'name_en'          => 'Luxury Beauty Cosmetics Set',
            'name_fr'          => 'Ensemble de cosmétiques de luxe',
            'description_ar'   => 'مجموعة متكاملة للعناية بالبشرة والجمال.',
            'description_en'   => 'Complete beauty and skincare cosmetic set.',
            'description_fr'   => 'Ensemble de beauté complet pour le soin de la peau.',
            'price'            => 150,
            'original_price'   => 200,
            'discount_percent' => 25,
            'stock'            => 15,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'beauty')->id,
            'images'           => [
                    '/storage/products/cosmetics.png',
                    '/storage/products/gallery-2.jpg'
            ],
            'is_featured'      => true,
            'slug'             => 'luxury-beauty-cosmetics',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'كاميرا احترافية للتصوير',
            'name_en'          => 'Professional Product Camera',
            'name_fr'          => 'Appareil photo professionnel',
            'description_ar'   => 'كاميرا احترافية بدقة عالية لتصوير المنتجات بدقة متناهية.',
            'description_en'   => 'High resolution professional camera for exquisite photography.',
            'description_fr'   => 'Appareil photo de haute résolution pour une photographie exquise.',
            'price'            => 1200,
            'original_price'   => 1500,
            'discount_percent' => 20,
            'stock'            => 5,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'appliances')->id,
            'images'           => [
                    '/storage/products/camera.png',
                    '/storage/products/gallery-3.jpg',
                    '/storage/products/gallery-4.jpg'
            ],
            'is_featured'      => true,
            'slug'             => 'professional-product-camera',
        ];

        // --- NEW REALISTIC PRODUCTS USING NEW IMAGES ---
        $sampleProducts[] = [
            'name_ar'          => 'ساعة ذكية رياضية متطورة مقاومة للماء',
            'name_en'          => 'Advanced Waterproof Sports Smartwatch',
            'name_fr'          => 'Montre intelligente de sport avancée étanche',
            'description_ar'   => "أبقِ على اطلاع دائم بنشاطك البدني مع هذه الساعة الذكية الرياضية المتطورة. \nتأتي مع شاشة عريضة ساطعة من نوع AMOLED وبطارية تدوم طويلاً حتى 14 يوماً.\nتحتوي على مئات الوضعيات الرياضية لتتبع نشاطك بدقة طوال الوقت.",
            'description_en'   => "Stay on top of your physical activity with this advanced sports smartwatch.\nFeaturing a bright, vibrant AMOLED widescreen and an ultra-long battery life of up to 14 days.\nIncludes hundreds of sports modes to accurately track your everyday wellness.",
            'description_fr'   => "Restez au courant de votre activité physique avec cette montre intelligente de sport avancée.",
            'price'            => 199,
            'original_price'   => 250,
            'discount_percent' => 20,
            'stock'            => 85,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
            'images'           => [
                    '/storage/products/product-4-1.jpg',
                    '/storage/products/product-4-2.jpg',
                    '/storage/products/showcase-img-4.jpg',
                    '/storage/products/showcase-img-5.jpg'
            ],
            'specifications'   => [
                ['label' => 'Brand', 'value' => 'TechGear'],
                ['label' => 'Display', 'value' => '1.75 inch AMOLED High-Res'],
                ['label' => 'Battery Life', 'value' => 'Up to 14 Days'],
                ['label' => 'Water Resistance', 'value' => 'IP68 / 5ATM'],
                ['label' => 'Connectivity', 'value' => 'Bluetooth 5.3'],
            ],
            'is_featured'      => true,
            'slug'             => 'advanced-waterproof-sports-smartwatch',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'مجموعة نظارات شمسية كلاسيكية (للجنسين)',
            'name_en'          => 'Classic Unisex Sunglasses Collection',
            'name_fr'          => 'Collection de lunettes de soleil unisexe classique',
            'description_ar'   => "اكتسب إطلالة أنيقة وعصرية مع هذه النظارات الشمسية الكلاسيكية.\nعدسات مستقطبة (Polarized) توفر حماية بنسبة 100% من الأشعة فوق البنفسجية.\nإطار خفيف الوزن ومتين مصمم للارتداء اليومي في مختلف الظروف.",
            'description_en'   => "Achieve an elegant and modern look with these classic sunglasses.\nFully polarized lenses offer 100% UV protection for your eyes against harmful rays.\nLightweight and highly durable frame engineered for all-day daily wear.",
            'description_fr'   => "Obtenez un look élégant et moderne avec ces lunettes de soleil classiques.",
            'price'            => 45,
            'original_price'   => 60,
            'discount_percent' => 25,
            'stock'            => 200,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
            'images'           => [
                    '/storage/products/product-12-1.jpg',
                    '/storage/products/product-12-2.jpg',
                    '/storage/products/product-11-2.jpg',
                    '/storage/products/deals-1.jpg'
            ],
            'specifications'   => [
                ['label' => 'Frame Material', 'value' => 'Premium Acetate'],
                ['label' => 'Lenses', 'value' => 'Polarized / 100% UV400 Protection'],
                ['label' => 'Style', 'value' => 'Classic Vintage / Unisex'],
                ['label' => 'Weight', 'value' => '32 grams'],
            ],
            'is_featured'      => true,
            'slug'             => 'classic-unisex-sunglasses-collection',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'حقيبة ظهر فاخرة للسفر والأعمال',
            'name_en'          => 'Premium Travel & Business Backpack',
            'name_fr'          => 'Sac à dos de voyage et d\'affaires haut de gamme',
            'description_ar'   => "حقيبة ظهر واسعة بتصميم متميز تلائم احتياجاتك في العمل والسفر.\nتحتوي على جيب مبطن لحماية الحواسيب المحمولة حتى مقاس 15.6 بوصة ومنافذ USB خفية لشحن هاتفك.",
            'description_en'   => "A spacious backpack with a luxury design perfectly suited for business and travel.\nFeatures a dedicated padded compartment for laptops up to 15.6 inches and a hidden USB charging port.",
            'description_fr'   => "Un sac à dos spacieux avec un design luxueux parfaitement adapté pour les affaires et les voyages.",
            'price'            => 70,
            'original_price'   => 105,
            'discount_percent' => 33,
            'stock'            => 40,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
            'images'           => [
                    '/storage/products/product-7-1.jpg',
                    '/storage/products/product-7-2.jpg',
                    '/storage/products/product-13-1.jpg',
                    '/storage/products/product-13-2.jpg'
            ],
            'specifications'   => [
                ['label' => 'Material', 'value' => 'Water-resistant Oxford Fabric'],
                ['label' => 'Capacity', 'value' => '25 Liters / 15.6" Laptop'],
                ['label' => 'Features', 'value' => 'Anti-theft pockets, USB Charging Port'],
                ['label' => 'Dimensions', 'value' => '18" x 12" x 6"'],
            ],
            'is_featured'      => true,
            'slug'             => 'premium-travel-business-backpack',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'هاتف ألترا الذكي برو ماكس 5G',
            'name_en'          => 'Ultra Smart Pro Max 5G Smartphone',
            'name_fr'          => 'Smartphone Ultra Smart Pro Max 5G',
            'description_ar'   => "أقوى هاتف ذكي على الإطلاق مع كاميرا خرافية بدقة 200 ميجابكسل وشاشة لا مثيل لها بتردد 144 هرتز للمحترفين وعشاق الألعاب.\nأداء المعالج يكسر كل حدود السرعة ليوفر تجربة سلسة استثنائية مع دعم شبكات الجيل الخامس 5G.",
            'description_en'   => "The most powerful smartphone ever with a breathtaking 200MP camera and an unmatched 144Hz display for professionals and gamers.\nProcessor performance breaks all speed limits to provide an exceptionally smooth 5G experience.",
            'description_fr'   => "Le smartphone le plus puissant de tous les temps avec un appareil photo époustouflant.",
            'price'            => 1199,
            'original_price'   => 1350,
            'discount_percent' => 11,
            'stock'            => 15,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'mobiles')->id,
            'images'           => [
                    '/storage/products/product-1-2.jpg',
                    '/storage/products/product-2-1.jpg',
                    '/storage/products/product-2-2.jpg'
            ],
            'specifications'   => [
                ['label' => 'Display', 'value' => '6.8 inch Dynamic AMOLED 144Hz'],
                ['label' => 'Processor', 'value' => 'Snapdragon 8 Gen 3'],
                ['label' => 'Camera', 'value' => '200MP Primary, 50MP Ultrawide, 10x Optical Zoom'],
                ['label' => 'Battery', 'value' => '5500mAh / 65W Fast Charge'],
            ],
            'is_featured'      => true,
            'slug'             => 'ultra-smart-pro-max-5g',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'سماعات ألعاب لاسلكية بمحيط صوتي 7.1',
            'name_en'          => 'Wireless Gaming Headset 7.1 Surround',
            'name_fr'          => 'Casque de jeu sans fil 7.1 Surround',
            'description_ar'   => "عشاق الألعاب، استمتعوا بتجربة انغماس كاملة مع هذه السماعات المخصصة التي تقدم صوتاً محيطياً مذهلاً 7.1.\nتوفر ميكروفون احترافي بخاصية عزل الضوضاء لضمان التواصل الواضح مع فريقك، وبطارية لـ 50 ساعة.",
            'description_en'   => "Gamers, immerse yourselves fully with this dedicated headset offering stunning 7.1 surround sound.\nFeatures a professional noise-canceling microphone for crystal clear comms and a massive 50-hour battery life.",
            'description_fr'   => "Joueurs, plongez-vous pleinement avec ce casque dédié offrant un son surround 7.1 époustouflant.",
            'price'            => 129,
            'original_price'   => 160,
            'discount_percent' => 19,
            'stock'            => 45,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
            'images'           => [
                    '/storage/products/product-8-1.jpg',
                    '/storage/products/product-8-2.jpg',
                    '/storage/products/product-9-1.jpg',
                    '/storage/products/product-9-2.jpg'
            ],
            'specifications'   => [
                ['label' => 'Audio', 'value' => '7.1 Virtual Surround Sound'],
                ['label' => 'Connectivity', 'value' => '2.4GHz Wireless USB + 3.5mm'],
                ['label' => 'Microphone', 'value' => 'Detachable Noise Cancelling'],
                ['label' => 'Battery', 'value' => 'Up to 50 Hours'],
            ],
            'is_featured'      => true,
            'slug'             => 'wireless-gaming-headset-7-1',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'تلفزيون ذكي 65 بوصة 4K فائق الوضوح',
            'name_en'          => '65 Inch 4K Ultra HD Smart TV',
            'name_fr'          => 'Téléviseur intelligent 65 pouces 4K Ultra HD',
            'description_ar'   => "شاهد برامجك وأفلامك المفضلة بتفاصيل مذهلة مع تلفزيون 65 بوصة 4K. \nيدعم تقنيات HDR10 وDolby Vision لتجربة سينمائية في منزلك، إضافة لمتجر تطبيقات واسع يضم نتفليكس ويوتيوب والمزيد.",
            'description_en'   => "Watch your favorite shows and movies in breathtaking detail with this 65-inch 4K TV.\nSupports HDR10 and Dolby Vision for a cinematic experience right at home, plus a vast smart hub with Netflix, YouTube and more.",
            'description_fr'   => "Regardez vos émissions et films préférés avec des détails époustouflants.",
            'price'            => 899,
            'original_price'   => 1150,
            'discount_percent' => 21,
            'stock'            => 10,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'tvs')->id,
            'images'           => [
                    '/storage/products/product-3-1.jpg',
                    '/storage/products/product-5-1.jpg',
                    '/storage/products/product-5-2.jpg',
                    '/storage/products/product-6-2.jpg'
            ],
            'specifications'   => [
                ['label' => 'Display Category', 'value' => '65" 4K UHD LED'],
                ['label' => 'HDR', 'value' => 'Dolby Vision / HDR10+'],
                ['label' => 'Smart Features', 'value' => 'Android TV, Voice Control, App Store'],
                ['label' => 'Ports', 'value' => '4x HDMI 2.1, 2x USB, Ethernet'],
            ],
            'is_featured'      => true,
            'slug'             => '65-inch-4k-uhd-smart-tv',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'لابتوب احترافي ماكس 16 بوصة للإبداع',
            'name_en'          => 'ProBook Max 16-inch Creator Laptop',
            'name_fr'          => 'Ordinateur portable ProBook Max 16 pouces',
            'description_ar'   => "أقوى لابتوب للمبدعين والمحترفين، يأتي بشريحة M3 الاستثنائية التي تقدم أداءً لا يضاهى في المونتاج وتعديل الصور.\nشاشة Liquid Retina مذهلة، وبطارية تدوم حتى 22 ساعة للعمل بدون انقطاع.",
            'description_en'   => "The ultimate powerhouse for creators and professionals, featuring the groundbreaking M3 chip for peerless video editing and rendering.\nStunning Liquid Retina display and up to 22 hours of battery life for uninterrupted workflow.",
            'description_fr'   => "La puissance ultime pour les créateurs et les professionnels, avec la puce M3 révolutionnaire.",
            'price'            => 2499,
            'original_price'   => 2700,
            'discount_percent' => 7,
            'stock'            => 10,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'appliances')->id,
            'images'           => [
                    '/storage/products/product-10.jpg',
                    '/storage/products/product-10-1.jpg',
                    '/storage/products/product-10-2.jpg',
                    '/storage/products/showcase-img-1.jpg'
            ],
            'specifications'   => [
                ['label' => 'Processor', 'value' => 'M3 Max 14-Core CPU'],
                ['label' => 'RAM', 'value' => '36GB Unified Memory'],
                ['label' => 'Storage', 'value' => '1TB SSD'],
                ['label' => 'Display', 'value' => '16.2" Mini-LED 120Hz'],
            ],
            'is_featured'      => true,
            'slug'             => 'probook-max-16-inch-creator',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'محفظة جلدية فاخرة للرجال أصلية',
            'name_en'          => 'Luxury Genuine Leather Men\'s Wallet',
            'name_fr'          => 'Portefeuille de luxe en cuir véritable pour hommes',
            'description_ar'   => "محفظة فاخرة مصنوعة يدوياً من الجلد الأصلي الإيطالي، مصممة بأناقة مع خاصية حظر طاقات RFID لحماية بطاقاتك البنكية من السرقة اللاسلكية.\nتتميز بعدة جيوب تتسع لـ 10 بطاقات وحامل للعملات الورقية.",
            'description_en'   => "A luxurious handcrafted wallet made from genuine Italian leather, elegantly designed with RFID blocking technology to protect your cards from wireless theft.\nFeatures multiple slots accommodating up to 10 cards and a cash sleeve.",
            'description_fr'   => "Un luxueux portefeuille fait à la main en cuir italien véritable, élégamment conçu avec la technologie de blocage RFID.",
            'price'            => 65,
            'original_price'   => 85,
            'discount_percent' => 23,
            'stock'            => 120,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
            'images'           => [
                    '/storage/products/product-01.jpg',
                    '/storage/products/product-02.jpg',
                    '/storage/products/product-03.jpg',
                    '/storage/products/product-11-1.jpg'
            ],
            'specifications'   => [
                ['label' => 'Material', 'value' => '100% Genuine Italian Leather'],
                ['label' => 'Security', 'value' => 'RFID Blocking Layer'],
                ['label' => 'Capacity', 'value' => '10 Card Slots, 1 Cash Sleeves'],
                ['label' => 'Dimensions', 'value' => '4.5" x 3.5" x 0.5"'],
            ],
            'is_featured'      => false,
            'slug'             => 'luxury-genuine-leather-wallet',
        ];

        $sampleProducts[] = [
            'name_ar'          => 'سماعات رأس لاسلكية بخاصية عزل الضوضاء برو',
            'name_en'          => 'Noise Cancelling Over-Ear Headphones Pro',
            'name_fr'          => 'Casque sans fil à réduction de bruit Pro',
            'description_ar'   => "اغمر نفسك في عالم الموسيقى النقي مع سماعات الرأس اللاسلكية المتطورة، المزودة بتقنية عزل الضوضاء النشط (ANC).\nصوت جهير عميق ووسائد أذن مريحة جداً تناسب الاستخدام لساعات طويلة.",
            'description_en'   => "Immerse yourself in a world of pure music with these advanced wireless headphones equipped with Active Noise Cancellation (ANC).\nFeatures deep bass response and incredibly comfortable ear cushions for hours of listening.",
            'description_fr'   => "Plongez dans un monde de musique pure avec ce casque sans fil avancé doté de la réduction active du bruit (ANC).",
            'price'            => 199,
            'original_price'   => 299,
            'discount_percent' => 33,
            'stock'            => 60,
            'category_id'      => collect($createdCats)->firstWhere('slug', 'accessories')->id,
            'images'           => [
                    '/storage/products/headphones.png',
                    '/storage/products/product-17.jpg',
                    '/storage/products/product-16.jpg',
                    '/storage/products/showcase-img-7.jpg'
            ],
            'specifications'   => [
                ['label' => 'Technology', 'value' => 'Active Noise Cancelling (ANC)'],
                ['label' => 'Battery Life', 'value' => 'Up to 40 Hours with ANC'],
                ['label' => 'Drivers', 'value' => '40mm High-Resolution Audio Drivers'],
                ['label' => 'Weight', 'value' => '250g (Foldable Design)'],
            ],
            'is_featured'      => true,
            'slug'             => 'noise-cancelling-headphones-pro',
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

        // ── Sample Users for Reviews ──────────────────────────────────
        $testUsers = [];
        for ($i = 1; $i <= 3; $i++) {
            $testUsers[] = User::create([
                'name'     => "Test User $i",
                'email'    => "user$i@ecavo.com",
                'password' => bcrypt('password'),
                'role'     => 'customer',
                'phone'    => "+123456789$i",
            ]);
        }

        // ── Seed Reviews, large Description and Specifications ──────────────
        $allProducts = Product::all();
        foreach ($allProducts as $product) {
            // Give every product a rich description array if empty
            if (empty($product->description_ar)) {
                $product->description_ar = "هذا المنتج رائع جدا ويتمتع بمواصفات عالية الجودة. مصمم خصيصا لتلبية احتياجاتك اليومية بأفضل الخامات.";
            }
            if (empty($product->description_en)) {
                $product->description_en = "This is a highly durable and premium product designed for daily use. It offers top quality and great value.";
            }

            // Give every product specifications if empty
            if (empty($product->specifications)) {
                $product->specifications = collect([
                    ['label' => 'Brand', 'value' => 'Ecavo Originals'],
                    ['label' => 'Material', 'value' => 'Premium Quality'],
                    ['label' => 'Warranty', 'value' => '1 Year'],
                    ['label' => 'Color', 'value' => 'Mixed'],
                ])->toArray();
            }
            $product->save();

            // Add 1-3 random reviews per product
            $reviewCount = rand(1, 3);
            $randomUsers = collect($testUsers)->random($reviewCount);
            
            foreach ($randomUsers as $randomUser) {
                \App\Models\Review::create([
                    'product_id' => $product->id,
                    'user_id'    => $randomUser->id,
                    'rating'     => rand(4, 5),
                    'comment'    => "Great product! Highly recommended. Excellent quality.",
                    'approved'   => true,
                ]);
            }
        }

        $this->command->info('✅ ECAVO seeded: 1 admin, 3 test users, ' . count($categories) . ' categories, ' . count($sampleProducts) . ' products, reviews added, 1 coupon.');
    }
}
