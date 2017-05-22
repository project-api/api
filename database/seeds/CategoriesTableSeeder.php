<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


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
          'description' => 'Category Fashion: T-Shirts',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);

        DB::table('categories')->insert([
          'name' => 'Shorts',
          'description' => 'Category Fashion: Shorts',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);

        DB::table('categories')->insert([
          'name' => 'Jeans',
          'description' => 'Category Fashion: Jeans',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);

        DB::table('categories')->insert([
          'name' => 'Pants',
          'description' => 'Category Fashion: Pants',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);
    }
}
