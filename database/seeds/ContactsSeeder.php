<?php
/*
 * Elyssif-API
 * Copyright (C) 2019 Jérémy LAMBERT (System-Glitch)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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