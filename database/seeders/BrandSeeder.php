<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure Categories exist before creating brands
        $categories = [
            ['name' => 'bike'],
            ['name' => 'Laptop'],
            ['name' => 'Tablet'],
            ['name' => 'phone'],
            ['name' => 'LED'],
            ['name' => 'Headphones'],
            ['name' => 'Speakers'],
            ['name' => 'Camera'],
            ['name' => 'Smartwatch'],
            ['name' => 'Printer'],
            ['name' => 'Gaming Console'],
            ['name' => 'Router'],
            ['name' => 'Projector'],
            // Home Appliances
            ['name' => 'Refrigerator'],
            ['name' => 'Microwave'],
            ['name' => 'Washing Machine'],
            ['name' => 'Air Conditioner'],
            ['name' => 'Vacuum Cleaner'],
            ['name' => 'Water Dispenser'],
            ['name' => 'Mixer Grinder'],
            ['name' => 'Iron'],
            ['name' => 'Water Heater'],
            ['name' => 'Geyser'],
            ['name' => 'Fan'],
            ['name' => 'Air Cooler'],
            ['name' => 'Room Heater'],
            ['name' => 'Electric Kettle'],
            ['name' => 'Juicer'],
            ['name' => 'Induction Cooktop'],
            ['name' => 'Coffee Maker'],
            ['name' => 'Toaster'],
            ['name' => 'Sandwich Maker'],
            ['name' => 'Food Processor'],
            ['name' => 'Electric Cooker'],
            ['name' => 'Hand Blender'],
            ['name' => 'Pop-up Toaster'],
            ['name' => 'Electric Tandoor'],
            ['name' => 'Roti Maker'],
            ['name' => 'Electric Chimney'],
            ['name' => 'Dishwasher'],
            ['name' => 'Air Fryer'],
            ['name' => 'Barbeque Grill'],
            ['name' => 'Cooking Range'],
            ['name' => 'Oven'],
            ['name' => 'Steam Iron'],
            ['name' => 'Dry Iron'],
            ['name' => 'Sewing Machine'],
            ['name' => 'Hair Dryer'],
            ['name' => 'Trimmer'],
            ['name' => 'Electric Toothbrush'],
            ['name' => 'Massager'],
            ['name' => 'Fitness Tracker'],
            ['name' => 'Bicycle'],
            ['name' => 'Monitor'],
            ['name' => 'Drone'],
            ['name' => 'Power Bank'],
            ['name' => 'VR Headset'],
            ['name' => 'E-Reader'],
            ['name' => 'External Hard Drive'],
            ['name' => 'Others'],
        ];

        // Create or fetch categories
        foreach ($categories as $categoryData) {
            $category = Category::firstOrCreate($categoryData);

            // Create brands for each category
            $brands = match ($category->name) {
                'bike' => [
                    ['name' => 'Honda', 'category_id' => $category->id],
                    ['name' => 'United', 'category_id' => $category->id],
                    ['name' => 'Suzuki', 'category_id' => $category->id],
                    ['name' => 'Yamaha', 'category_id' => $category->id],
                    ['name' => 'Unique', 'category_id' => $category->id],
                    ['name' => 'Benelli', 'category_id' => $category->id],
                    ['name' => 'Hero', 'category_id' => $category->id],
                    ['name' => 'Crown', 'category_id' => $category->id],
                    ['name' => 'Metro', 'category_id' => $category->id],
                    ['name' => 'Evee', 'category_id' => $category->id],
                    ['name' => 'Jolta Electric', 'category_id' => $category->id],
                    ['name' => 'Super Power', 'category_id' => $category->id],
                    ['name' => 'Super Star', 'category_id' => $category->id],
                    ['name' => 'Bingo Electric', 'category_id' => $category->id],
                    ['name' => 'Road Prince', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Laptop' => [
                    ['name' => 'Dell', 'category_id' => $category->id],
                    ['name' => 'HP', 'category_id' => $category->id],
                    ['name' => 'Apple', 'category_id' => $category->id],
                    ['name' => 'Lenovo', 'category_id' => $category->id],
                    ['name' => 'Acer', 'category_id' => $category->id],
                    ['name' => 'Asus', 'category_id' => $category->id],
                    ['name' => 'MSI', 'category_id' => $category->id],
                    ['name' => 'Razer', 'category_id' => $category->id],
                    ['name' => 'Microsoft', 'category_id' => $category->id],
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Tablet', 'phone'  => [
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Apple', 'category_id' => $category->id],
                    ['name' => 'OnePlus', 'category_id' => $category->id],
                    ['name' => 'Huawei', 'category_id' => $category->id],
                    ['name' => 'Google', 'category_id' => $category->id],
                    ['name' => 'Honor', 'category_id' => $category->id],
                    ['name' => 'HTC', 'category_id' => $category->id],
                    ['name' => 'Infinix', 'category_id' => $category->id],
                    ['name' => 'itel', 'category_id' => $category->id],
                    ['name' => 'Oppo', 'category_id' => $category->id],
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'ZTE', 'category_id' => $category->id],
                    ['name' => 'Xiaomi', 'category_id' => $category->id],
                    ['name' => 'Tecno', 'category_id' => $category->id],
                    ['name' => 'vivo', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],


                ],
                'LED' => [
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'LG', 'category_id' => $category->id],
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Haier', 'category_id' => $category->id],
                    ['name' => 'Ecostar', 'category_id' => $category->id],
                    ['name' => 'ITEL', 'category_id' => $category->id],
                    ['name' => 'Panasonic', 'category_id' => $category->id],
                    ['name' => 'TCL', 'category_id' => $category->id],
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'Dawlance', 'category_id' => $category->id],
                    ['name' => 'Oxy', 'category_id' => $category->id],
                    ['name' => 'orient ', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],


                ],
                'Camera' => [
                    ['name' => 'Canon', 'category_id' => $category->id],
                    ['name' => 'Nikon', 'category_id' => $category->id],
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'Panasonic', 'category_id' => $category->id],
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],


                ],
                'Headphones' => [
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Apple', 'category_id' => $category->id],
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'Zero', 'category_id' => $category->id],
                    ['name' => 'JBL', 'category_id' => $category->id],
                    ['name' => 'Beats', 'category_id' => $category->id],
                    ['name' => 'Audionic', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Speakers' => [
                    ['name' => 'Bose', 'category_id' => $category->id],
                    ['name' => 'JBL', 'category_id' => $category->id],
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'Zero', 'category_id' => $category->id],
                    ['name' => 'JBL', 'category_id' => $category->id],
                    ['name' => 'Beats', 'category_id' => $category->id],
                    ['name' => 'Audionic', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Smartwatch' => [
                    ['name' => 'Apple', 'category_id' => $category->id],
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Fitbit', 'category_id' => $category->id],
                    ['name' => 'Huawei', 'category_id' => $category->id],
                    ['name' => 'Xiaomi', 'category_id' => $category->id],
                    ['name' => 'Audionic', 'category_id' => $category->id],
                    ['name' => 'Zero', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Printer' => [
                    ['name' => 'HP', 'category_id' => $category->id],
                    ['name' => 'Epson', 'category_id' => $category->id],
                    ['name' => 'Canon', 'category_id' => $category->id],
                    ['name' => 'Brother', 'category_id' => $category->id],
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Gaming Console' => [
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'Xbox', 'category_id' => $category->id],
                    ['name' => 'Nintendo', 'category_id' => $category->id],
                    ['name' => 'Sega', 'category_id' => $category->id],
                    ['name' => 'Atari', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Router' => [
                    ['name' => 'TP-Link', 'category_id' => $category->id],
                    ['name' => 'Linksys', 'category_id' => $category->id],
                    ['name' => 'D-Link', 'category_id' => $category->id],
                    ['name' => 'Netgear', 'category_id' => $category->id],
                    ['name' => 'Tanda', 'category_id' => $category->id],
                    ['name' => 'Huawei', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Projector' => [
                    ['name' => 'Epson', 'category_id' => $category->id],
                    ['name' => 'BenQ', 'category_id' => $category->id],
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'Acer', 'category_id' => $category->id],
                    ['name' => 'Optoma', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],


                // Home Appliances
                'Refrigerator', 'Washing Machine', 'Microwave', 'Air Conditioner', 'Vacuum Cleaner', 'Water Dispenser', 'Oven' => [
                    ['name' => 'Whirlpool', 'category_id' => $category->id],
                    ['name' => 'Haier', 'category_id' => $category->id],
                    ['name' => 'Dawlance', 'category_id' => $category->id],
                    ['name' => 'Orient', 'category_id' => $category->id],
                    ['name' => 'Panasonic', 'category_id' => $category->id],
                    ['name' => 'Kenwood', 'category_id' => $category->id],
                    ['name' => 'Hitachi', 'category_id' => $category->id],
                    ['name' => 'Gree', 'category_id' => $category->id],
                    ['name' => 'Pel', 'category_id' => $category->id],
                    ['name' => 'Sharp', 'category_id' => $category->id],
                    ['name' => 'Wave', 'category_id' => $category->id],
                    ['name' => 'Super Asia', 'category_id' => $category->id],
                    ['name' => 'Dyson', 'category_id' => $category->id],
                    ['name' => 'Eureka Forbes', 'category_id' => $category->id],
                    ['name' => 'Philips', 'category_id' => $category->id],
                    ['name' => 'Kent', 'category_id' => $category->id],
                    ['name' => 'National', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Mixer Grinder', 'Juicer', 'Hand Blender' => [
                    ['name' => 'Panasonic', 'category_id' => $category->id],
                    ['name' => 'Philips', 'category_id' => $category->id],
                    ['name' => 'National', 'category_id' => $category->id],
                    ['name' => 'Kenwood', 'category_id' => $category->id],
                    ['name' => 'Anex', 'category_id' => $category->id],
                    ['name' => 'Westpoint', 'category_id' => $category->id],
                    ['name' => 'Black & Decker', 'category_id' => $category->id],
                    ['name' => 'Braun', 'category_id' => $category->id],
                    ['name' => 'Sencor', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Iron', 'Steam Iron', 'Dry Iron', 'Electric Kettle', 'Coffee Maker', 'Toaster', 'Sandwich Maker' => [
                    ['name' => 'Philips', 'category_id' => $category->id],
                    ['name' => 'Panasonic', 'category_id' => $category->id],
                    ['name' => 'National', 'category_id' => $category->id],
                    ['name' => 'Kenwood', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Air Cooler', 'Fan', 'Room Heater' => [
                    ['name' => 'Super Star Asia', 'category_id' => $category->id],
                    ['name' => 'Three Star Asia', 'category_id' => $category->id],
                    ['name' => 'Pak', 'category_id' => $category->id],
                    ['name' => 'Punjab', 'category_id' => $category->id],
                    ['name' => 'Indes', 'category_id' => $category->id],
                    ['name' => 'Super Asia', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Dishwasher', 'Electric Chimney', 'Oven' => [
                    ['name' => 'Bosch', 'category_id' => $category->id],
                    ['name' => 'Faber', 'category_id' => $category->id],
                    ['name' => 'LG', 'category_id' => $category->id],
                ],
                'Hair Dryer' => [
                    ['name' => 'Philips', 'category_id' => $category->id],
                    ['name' => 'Panasonic', 'category_id' => $category->id],
                    ['name' => 'Dyson', 'category_id' => $category->id],
                    ['name' => 'Braun', 'category_id' => $category->id],
                    ['name' => 'Remington', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Trimmer' => [
                    ['name' => 'Philips', 'category_id' => $category->id],
                    ['name' => 'Panasonic', 'category_id' => $category->id],
                    ['name' => 'Braun', 'category_id' => $category->id],
                    ['name' => 'Wahl', 'category_id' => $category->id],
                    ['name' => 'Remington', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Electric Toothbrush' => [
                    ['name' => 'Oral-B', 'category_id' => $category->id],
                    ['name' => 'Philips', 'category_id' => $category->id],
                    ['name' => 'Colgate', 'category_id' => $category->id],
                    ['name' => 'Fairywill', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Massager' => [
                    ['name' => 'HoMedics', 'category_id' => $category->id],
                    ['name' => 'Naipo', 'category_id' => $category->id],
                    ['name' => 'Renpho', 'category_id' => $category->id],
                    ['name' => 'Theragun', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Fitness Tracker' => [
                    ['name' => 'Fitbit', 'category_id' => $category->id],
                    ['name' => 'Garmin', 'category_id' => $category->id],
                    ['name' => 'Xiaomi', 'category_id' => $category->id],
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Bicycle' => [
                    ['name' => 'Giant', 'category_id' => $category->id],
                    ['name' => 'Trek', 'category_id' => $category->id],
                    ['name' => 'Specialized', 'category_id' => $category->id],
                    ['name' => 'Cannondale', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Monitor' => [
                    ['name' => 'Dell', 'category_id' => $category->id],
                    ['name' => 'HP', 'category_id' => $category->id],
                    ['name' => 'Samsung', 'category_id' => $category->id],
                    ['name' => 'LG', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],

                'Drone' => [
                    ['name' => 'DJI', 'category_id' => $category->id],
                    ['name' => 'Parrot', 'category_id' => $category->id],
                    ['name' => 'Yuneec', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Power Bank' => [
                    ['name' => 'Anker', 'category_id' => $category->id],
                    ['name' => 'Xiaomi', 'category_id' => $category->id],
                    ['name' => 'RAVPower', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'VR Headset' => [
                    ['name' => 'Oculus', 'category_id' => $category->id],
                    ['name' => 'HTC', 'category_id' => $category->id],
                    ['name' => 'Sony', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'E-Reader' => [
                    ['name' => 'Amazon', 'category_id' => $category->id],
                    ['name' => 'Kobo', 'category_id' => $category->id],
                    ['name' => 'Barnes & Noble', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'External Hard Drive' => [
                    ['name' => 'Western Digital', 'category_id' => $category->id],
                    ['name' => 'Seagate', 'category_id' => $category->id],
                    ['name' => 'Toshiba', 'category_id' => $category->id],
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                'Others' => [
                    ['name' => 'Others', 'category_id' => $category->id],
                ],
                default => [],
            };

            foreach ($brands as $brand) {
                Brand::create($brand);
            }
        }
    }
}
