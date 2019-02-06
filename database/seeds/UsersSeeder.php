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
    public $nbUsers = 50;

    public function run()
    {
        factory(User::class, $this->nbUsers)->create();
    }
}
