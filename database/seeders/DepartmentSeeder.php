<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            [
                'name' => 'human resource/Admin'
            ], [
                'name' => 'legal'
            ], [
                'name' => 'public relation'
            ], [
                'name' => 'sales'
            ], [
                'name' => 'software'
            ], [
                'name' => 'technical'
            ], [
                'name' => 'finance'
            ], [
                'name' => 'customer care'
            ], [
                'name' => 'credit management'
            ]
        ]);
    }
}
