<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
	{
		Eloquent::unguard();

		$this->call(UsersTableSeeder::class);
		$this->call(FilesTableSeeder::class);

		Eloquent::reguard();
	}
}