<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	/*
        factory(App\Models\User::class, 50)->create()->each(function ($contact) {
	        $contact->contacts(User::inRandomOrder()->get()->id, User::inRandomOrder()->get()->id, str_random(40));
	    });
	    */

	    factory(App\Models\User::class, 50)->create();

	    for($i = 0; $i < 30; $i++){
	    	$user_id1 = User::all()->random()->id;
	    	$user_id2 = User::all()->random()->id;
	    	while($user_id1 == $user_id2){
	    		$user_id2 = User::all()->random()->id;
	    	}
	    	$notes = "Lorem ipsum";
	    	$data[] = array(
	    		'user_id' => $user_id1,
	    		'contact_id' => $user_id2,
	    		'notes' => $notes,
	    		'created_at' => now(),
	    		'updated_at' => now()
	    	);

	    	DB::table('contact_user')->insert($data);
	    }
    }
}
