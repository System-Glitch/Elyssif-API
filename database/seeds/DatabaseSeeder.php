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

		if(App\Models\User::all()->count() == 50){
			$this->call(FilesTableSeeder::class);
			$this->call(ContactUserTableSeeder::class);
		}else{
			// Error Message need to be produced
		}

		Eloquent::reguard();
	}
}