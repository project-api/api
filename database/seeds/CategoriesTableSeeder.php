<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
          'name' => 'T-Shirts',
          'quatity' => 0,
          'description' => 'Category Fashion: T-Shirts',
          'created_at' => date('Y-m-d h:i:s'),
          'updated_at' => date('Y-m-d h:i:s'),
        ]);

        DB::table('categories')->insert([
          'name' => 'Shorts',
          'quatity' => 0,
          'description' => 'Category Fashion: Shorts',
          'created_at' => date('Y-m-d h:i:s'),
          'updated_at' => date('Y-m-d h:i:s'),
        ]);

        DB::table('categories')->insert([
          'name' => 'Jeans',
          'quatity' => 0,
          'description' => 'Category Fashion: Jeans',
          'created_at' => date('Y-m-d h:i:s'),
          'updated_at' => date('Y-m-d h:i:s'),
        ]);

        DB::table('categories')->insert([
          'name' => 'Pants',
          'quatity' => 0,
          'description' => 'Category Fashion: Pants',
          'created_at' => date('Y-m-d h:i:s'),
          'updated_at' => date('Y-m-d h:i:s'),
        ]);
    }
}
