<?php

use App\Models\User;
use Illuminate\Database\Seeder;

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

        if($nbUsers > 5){
            User::all()->each(function($user) use ($faker){
                $idOwner = $user->id;

                $takenIds = array();
                $takenIds[] = $idOwner;

                for($i = 0; $i < 5; $i++){
                    $contact = User::inRandomOrder()->whereNotIn('id', $takenIds)->first();

                    $idTarget = $contact->id;
                    $takenIds[] = $idTarget;

                    $notes = [
                        'notes' => $faker->text(200),
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