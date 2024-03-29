<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(OrderSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RolePermissionSeeder::class);
    }
}