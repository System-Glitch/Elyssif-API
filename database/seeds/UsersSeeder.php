<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
