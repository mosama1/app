<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\User;
use App\Profile;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        $sex = ['Femenino','Masculino'];
        User::truncate();
        Profile::truncate();
        foreach (range(1, 10) as $i) {
            if ($i === 1) {
                User::create([
                    'user_name' => 'mosama',
                    'email' => 'jm_16_2008@hotmail.com',
                    'password' => '$2y$10$1YzIytdMfTG9P6HlmrBPOOvS2Yku1ozvBYADT0t8tI2FhN.ga2VOW',
                    'api_token' => str_random(60)
                ]);
                
            }else {
                User::create([
                    'user_name' => $faker->word,
                    'email' => $faker->email,
                    'password' => bcrypt('password'),
                    'api_token' => str_random(60)
                ]);
            }
            $name_img = "test".$i.".png";
            copy(base_path('public/img/test.png'), base_path('public/img/users/'.$name_img));
            Profile::create([
                'user_id' => $i,
                'name' => $faker->firstname,
                'last_name' => $faker->lastname,
                'sex' => $sex[rand(0,1)],
                'direction' => $faker->sentence,
                'phone' => $faker->phoneNumber,
                'birthdate' => $faker->date,
                'image' => $name_img,
            ]);
        }
    }
}
