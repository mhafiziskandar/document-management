<?php

namespace Database\Seeders;

use App\Models\FolderType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FolderTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FolderType::insert([
            [
                'name' => 'Data'
            ], [
                'name' => 'Manual'
            ], [
                'name' => 'Laporan'
            ], [
                'name' => 'Pautan Artikel'
            ], [
                'name' => 'Instrumen'
            ], [
                'name' => 'Kertas Konsep'
            ], [
                'name' => 'Keratan Akhbar'
            ]
        ],);
    }
}
