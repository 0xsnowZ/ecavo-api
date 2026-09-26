<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderStatisticsSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            ['name' => 'Youssef Alami',          'phone' => '+212 661-234567', 'email' => 'youssef.alami@gmail.com', 'city' => 'Casablanca', 'address' => 'Boulevard d\'Anfa, No 142'],
            ['name' => 'Fatima-Zahra Bennani',   'phone' => '+212 662-890123', 'email' => 'fz.bennani@yahoo.com',    'city' => 'Rabat',      'address' => 'Avenue Mohammed VI, Résidence Al Manar'],
            ['name' => 'Karim El Idrissi',       'phone' => '+212 663-456789', 'email' => 'karim.idrissi@gmail.com', 'city' => 'Marrakech',  'address' => 'Guéliz, Rue de la Liberté, Appt 12'],
            ['name' => 'Sofia Amrani',           'phone' => '+212 664-012345', 'email' => 'sofia.amrani@outlook.com','city' => 'Tanger',     'address' => 'Malabata, Résidence Vue sur Mer'],
            ['name' => 'Mehdi Mansouri',         'phone' => '+212 665-678901', 'email' => 'mehdi.mansouri@gmail.com','city' => 'Agadir',     'address' => 'Hassan II, Immeuble B, No 8'],
            ['name' => 'Nadia Chraibi',          'phone' => '+212 666-234567', 'email' => 'nadia.chraibi@gmail.com', 'city' => 'Fès',        'address' => 'Ville Nouvelle, Rue Al Qods'],
            ['name' => 'Amine Tazi',             'phone' => '+212 667-890123', 'email' => 'amine.tazi@gmail.com',    'city' => 'Casablanca', 'address' => 'Gauthier, Rue Jean Jaurès'],
            ['name' => 'Salma Berrada',          'phone' => '+212 668-456789', 'email' => 'salma.berrada@gmail.com', 'city' => 'Rabat',      'address' => 'Agdal, Avenue Fal Ould Oumeir'],
            ['name' => 'Omar Belkacem',          'phone' => '+212 669-012345', 'email' => 'omar.belkacem@gmail.com', 'city' => 'Kénitra',    'address' => 'Centre Ville, Rue Mamora'],
            ['name' => 'Khadija Filali',         'phone' => '+212 670-678901', 'email' => 'khadija.filali@gmail.com','city' => 'Marrakech',  'address' => 'Hivernage, Avenue Mohammed V'],
            ['name' => 'Anas Senhaji',           'phone' => '+212 671-234567', 'email' => 'anas.senhaji@gmail.com',  'city' => 'Casablanca', 'address' => 'Maarif, Rue Normandie'],
            ['name' => 'Leila Benjelloun',       'phone' => '+212 672-890123', 'email' => 'leila.b@gmail.com',       'city' => 'Tanger',     'address' => 'Boulevard Pasteur, Appt 5'],
            ['name' => 'Hamza Kabbaj',           'phone' => '+212 673-456789', 'email' => 'hamza.kabbaj@gmail.com',  'city' => 'Casablanca', 'address' => 'Bourgogne, Rue de Bourgogne'],
            ['name' => 'Sara El Ouardi',         'phone' => '+212 674-012345', 'email' => 'sara.ouardi@gmail.com',   'city' => 'Rabat',      'address' => 'Hay Riad, Secteur 14'],
            ['name' => 'Reda Touzani',           'phone' => '+212 675-678901', 'email' => 'reda.touzani@gmail.com',  'city' => 'Agadir',     'address' => 'Cité Dakhla, Rue 2 Mars'],
            ['name' => 'Hiba El Fassi',          'phone' => '+212 676-234567', 'email' => 'hiba.elfassi@gmail.com',  'city' => 'Fès',        'address' => 'Narjiss, Boulevard Allal Ben Abdellah'],
        ];

        // Available real products
        $products = Product::whereNotNull('name_en')->take(40)->get();
        if ($products->count() < 10) {
            $products = Product::all();
        }

        // Star products that will be the Top Sellers
        $starProducts = $products->take(5);

        // Daily targets for the 7 days (daysAgo 6 to 0, where 0 is today)
        // Saturday 2026-09-26 is day 0 (today)
        // Friday   2026-09-25 is day 1 (yesterday)
        // Thursday 2026-09-24 is day 2
        // Wednesday2026-09-23 is day 3
        // Tuesday  2026-09-22 is day 4
        // Monday   2026-09-21 is day 5
        // Sunday   2026-09-20 is day 6
        $dailyPlan = [
            6 => [ // Sunday 2026-09-20
                'order_count' => 5,
                'target_rev'  => 480,
                'statuses'    => ['delivered', 'delivered', 'delivered', 'delivered', 'returned'],
            ],
            5 => [ // Monday 2026-09-21
                'order_count' => 6,
                'target_rev'  => 620,
                'statuses'    => ['delivered', 'delivered', 'delivered', 'delivered', 'delivered', 'delivered'],
            ],
            4 => [ // Tuesday 2026-09-22
                'order_count' => 7,
                'target_rev'  => 850,
                'statuses'    => ['delivered', 'delivered', 'delivered', 'delivered', 'delivered', 'delivered', 'cancelled'],
            ],
            3 => [ // Wednesday 2026-09-23
                'order_count' => 6,
                'target_rev'  => 740,
                'statuses'    => ['delivered', 'delivered', 'delivered', 'delivered', 'delivered', 'in_transit'],
            ],
            2 => [ // Thursday 2026-09-24
                'order_count' => 8,
                'target_rev'  => 1120,
                'statuses'    => ['delivered', 'delivered', 'delivered', 'delivered', 'in_transit', 'in_transit', 'shipped', 'shipped'],
            ],
            1 => [ // Friday 2026-09-25 (Yesterday)
                'order_count' => 9,
                'target_rev'  => 1450,
                'statuses'    => ['delivered', 'delivered', 'in_transit', 'in_transit', 'shipped', 'awaiting_shipment', 'preparing', 'placed', 'placed'],
            ],
            0 => [ // Saturday 2026-09-26 (Today)
                'order_count' => 8,
                'target_rev'  => 980,
                'statuses'    => ['in_transit', 'shipped', 'awaiting_shipment', 'preparing', 'placed', 'placed', 'placed', 'placed'],
            ],
        ];

        $users = User::where('role', 'customer')->get();
        $userIndex = 0;
        $custIndex = 0;

        foreach ($dailyPlan as $daysAgo => $plan) {
            $baseDate = Carbon::now()->subDays($daysAgo);

            for ($k = 0; $k < $plan['order_count']; $k++) {
                $status = $plan['statuses'][$k % count($plan['statuses'])];
                $cust = $customers[$custIndex % count($customers)];
                $custIndex++;

                // Stagger hours during the day: between 08:30 and 21:45
                $hour = 8 + intval(($k / $plan['order_count']) * 13);
                $minute = ($k * 17) % 60;
                $second = ($k * 29) % 60;
                $orderTime = $baseDate->copy()->setTime($hour, $minute, $second);

                // For updated_at: if delivered or shipped today/yesterday, reflect accurate updated_at
                $updatedTime = $orderTime->copy();
                if ($status === 'delivered') {
                    $updatedTime = $orderTime->copy()->addHours(rand(12, 36));
                    if ($updatedTime->isFuture()) $updatedTime = Carbon::now()->subMinutes(rand(10, 60));
                } elseif (in_array($status, ['shipped', 'in_transit'])) {
                    if ($daysAgo === 0) {
                        $updatedTime = Carbon::now()->subHours(rand(1, 4));
                    } elseif ($daysAgo === 1) {
                        $updatedTime = Carbon::now()->subDay()->setTime(rand(14, 20), rand(0, 59));
                    }
                } elseif (in_array($status, ['cancelled', 'returned'])) {
                    $updatedTime = $orderTime->copy()->addHours(rand(4, 24));
                }

                // Randomly assign to a registered user or guest
                $linkedUser = ($k % 3 !== 0 && $users->isNotEmpty())
                    ? $users[$userIndex++ % $users->count()]
                    : null;

                // Pick 1 to 3 products, prioritizing star products for top leaderboard
                $itemCount = rand(1, 3);
                $orderProducts = [];
                // Guarantee star products appear often
                if ($k % 2 === 0) {
                    $orderProducts[] = $starProducts[($k + $daysAgo) % $starProducts->count()];
                }
                while (count($orderProducts) < $itemCount) {
                    $randomP = $products->random();
                    if (!in_array($randomP->id, array_column($orderProducts, 'id'))) {
                        $orderProducts[] = $randomP;
                    }
                }

                $deliveryFee = 5.99;
                $discount = ($k % 4 === 0) ? 10.00 : 0.00;
                $subtotal = 0;

                $orderItemsData = [];
                foreach ($orderProducts as $prod) {
                    $qty = rand(1, 3);
                    $unitPrice = (float) $prod->price;
                    $itemTotal = $unitPrice * $qty;
                    $subtotal += $itemTotal;

                    $orderItemsData[] = [
                        'product_id'   => $prod->id,
                        'product_name' => $prod->name_en ?? $prod->name_ar,
                        'unit_price'   => $unitPrice,
                        'qty'          => $qty,
                        'total'        => $itemTotal,
                        'created_at'   => $orderTime,
                        'updated_at'   => $updatedTime,
                    ];
                }

                $total = max(0, $subtotal + $deliveryFee - $discount);

                $paymentMethods = ['cod', 'stripe', 'stripe', 'cod'];
                $paymentMethod = $paymentMethods[$k % count($paymentMethods)];
                $paymentId = ($paymentMethod === 'stripe') ? 'pi_' . substr(md5(uniqid()), 0, 24) : null;

                $order = Order::create([
                    'user_id'        => $linkedUser?->id,
                    'address_id'     => null,
                    'status'         => $status,
                    'payment_method' => $paymentMethod,
                    'payment_id'     => $paymentId,
                    'subtotal'       => $subtotal,
                    'delivery_fee'   => $deliveryFee,
                    'discount'       => $discount,
                    'total'          => $total,
                    'coupon_code'    => ($discount > 0) ? 'WELCOME10' : null,
                    'notes'          => ($k % 5 === 0) ? 'Please call before delivery' : null,
                    'guest_name'     => $cust['name'],
                    'guest_phone'    => $cust['phone'],
                    'guest_email'    => $cust['email'],
                    'guest_address'  => $cust['address'] . ', ' . $cust['city'],
                    'created_at'     => $orderTime,
                    'updated_at'     => $updatedTime,
                ]);

                // Create OrderItems
                foreach ($orderItemsData as $itemData) {
                    $itemData['order_id'] = $order->id;
                    OrderItem::create($itemData);
                }
            }
        }
    }
}
