<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'bike'],
            
            // Electronics
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
            ['name' => 'water Dispenser '],
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

            // Personal Care
            ['name' => 'Hair Dryer'],
            ['name' => 'Trimmer'],
            ['name' => 'Electric Toothbrush'],
            ['name' => 'Massager'],

            // Sports & Outdoors
            ['name' => 'Fitness Tracker'],
            ['name' => 'Bicycle'],


            // Miscellaneous
            ['name' => 'Drone'],
            ['name' => 'Power Bank'],
            ['name' => 'VR Headset'],
            ['name' => 'E-Reader'],
            ['name' => 'External Hard Drive'],
            ['name' => 'Others']

        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
