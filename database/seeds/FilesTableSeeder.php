<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use App\Models\User;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seeding 5 files from each user to random users (can be equal)
        App\Models\User::all()->each(function($user){
        	$id1 = $user->id;
        	
        	for($i = 0; $i < 5; $i++){
        		$id2 = App\Models\User::inRandomOrder()->first()->id;

	        	factory(App\Models\File::class, 1)->create([
	    			'sender_id' => $id1,
	    			'recipient_id' => $id2,
				]);
        	}
        });
    }
}