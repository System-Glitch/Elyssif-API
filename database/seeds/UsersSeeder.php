<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    const nbUsers = 50;

    public function run()
    {
        factory(User::class, self::nbUsers)->create();
    }
}
