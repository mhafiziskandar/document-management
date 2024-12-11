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
        Category::insert([
            [ 'name' => 'Penduduk' ], 
            [ 'name' => 'Kelab & Persatuan' ], 
            [ 'name' => 'Pendidikan & Kemahiran' ], 
            [ 'name' => 'Kesihatan'], 
            [ 'name' => 'Sukarelawan' ], 
            [ 'name' => 'Gejala Sosial' ], 
            [ 'name' => 'Sukan & Rekreasi' ], 
            [ 'name' => 'Pengantarabangsaaan'], 
            [ 'name' => 'Kepimpinan'], 
            [ 'name' => 'Media & Teknologi' ], 
            [ 'name' => 'Sosialisasi Politik' ], 
            [ 'name' => 'Pembangunan Belia Positif' ], 
            [ 'name' => 'Ekonomi' ], 
            [ 'name' => 'Guna Tenaga'], 
            [ 'name' => 'Covid-19' ], 
            [ 'name' => 'Fasiliti & Kemudahan' ], 
            [ 'name' => 'Industri Sukan' ], 
            [ 'name' => 'Lain-lain']
        ]);
    }
}
