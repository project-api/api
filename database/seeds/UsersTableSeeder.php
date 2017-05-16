<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
          'name' => 'admin',
          'email' => 'admin@example.com',
          'password' => bcrypt('123456'),
          'created_at' => date('Y-m-d\TH:i:s.u\Z'),
        ]);

        DB::table('users')->insert([
          'name' => 'user',
          'email' => 'user@example.com',
          'password' => bcrypt('123456'),
          'created_at' => date('Y-m-d\TH:i:s.u\Z'),
        ]);
    }
}
