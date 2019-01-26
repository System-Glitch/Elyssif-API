<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactsSeeder extends Seeder
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
        $nbUsers = User::all()->count();
        if($nbUsers == 50){
	        User::all()->each(function($user) use ($faker){
	        	for($i = 0; $i < 5; $i++){
	        		$contact = User::inRandomOrder()->where('id', '!=', $user->id)->first()->id;
	        		$notes = [
	        			'notes' => $faker->text($maxNbChars = 200),
	        		];

		        	$user->contacts()->attach($contact, $notes);
	        	}
	        });
	    }else{
	    	$error = "Not enough users found for contacts seeding (only ".$nbUsers."). Users seeder must have failed.";
	    	$this->command->error($error);
	    }
    }
}
