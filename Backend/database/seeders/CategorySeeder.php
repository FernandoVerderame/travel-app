<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['label' => 'Culturale', 'icon' => 'fa-landmark'],
            ['label' => 'Natura', 'icon' => 'fa-tree'],
            ['label' => 'Ristorazione', 'icon' => 'fa-utensils'],
            ['label' => 'Intrattenimento', 'icon' => 'fa-masks-theater'],
            ['label' => 'Shopping', 'icon' => 'fa-cart-shopping'],
            ['label' => 'Benessere e Relax', 'icon' => 'fa-water-ladder'],
            ['label' => 'Fotografia o Panorami', 'icon' => 'fa-camera'],
            ['label' => 'Spiritualità', 'icon' => 'fa-church'],
            ['label' => 'Eventi Speciali', 'icon' => 'fa-calendar-days'],
            ['label' => 'Trasporti', 'icon' => 'fa-truck-plane'],
            ['label' => 'Lusso', 'icon' => 'fa-gem'],
            ['label' => 'Eventi Sportivi', 'icon' => 'fa-futbol'],
            ['label' => 'Avventura', 'icon' => 'fa-person-hiking'],
            ['label' => 'Festival Musicali', 'icon' => 'fa-guitar'],
            ['label' => 'Esplorazione Urbana', 'icon' => 'fa-city']
        ];

        foreach ($categories as $category) {
            $new_category = new Category();

            $new_category->label = $category['label'];
            $new_category->icon = $category['icon'];

            $new_category->save();
        }
    }
}
