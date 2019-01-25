<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use App\Models\User;

class FilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(User::all()->count() == 50){
	        // Seeding 5 files from each user to random users (can be equal)
	        User::all()->each(function($user){
	        	$id1 = $user->id;
	        	
	        	for($i = 0; $i < 5; $i++){
	        		$id2 = User::inRandomOrder()->first()->id;

		        	factory(File::class, 1)->create([
		    			'sender_id' => $id1,
		    			'recipient_id' => $id2,
					]);
	        	}
	        });
	    }else{
	    	$error = "Not enough users found for files seeding (only ".$nbUsers."). Users seeding must have fail.";
	    	$this->command->error($error);
	    }
    }
}
