import Vue from 'vue'
import VueRouter from 'vue-router'

//Auth
import Register from '../view/Auth/Register.vue'
import Login from '../view/Auth/Login.vue'
//Recipes
import RecipeIndex from '../view/Recipes/Index.vue'
import RecipeShow from '../view/Recipes/Show.vue'
import RecipeForm from '../view/Recipes/Form.vue'
//User
import UserProfile from '../view/User/Profile.vue'
import UserEdit from '../view/User/Edit.vue'
import UserIndex from '../view/User/Index.vue'
Vue.use(VueRouter);

const router = new VueRouter({
	routes: [
		//Auth
                {path: '/register', component: Register},
                {path: '/login', component: Login},

                //Recipe
                {path: '/recipes', component: RecipeIndex, meta: {name: 'recipes'}},
                {path: '/recipes/page/:page', component: RecipeIndex, meta: {name: 'recipes'}},
				{path: '/recipes/search/:string', component: RecipeIndex, meta: {name: 'recipes'}},
				{path: '/recipes/search/:string/page/:page', component: RecipeIndex, meta: {name: 'recipes'}},
                {path: '/recipes/create', component: RecipeForm, meta: {mode: 'create', name: 'recipes'}},
                {path: '/recipes/:id', component: RecipeShow, meta: {name: 'recipes'}},
                {path: '/recipes/:id/edit', component: RecipeForm, meta: {mode: 'edit', name: 'recipes'}},

                //User
                {path: '/users/:id', component: UserProfile, meta: {name: 'users'}},
                {path: '/users/:id/edit', component: UserEdit, meta: {name: 'users'}},
                {path: '/users', component: UserIndex, meta: {name: 'users'}}
	]
});

export default router
