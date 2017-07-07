<template>
	<div class="recipe__show">
		<div class="recipe__row">
			<div class="recipe__image">
	            <transition name="transition">
					<div class="recipe__box transition" v-if="recipe.image">
						<img :src="`/img/recipes/${recipe.image}`" v-if="isLoad">
					</div>
   				</transition>
			</div>
			<div class="recipe__details">
	            <transition name="transition">
					<div class="recipe__details_inner transition" v-if="isLoad">
						<small>Subido por: <router-link :to="`/users/${recipe.user_id}`">{{recipe.user.user_name}}</router-link></small>
						<h1 class="recipe__title">{{recipe.name}}</h1>
						<p class="recipe__description">{{recipe.description}}</p>
						<div class="" v-if="auth.api_token && auth.user_id === recipe.user.id">
							<router-link :to="`/recipes/${recipe.id}/edit`" class="btn btn__primary">
								Editar
							</router-link>
							<button class="btn btn__danger" @click="remove" :disabled="isRemoving">
								Eliminar
							</button>
						</div>
					</div>
   				</transition>
			</div>
		</div>
		<div class="recipe__row">
			<div class="recipe__ingredients">
	            <transition name="transition">
					<div class="recipe__box transition" v-if="isLoad">
						<h3 class="recipe__sub_title">Ingredientes</h3>
						<ul>
							<li v-for="ingredient in recipe.ingredients">
								<span>{{ingredient.name}}</span>
								<span>{{ingredient.qty}}</span>
							</li>
						</ul>
					</div>
   				</transition>
			</div>
			<div class="recipe__directions">
	            <transition name="transition">
					<div class="recipe__directions_inner transition" v-if="isLoad">
						<h3 class="recipe__sub_title">
							Direcciones
						</h3>
						<ul>
							<li v-for="(direction, i) in recipe.directions">
								<p>
									<strong>{{i + 1}}- </strong>
									{{direction.description}}
								</p>
							</li>
						</ul>
					</div>
   				</transition>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	import Flash from '../../helpers/flash.js'
	import Auth from '../../store/auth.js'
	import { get, del } from '../../helpers/api.js'
	export default {
		data() {
			return {
				auth: Auth.state,
				isRemoving: false,
				isLoad: false,
				recipe: {
					user: {},
					ingredients: [],
					directions: []
				}
			}
		},
		created() {
			get(`/api/recipes/${this.$route.params.id}`)
				.then((res) => {
					this.recipe = res.data.recipe
					this.isLoad = true
				})
		},
		methods: {
			remove() {
				this.isRemoving = true
				del(`/api/recipes/${this.$route.params.id}`)
					.then((res) => {
						if (res.data.deleted) {
							Flash.setSuccess(res.data.message)
							this.$router.push('/recipes')
						}
					})
			}
		}
	}
</script>
