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
            [ 'name' => 'Human Resource/Admin' ], 
            [ 'name' => 'Legal' ], 
            [ 'name' => 'Public Relation' ], 
            [ 'name' => 'Sales' ], 
            [ 'name' => 'Software' ], 
            [ 'name' => 'Technical' ], 
            [ 'name' => 'Finance' ], 
            [ 'name' => 'Customer Care' ], 
            [ 'name' => 'Credit Management' ]
        ]);
    }
}
