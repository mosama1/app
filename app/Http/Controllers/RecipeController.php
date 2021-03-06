<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Recipe;
use App\RecipeIngredient;
use App\RecipeDirection;
use File;

class RecipeController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:api')
    		->except('index', 'show', 'search');
    }

    public function index()
    {
    	$recipes = Recipe::with('user')->orderBy('created_at', 'desc')->paginate(12);

    	return response()->json([
    			'recipes' => $recipes
    		]);
    }
    public function search(Request $request)
    {
    	$recipes = Recipe::with('user')->where([
								['name', 'LIKE', '%'.$request->string.'%'],
							])->take(10)->paginate(12);

        // dd($recipes);

    	return response()->json([
    			'recipes' => $recipes
    		]);
    }

    public function create()
    {
    	$form = Recipe::form();

    	return response()->json([
    			'form' => $form
    		]);
    }

    public function store(Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required|max:255',
    		'description' => 'required|max:300',
    		'image' => 'required|image',
    		'ingredients' => 'required|array|min:1',
    		'ingredients.*.name' => 'required|max:255',
    		'ingredients.*.qty' => 'required|max:255',
    		'directions' => 'required|array|min:1',
    		'directions.*.description' => 'required|max:3000',


		]);

		$ingredients = [];

		foreach ($request->ingredients as $ingredient) {
			$ingredients[] = new RecipeIngredient($ingredient);
		}

		$directions = [];

		foreach ($request->directions as $direction) {
			$directions[] = new RecipeDirection($direction);
		}

		if (!$request->hasFile('image') && !$request->file('image')->isValid()) {
			return abort(404, 'Imagen no cargada');
		}

		$filename = $this->getFileName($request->image);

		$request->image->move(base_path('public/img/recipes'), $filename);

		$recipe = new Recipe($request->all());

		$recipe->image = $filename;

		$request->user()
			->recipes()->save($recipe);

		$recipe->directions()
			->saveMany($directions);

		$recipe->ingredients()
			->saveMany($ingredients);


    	return response()->json([
    			'saved' => true,
    			'id' => $recipe->id,
    			'message' => 'Receta guardad satisfactoriamente!!',
    		]);
    }

    protected function getFileName($file)
    {
    	return str_random(32).'.'.$file->extension();
    }

    public function show($id)
    {
    	$recipe = Recipe::with(['user', 'ingredients', 'directions'])->findOrFail($id);


    	return response()->json([
    			'recipe' => $recipe
    		]);
    }

    public function edit($id, Request $request)
    {
    	$form = $request->user()->recipes()
    		->with(['ingredients' => function($query){
    			$query->get(['id', 'name', 'qty']);
    		}, 'directions' => function($query){
    			$query->get(['id', 'description']);
    		}])
    		->findOrFail($id, [
    			'id', 'name', 'description', 'image'
			]);

    	return response()->json([
			'form' => $form
			]);
    }
    public function update($id, Request $request)
    {
    	$this->validate($request, [
    		'name' => 'required|max:255',
    		'description' => 'required|max:3000',
    		'image' => 'image',
    		'ingredients' => 'required|array|min:1',
    		'ingredients.*.id' => 'integer|exists:recipe_ingredients',
    		'ingredients.*.name' => 'required|max:255',
    		'ingredients.*.qty' => 'required|max:255',
    		'directions' => 'required|array|min:1',
    		'directions.*.id' =>  'integer|exists:recipe_directions',

    		'directions.*.description' => 'required|max:3000',
		]);

		$recipe = $request->user()->recipes()
			->findOrFail($id);

		$ingredients = [];

		$ingredientsUpdated = [];

		foreach ($request->ingredients as $ingredient) {
			if (isset($ingredient['id'])) {
				RecipeIngredient::where('recipe_id', $recipe->id)
					->where('id', $ingredient['id'])
					->update($ingredient);

				$ingredientsUpdated[] = $ingredient['id'];


			}else {
				$ingredients[] = new RecipeIngredient($ingredient);
			}
		}

		$directions = [];

		$directionsUpdated = [];

		foreach ($request->directions as $direction) {
			if (isset($direction['id'])) {
				RecipeDirection::where('recipe_id', $recipe->id)
					->where('id', $direction['id'])
					->update($direction);

				$directionsUpdated[] = $direction['id'];


			}else {
				$directions[] = new RecipeDirection($direction);
			}
		}

		$recipe->name = $request->name;
		$recipe->description = $request->description;

		if ($request->hasFile('image') && $request->file('image')->isValid()) {
			$filename = $this->getFileName($request->image);
			$request->image->move(base_path('public/img/recipes'), $filename);

			//remove old image

			File::delete(base_path('public/img/recipes/'.$recipe->image));

			$recipe->image = $filename;

		}

		$recipe->save();

		//delete all ids exept updated

		RecipeIngredient::whereNotIn('id', $ingredientsUpdated)
			->where('recipe_id', $recipe->id)
			->delete();

		RecipeDirection::whereNotIn('id', $directionsUpdated)
			->where('recipe_id', $recipe->id)
			->delete();

		//Create new items if exists

		if (count($ingredients)) {
			$recipe->ingredients()->saveMany($ingredients);
		}

		if (count($directions)) {
			$recipe->directions()->saveMany($directions);
		}

    	return response()->json([
			'saved' => true,
			'id' => $recipe->id,
			'message' => 'Receta actualiza satisfactoriamente!!',
			]);
    }

    public function destroy($id, Request $request)
    {
    	$recipe = $request->user()->recipes()
    		->findOrFail($id);

    	RecipeIngredient::where('recipe_id', $recipe->id)->delete();

    	RecipeDirection::where('recipe_id', $recipe->id)->delete();

    	File::delete(base_path('public/img/recipes/'.$recipe->image));

    	$recipe->delete();

    	return response()->json([
			'deleted' => true,
			'id' => $recipe->id,
			'message' => 'Receta eliminada satisfactoriamente!!',
			]);
    }
}
