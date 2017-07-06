<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\Recipe;
use App\RecipeIngredient;
use App\RecipeDirection;

class RecipesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $users = App\User::get();
        $users_array = array();
        foreach ($users as $key => $value) {
            $users_array[$key] = $value->id;
        }


        RecipeDirection::truncate();
        RecipeIngredient::truncate();
        Recipe::truncate();

        foreach (range(1, 350) as $i) {
            $name_img = "test".$i.".png";
            copy(base_path('public/img/test.png'), base_path('public/img/recipes/'.$name_img));

            $recipe = Recipe::create([
                'user_id' => $faker->randomElement($users_array),
                'name' => $faker->sentence,
                'description' => $faker->paragraph(mt_rand(5, 15)),
                'image' => $name_img,

            ]);
            foreach (range(1, mt_rand(3, 12)) as $j) {
                RecipeIngredient::create([
                    'recipe_id' => $recipe->id,
                    'name' => $faker->word,
                    'qty' => mt_rand(1, 12). 'Kg',
                ]);
            }
            foreach (range(1, mt_rand(5, 12)) as $k) {
                RecipeDirection::create([
                    'recipe_id' => $recipe->id,
                    'description' => $faker->sentence,
                ]);
            }
        }


    }
}
