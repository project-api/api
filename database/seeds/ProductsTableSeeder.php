<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
          'name' => 'ASOS Scoop Neck T-Shirt In Brown',
          'price' => 160000,
          'quatity' => 20,
          'cat_id' => 1,
          'description' => 'T-Shirt 01',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);

        DB::table('products')->insert([
          'name' => 'Adidas Originals California T-Shirt',
          'price' => 190000,
          'quatity' => 20,
          'cat_id' => 1,
          'description' => 'T-Shirt 02',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);

        DB::table('products')->insert([
          'name' => 'ASOS T-Shirt With Crew Neck And Roll Sleeve In Pink',
          'price' => 170000,
          'quatity' => 20,
          'cat_id' => 1,
          'description' => 'T-Shirt 03',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);

        DB::table('products')->insert([
          'name' => 'ASOS Slim Basketball Shorts With Elasticated Waist In Pink',
          'price' => 270000,
          'quatity' => 20,
          'cat_id' => 2,
          'description' => 'Short 01',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);

        DB::table('products')->insert([
          'name' => 'ASOS TALL Slim Chino Shorts In Navy',
          'price' => 275000,
          'quatity' => 20,
          'cat_id' => 2,
          'description' => 'Short 02',
          'created_at' => Carbon::now(new DateTimeZone('Europe/London')),
          'updated_at' => null,
        ]);
    }
}
