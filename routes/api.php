<?php

	Route::post('/register', 'AuthController@register');
	Route::post('/login', 'AuthController@login');
	Route::post('/logout', 'AuthController@logout');	

	Route::get('recipes/search', 'RecipeController@search');	
	Route::resource('recipes', 'RecipeController');
	Route::resource('users', 'UserController');
