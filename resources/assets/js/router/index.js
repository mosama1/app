import Vue from 'vue'
import VueRouter from 'vue-router'

//Auth
import Register from '../view/auth/Register.vue'
import Login from '../view/auth/Login.vue'
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
                {path: '/recipes', component: RecipeIndex},
                {path: '/recipes/page/:page', component: RecipeIndex},
                {path: '/recipes/search/:string', component: RecipeIndex},
                {path: '/recipes/create', component: RecipeForm, meta: {mode: 'create'}},
                {path: '/recipes/:id', component: RecipeShow},
                {path: '/recipes/:id/edit', component: RecipeForm, meta: {mode: 'edit'}},

                //User
                {path: '/users/:id', component: UserProfile},
                {path: '/users/:id/edit', component: UserEdit},
                {path: '/users', component: UserIndex},

	]
});

export default router
