<?php

namespace Database\Seeders;

use App\Models\Cluster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClusterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cluster::insert([
            [
                'name' => 'Penyelidikan'
            ], [
                'name' => 'Program'
            ], [
                'name' => 'Penerbitan & Inovasi'
            ], [
                'name' => 'Parlimen'
            ], [
                'name' => 'Kewangan'
            ], [
                'name' => 'Data'
            ], [
                'name' => 'Dasar & Strategik'
            ]
        ]);
    }
}
