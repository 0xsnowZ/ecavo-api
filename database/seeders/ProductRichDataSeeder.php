<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ProductRichDataSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();

        // Create 5 fake users for reviews
        $reviewUsers = [];
        for ($i = 1; $i <= 5; $i++) {
            $reviewUsers[] = User::firstOrCreate(
                ['email' => "reviewer{$i}@example.com"],
                [
                    'name' => "Verified Buyer {$i}",
                    'password' => Hash::make('password'),
                ]
            );
        }

        $richDescriptionsEn = [
            "Experience unprecedented quality with this premium product. Designed using state-of-the-art technology, it delivers outstanding performance and reliability. \n\n**Key Features:**\n- Ergonomic design for maximum comfort\n- Built with high-grade durable materials\n- Long-lasting efficiency\n\nPerfect for both everyday use and professional environments. Upgrade your lifestyle with this incredible addition to your collection.",
            "Crafted to perfection, this item combines elegance and utility. Whether you are using it at home or on the go, you will appreciate its lightweight construct and premium finish. \n\n**Why Choose This?**\n- Industry-leading warranty\n- Eco-friendly packaging\n- Unmatched value for money\n\nDo not miss out on the top-rated product of the year.",
            "Discover the ultimate solution to your needs. This product has been meticulously engineered to provide seamless integration into your daily routine. \n\n**Product Highlights:**\n- Advanced safety features\n- Intuitive user interface\n- Minimal maintenance required\n\nJoin thousands of satisfied customers who have revolutionized their workflow."
        ];
        
        $richDescriptionsAr = [
            "استمتع بتجربة جودة غير مسبوقة مع هذا المنتج المتميز. تم تصميمه باستخدام أحدث التقنيات لتقديم أداء وموثوقية مذهلة.\n\n**الميزات الرئيسية:**\n- تصميم مريح للغاية\n- مصنوع من مواد عالية الجودة\n- كفاءة تدوم طويلاً\n\nمثالي للاستخدام اليومي والبيئات الاحترافية.",
            "مُصمم بإتقان ليجمع بين الأناقة والفائدة. سواء كنت تستخدمه في المنزل أو أثناء التنقل، ستقدر هيكله الخفيف وشكله الجذاب.\n\n**لماذا تختار هذا المنتج؟**\n- ضمان رائد في الصناعة\n- تغليف صديق للبيئة\n- أفضل قيمة مقابل السعر",
            "اكتشف الحل الأمثل لاحتياجاتك. تمت هندسة هذا المنتج بدقة ليوفر تكاملاً سلساً في روتينك اليومي.\n\n**أبرز مزايا المنتج:**\n- ميزات أمان متقدمة\n- واجهة مستخدم بديهية\n- يتطلب صيانة قليلة جداً"
        ];
        
        $richDescriptionsFr = [
            "Découvrez une qualité sans précédent avec ce produit haut de gamme. Conçu en utilisant une technologie de pointe, il offre des performances et une fiabilité exceptionnelles.\n\n**Caractéristiques Principales :**\n- Design ergonomique pour un confort maximum\n- Fabriqué avec des matériaux durables de haute qualité\n- Efficacité durable\n\nParfait pour un usage quotidien et les environnements professionnels.",
            "Fabriqué à la perfection, cet article allie élégance et utilité. Que vous l'utilisiez à la maison ou en déplacement, vous apprécierez sa conception légère et sa finition haut de gamme.\n\n**Pourquoi choisir ceci ?**\n- Garantie leader de l'industrie\n- Emballage écologique\n- Rapport qualité-prix inégalé",
            "Découvrez la solution ultime à vos besoins. Ce produit a été méticuleusement conçu pour s'intégrer de manière transparente dans votre routine quotidienne.\n\n**Points Forts :**\n- Fonctions de sécurité avancées\n- Interface utilisateur intuitive\n- Entretien minimal requis"
        ];

        $specificationsPool = [
            [
                ['label' => 'Brand', 'value' => 'Premium Brand Co.'],
                ['label' => 'Weight', 'value' => '1.2 kg'],
                ['label' => 'Color', 'value' => 'Obsidian Black / Matte Finish'],
                ['label' => 'Warranty', 'value' => '2 Years International'],
                ['label' => 'Model Year', 'value' => '2026'],
            ],
            [
                ['label' => 'Material', 'value' => 'Aircraft-grade Aluminum'],
                ['label' => 'Dimensions', 'value' => '15 x 10 x 5 cm'],
                ['label' => 'Battery Life', 'value' => 'Up to 24 hours continuous use'],
                ['label' => 'Connectivity', 'value' => 'Bluetooth 5.3 & Wi-Fi 6E'],
            ],
            [
                ['label' => 'Fabric', 'value' => '100% Organic Cotton'],
                ['label' => 'Care Instructions', 'value' => 'Machine wash cold below 30°C'],
                ['label' => 'Fit', 'value' => 'Regular Fit'],
                ['label' => 'Origin', 'value' => 'Imported ethically'],
            ]
        ];

        $reviewComments = [
            "Absolutely fantastic product! It exceeded all my expectations and works flawlessly.",
            "Really good value for the money. The build quality is surprisingly premium.",
            "I'm very happy with this purchase. Shipping was fast and the product is exactly as described.",
            "It's decent, but I wish the delivery was faster. Still a solid 4 stars.",
            "Best purchase I've made this year. I highly recommend it to everyone!",
            "I use this every day now. I cannot imagine my life without it.",
            "Excellent build quality and very intuitive to use. Worth every penny.",
            "The product arrived in perfect condition and the customer service was extremely helpful."
        ];

        // Ensure we don't have unique constraint issues by deleting old test reviews first
        Review::whereIn('user_id', collect($reviewUsers)->pluck('id'))->delete();

        foreach ($products as $index => $product) {
            // Update product with rich description and specifications
            $descIndex = $index % 3;
            $specIndex = $index % 3;
            
            // Only update specs if they are empty
            $specs = $product->specifications ?: $specificationsPool[$specIndex];

            // If the old description didn't already have the rich text, append it
            $currentDesc = $product->description_en;
            if (strpos($currentDesc, '**Key Features:**') === false && strpos($currentDesc, 'Product Highlights') === false && strpos($currentDesc, 'Why Choose This') === false) {
                $descEn = "{$product->description_en}\n\n" . $richDescriptionsEn[$descIndex];
                $descAr = "{$product->description_ar}\n\n" . $richDescriptionsAr[$descIndex];
                $descFr = "{$product->description_fr}\n\n" . $richDescriptionsFr[$descIndex];

                $product->update([
                    'description_en' => $descEn,
                    'description_ar' => $descAr,
                    'description_fr' => $descFr,
                    'specifications' => $specs
                ]);
            }

            // Create Reviews (1 distinct review per fake user, randomly 2-5 reviews per product)
            $numReviews = rand(2, 5);
            $selectedUsers = collect($reviewUsers)->random($numReviews);
            
            foreach ($selectedUsers as $user) {
                // Check if a review already exists to prevent unique constraint
                if (!Review::where('product_id', $product->id)->where('user_id', $user->id)->exists()) {
                    Review::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'rating' => rand(4, 5),
                        'comment' => $reviewComments[array_rand($reviewComments)],
                        'approved' => true
                    ]);
                }
            }
        }
    }
}
