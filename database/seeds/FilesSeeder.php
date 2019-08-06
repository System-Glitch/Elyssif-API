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

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;

class FilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userCount = User::all()->count();

        if($userCount > 5) {

            User::all()->each(function($user) {
                $id1 = $user->id;

                for($i = 0; $i < 5; $i++){
                    $id2 = User::inRandomOrder()->first()->id;

                    factory(File::class)->create([
                        'sender_id' => $id1,
                        'recipient_id' => $id2,
                    ]);
                }
            });
        } else {
            $error = "Not enough users found for files seeding (only ".$userCount."). Users seeder must have failed.";
            $this->command->error($error);
        }
    }
}
