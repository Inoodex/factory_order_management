<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerOrder;
use App\Models\FactoryFollowup;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class FactoryOrderSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Customers
        $c1 = Customer::firstOrCreate(['name' => 'H&M Hennes & Mauritz'], [
            'brand' => 'H&M Divided',
            'session' => 'Summer 2026',
            'email' => 'sourcing.dhaka@hm.com',
            'phone' => '+880 1711 111111',
            'address' => 'Gulshan 2, Dhaka 1212',
        ]);

        $c2 = Customer::firstOrCreate(['name' => 'Inditex Sourcing Ltd'], [
            'brand' => 'Zara Man',
            'session' => 'Autumn/Winter 2026',
            'email' => 'buyer.bd@inditex.com',
            'phone' => '+880 1722 222222',
            'address' => 'Banani, Dhaka 1213',
        ]);

        $c3 = Customer::firstOrCreate(['name' => 'Next Retail UK'], [
            'brand' => 'Next Casuals',
            'session' => 'Spring 2026',
            'email' => 'next.sourcing@next.co.uk',
            'phone' => '+880 1733 333333',
            'address' => 'Baridhara DOHS, Dhaka',
        ]);

        // 2. Suppliers
        $s1 = Supplier::firstOrCreate(['name' => 'Apex Knitting & Dyeing Mills Ltd'], [
            'location' => 'Kashimpur, Gazipur',
            'contact_person' => 'Engr. Rafiqul Islam (GM Production)',
            'phone' => '+880 1819 001122',
            'email' => 'production@apexknitting.com',
        ]);

        $s2 = Supplier::firstOrCreate(['name' => 'Viyellatex Fashions Ltd'], [
            'location' => 'Tongi Industrial Area, Gazipur',
            'contact_person' => 'Tanvir Ahmed (Merchandising Head)',
            'phone' => '+880 1819 334455',
            'email' => 'orders@viyellatexgroup.com',
        ]);

        $s3 = Supplier::firstOrCreate(['name' => 'Square Fashions Ltd'], [
            'location' => 'Valuka, Mymensingh',
            'contact_person' => 'Mustafa Kamal (Factory Manager)',
            'phone' => '+880 1819 556677',
            'email' => 'squarefashions@squaregroup.com',
        ]);

        // 3. Customer Orders
        $ordersData = [
            [
                'customer_id' => $c1->id,
                'supplier_id' => $s1->id,
                'order_no' => 'ORD-2026-00001',
                'style_no' => 'STY-HM-101',
                'style_name' => 'Men Relaxed Fit Jersey T-Shirt',
                'composition' => '100% BCI Organic Cotton (180 GSM)',
                'color_name' => 'Navy Blue',
                'color_qty' => 12000,
                'order_date' => now()->subDays(25),
                'etd_date' => now()->addDays(20),
                'price' => 3.85,
                'notes' => 'Enzyme bio-wash required, export hanger pack.',
                'fo_etd_price' => 3.50,
                'fo_sub_price' => 0.45,
                'fo_aetd_date' => now()->addDays(20),
                'fo_fob_price' => 3.40,
                'fu_pps_status' => 'Approved',
                'fu_shs_status' => 'Sent',
                'fu_knitting' => 'Completed',
                'fu_dyeing' => 'Completed',
                'fu_cutting' => 'In Progress',
            ],
            [
                'customer_id' => $c2->id,
                'supplier_id' => $s2->id,
                'order_no' => 'ORD-2026-00002',
                'style_no' => 'STY-ZR-440',
                'style_name' => 'Men Oversized French Terry Hoodie',
                'composition' => '80% Cotton 20% Polyester (320 GSM)',
                'color_name' => 'Olive Heather',
                'color_qty' => 8500,
                'order_date' => now()->subDays(40),
                'etd_date' => now()->addDays(12),
                'price' => 7.60,
                'notes' => 'Metal eyelets and matching flat drawstrings.',
                'fo_etd_price' => 7.10,
                'fo_sub_price' => 1.20,
                'fo_aetd_date' => now()->addDays(15),
                'fo_fob_price' => 6.95,
                'fu_pps_status' => 'Approved with Comments',
                'fu_shs_status' => 'Approved',
                'fu_knitting' => 'Completed',
                'fu_dyeing' => 'Completed',
                'fu_cutting' => 'Completed',
            ],
            [
                'customer_id' => $c3->id,
                'supplier_id' => $s3->id,
                'order_no' => 'ORD-2026-00003',
                'style_no' => 'STY-NX-882',
                'style_name' => 'Ladies Ribbed Crewneck Long Sleeve',
                'composition' => '95% Modal 5% Elastane 2x2 Rib',
                'color_name' => 'Dusty Rose',
                'color_qty' => 6000,
                'order_date' => now()->subDays(15),
                'etd_date' => now()->subDays(2), // Overdue sample
                'price' => 5.20,
                'notes' => 'Silicone softener wash, flatlock stitch detailing.',
                'fo_etd_price' => 4.80,
                'fo_sub_price' => 0.60,
                'fo_aetd_date' => now()->subDays(2),
                'fo_fob_price' => 4.70,
                'fu_pps_status' => 'Approved',
                'fu_shs_status' => 'Pending',
                'fu_knitting' => 'Completed',
                'fu_dyeing' => 'In Progress',
                'fu_cutting' => 'Not Started',
            ],
            [
                'customer_id' => $c1->id,
                'supplier_id' => $s2->id,
                'order_no' => 'ORD-2026-00004',
                'style_no' => 'STY-HM-555',
                'style_name' => 'Kids Graphic Printed Romper',
                'composition' => '100% Combed Cotton Single Jersey',
                'color_name' => 'Butter Yellow',
                'color_qty' => 15000,
                'order_date' => now()->subDays(10),
                'etd_date' => now()->addDays(45),
                'price' => 2.95,
                'notes' => 'Nickel-free snaps at bottom, lead-free water base pigment print.',
                'fo_etd_price' => 2.65,
                'fo_sub_price' => 0.35,
                'fo_aetd_date' => now()->addDays(45),
                'fo_fob_price' => 2.60,
                'fu_pps_status' => 'Submitted',
                'fu_shs_status' => 'Pending',
                'fu_knitting' => 'In Progress',
                'fu_dyeing' => 'Not Started',
                'fu_cutting' => 'Not Started',
            ],
            [
                'customer_id' => $c2->id,
                'supplier_id' => $s1->id,
                'order_no' => 'ORD-2026-00005',
                'style_no' => 'STY-ZR-109',
                'style_name' => 'Classic Cotton Pique Polo',
                'composition' => '100% Combed Cotton Pique (220 GSM)',
                'color_name' => 'Classic White',
                'color_qty' => 14000,
                'order_date' => now()->subMonths(5)->setDay(12),
                'etd_date' => now()->subMonths(3)->setDay(20),
                'price' => 4.20,
                'notes' => 'Flat knit collar and cuffs, 2-button placket.',
                'fo_etd_price' => 3.85,
                'fo_sub_price' => 0.40,
                'fo_aetd_date' => now()->subMonths(3)->setDay(20),
                'fo_fob_price' => 3.80,
                'fu_pps_status' => 'Approved',
                'fu_shs_status' => 'Approved',
                'fu_knitting' => 'Completed',
                'fu_dyeing' => 'Completed',
                'fu_cutting' => 'Completed',
            ],
            [
                'customer_id' => $c3->id,
                'supplier_id' => $s2->id,
                'order_no' => 'ORD-2026-00006',
                'style_no' => 'STY-NX-304',
                'style_name' => 'Ladies Lightweight Cardigan',
                'composition' => '60% Cotton 40% Viscose Fine Knit',
                'color_name' => 'Oatmeal Melange',
                'color_qty' => 18500,
                'order_date' => now()->subMonths(4)->setDay(18),
                'etd_date' => now()->subMonths(2)->setDay(10),
                'price' => 5.10,
                'notes' => 'Tortoiseshell effect buttons, rib trim hem.',
                'fo_etd_price' => 4.65,
                'fo_sub_price' => 0.55,
                'fo_aetd_date' => now()->subMonths(2)->setDay(10),
                'fo_fob_price' => 4.60,
                'fu_pps_status' => 'Approved',
                'fu_shs_status' => 'Approved',
                'fu_knitting' => 'Completed',
                'fu_dyeing' => 'Completed',
                'fu_cutting' => 'Completed',
            ],
            [
                'customer_id' => $c1->id,
                'supplier_id' => $s3->id,
                'order_no' => 'ORD-2026-00007',
                'style_no' => 'STY-HM-712',
                'style_name' => 'Unisex Brushed Fleece Joggers',
                'composition' => '70% Cotton 30% Polyester (280 GSM)',
                'color_name' => 'Charcoal Grey',
                'color_qty' => 22000,
                'order_date' => now()->subMonths(3)->setDay(8),
                'etd_date' => now()->subMonths(1)->setDay(15),
                'price' => 4.80,
                'notes' => 'Side welt pockets, elasticated waistband with drawcord.',
                'fo_etd_price' => 4.35,
                'fo_sub_price' => 0.50,
                'fo_aetd_date' => now()->subMonths(1)->setDay(15),
                'fo_fob_price' => 4.30,
                'fu_pps_status' => 'Approved',
                'fu_shs_status' => 'Approved',
                'fu_knitting' => 'Completed',
                'fu_dyeing' => 'Completed',
                'fu_cutting' => 'Completed',
            ],
            [
                'customer_id' => $c2->id,
                'supplier_id' => $s1->id,
                'order_no' => 'ORD-2026-00008',
                'style_no' => 'STY-ZR-620',
                'style_name' => 'Cargo Shorts with Utility Pockets',
                'composition' => '98% Cotton 2% Spandex Twill',
                'color_name' => 'Khaki Tan',
                'color_qty' => 25000,
                'order_date' => now()->subMonths(2)->setDay(14),
                'etd_date' => now()->subDays(10),
                'price' => 5.40,
                'notes' => 'Garment enzyme stone wash, double needle topstitch.',
                'fo_etd_price' => 4.90,
                'fo_sub_price' => 0.65,
                'fo_aetd_date' => now()->subDays(10),
                'fo_fob_price' => 4.85,
                'fu_pps_status' => 'Approved',
                'fu_shs_status' => 'Approved',
                'fu_knitting' => 'Completed',
                'fu_dyeing' => 'Completed',
                'fu_cutting' => 'Completed',
            ],
        ];

        foreach ($ordersData as $data) {
            $order = CustomerOrder::firstOrCreate(['order_no' => $data['order_no']], [
                'customer_id' => $data['customer_id'],
                'supplier_id' => $data['supplier_id'],
                'style_no' => $data['style_no'],
                'style_name' => $data['style_name'],
                'composition' => $data['composition'],
                'color_name' => $data['color_name'],
                'color_qty' => $data['color_qty'],
                'order_date' => $data['order_date'],
                'etd_date' => $data['etd_date'],
                'price' => $data['price'],
                'notes' => $data['notes'],
            ]);

            // Update factory order commercial details
            if ($fo = $order->factoryOrder) {
                $fo->update([
                    'etd_price' => $data['fo_etd_price'],
                    'sub_price' => $data['fo_sub_price'],
                    'aetd_date' => $data['fo_aetd_date'],
                    'fob_price' => $data['fo_fob_price'],
                ]);

                if ($fu = $fo->followup) {
                    $fu->update([
                        'pps_comments_status' => $data['fu_pps_status'],
                        'pps_date' => now()->subDays(10),
                        'shs_comments_status' => $data['fu_shs_status'],
                        'shs_sending_date' => $data['fu_shs_status'] === 'Sent' || $data['fu_shs_status'] === 'Approved' ? now()->subDays(5) : null,
                        'knitting_status' => $data['fu_knitting'],
                        'dyeing_status' => $data['fu_dyeing'],
                        'cutting_status' => $data['fu_cutting'],
                        'fob_price' => $data['fo_fob_price'],
                        'sub_price' => $data['fo_sub_price'],
                    ]);
                }
            }
        }
    }
}
