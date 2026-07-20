<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ─────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@mataair.com'],
            [
                'name'     => 'Admin Mata Air',
                'phone'    => '08123456789',
                'role'     => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // ── Customer ──────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'customer@demo.com'],
            [
                'name'     => 'Pelanggan Demo',
                'phone'    => '08198765432',
                'role'     => 'customer',
                'password' => Hash::make('password'),
            ]
        );

        // ── Karyawan ──────────────────────────────────────────
        $karyawanList = [
            ['name'=>'Andi Pratama',  'email'=>'andi@mataair.com',   'phone'=>'08111111111', 'specialization'=>'detailing'],
            ['name'=>'Budi Santoso',  'email'=>'budi@mataair.com',   'phone'=>'08222222222', 'specialization'=>'carwash'],
            ['name'=>'Candra Wijaya', 'email'=>'candra@mataair.com', 'phone'=>'08333333333', 'specialization'=>'detailing'],
            ['name'=>'Deni Kusuma',   'email'=>'deni@mataair.com',   'phone'=>'08444444444', 'specialization'=>'carwash'],
            ['name'=>'Erik Setiawan', 'email'=>'erik@mataair.com',   'phone'=>'08555555555', 'specialization'=>'keduanya'],
        ];

        foreach ($karyawanList as $k) {
            User::updateOrCreate(
                ['email' => $k['email']],
                [
                    'name'           => $k['name'],
                    'phone'          => $k['phone'],
                    'role'           => 'karyawan',
                    'specialization' => $k['specialization'],
                    'is_available'   => 1,
                    'rating_avg'     => 0,
                    'rating_count'   => 0,
                    'password'       => Hash::make('password'),
                ]
            );
        }

        // ── Services Detailing ────────────────────────────────
        $detailing = [
            ['name'=>'Express Polish',   'desc'=>'One step polish, interior vacuum, clean wheels, rims, and tire polish.',                                                                                                                          'prices'=>['Normal'=>700000,  'Large'=>800000,  'Exotic'=>800000]],
            ['name'=>'Glass Cleaning',   'desc'=>'Clean the whole windows (inside and out) plus extra glass protection.',                                                                                                                           'prices'=>['Normal'=>500000,  'Large'=>600000,  'Exotic'=>650000]],
            ['name'=>'Exterior Detail',  'desc'=>'Complete cleaning of car - exterior surfaces include body, glass, tires and rims.',                                                                                                               'prices'=>['Normal'=>1300000, 'Large'=>1500000, 'Exotic'=>1650000]],
            ['name'=>'Interior Detail',  'desc'=>'Clean carpet and upholstery, dashboard, carseats, door trim and windows (interior only).',                                                                                                        'prices'=>['Normal'=>800000,  'Large'=>900000,  'Exotic'=>900000]],
            ['name'=>'Complete Detail',  'desc'=>'Include both the interior and exterior detail services plus engine cleaning for one price.',                                                                                                       'prices'=>['Normal'=>2200000, 'Large'=>2500000, 'Exotic'=>2850000]],
            ['name'=>'Coating',          'desc'=>'Using paint correction to create a glass like shield to protect your car paint for up to 12 month (include 2x maintenance within 6 month).',                                                     'prices'=>['Normal'=>5200000, 'Large'=>6000000, 'Exotic'=>7000000]],
            ['name'=>'Coating Plus',     'desc'=>'Complete coating process including interior.',                                                                                                                                                    'prices'=>['Normal'=>5700000, 'Large'=>6500000, 'Exotic'=>7500000]],
        ];

        foreach ($detailing as $service) {
            foreach ($service['prices'] as $size => $price) {
                Service::updateOrCreate(
                    ['category'=>'detailing', 'name'=>$service['name'], 'size'=>$size],
                    ['price'=>$price, 'description'=>$service['desc'], 'is_active'=>1]
                );
            }
        }

        // ── Services Carwash ──────────────────────────────────
        Service::updateOrCreate(
            ['category'=>'carwash', 'name'=>'Regular Wash'],
            ['size'=>null, 'price'=>80000, 'is_active'=>1,
             'description'=>'Hydraulic power system-based car washing utilizes hydraulic power to effectively clean various parts of a vehicle, including the interior, exterior, and undercarriage.']
        );

        Service::updateOrCreate(
            ['category'=>'carwash', 'name'=>'Special Wash'],
            ['size'=>null, 'price'=>150000, 'is_active'=>1,
             'description'=>'Hydraulic system-based car washing utilizes hydraulic power to effectively clean various parts of a vehicle. Enhanced with Meguiar\'s Gold Class Wax for the car\'s body.']
        );
    }
}