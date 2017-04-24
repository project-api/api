<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // Call CategoriesTableSeeder
        $this->call(CategoriesTableSeeder::class);

        // Call ProductsTableSeeder
        //$this->call(ProductsTableSeeder::class);
    }
}
