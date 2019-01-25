<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        // Seeding 5 contacts from each user to random users (can not be equal)

        if(User::all()->count() == 50){
	        User::all()->each(function($user) use ($faker){
	        	for($i = 0; $i < 5; $i++){
	        		$contact = User::inRandomOrder()->where('id', '!=', $user->id)->first()->id;

		        	$user->contacts()->attach($contact, $faker->text($maxNbChars = 200))->create();
	        	}
	        });
	    }else{
	    	// Message need to produced
	    }
    }
}