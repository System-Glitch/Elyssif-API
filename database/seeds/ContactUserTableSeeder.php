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

        App\Models\User::all()->each(function($user) use ($faker){
        	$id1 = $user->id;
        	
        	for($i = 0; $i < 5; $i++){
        		$id2 = App\Models\User::inRandomOrder()->first()->id;

        		$contactData = array([
        			'user_id' => $id1,
	        		'contact_id' => $id2,
	        		'notes' => $faker->text($maxNbChars = 200),
	        		'created_at' => now(),
	        		'updated_at' => now(),
        		]);

        		// Syntax not working
	        	// $user->contacts()->create($contactData);

	        	DB::table('contact_user')->insert($contactData);
        	}
        });
    }
}
