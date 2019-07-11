<?php

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
