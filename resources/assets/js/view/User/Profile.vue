<template>
	<div class="user__show">
		<transition name="header_transition">
			<div class="user__header" v-if="isLoad">
				<h3 class="">Perfil: <strong>{{user.user_name}}</strong> </h3>
			</div>
		</transition>
		<div class="user__row">
			<div class="user__image">
	            <transition name="transition">
					<div class="user__box transition" v-if="isLoad">
						<img :src="`/img/users/${user.profile.image}`" v-if="user.profile.image">
						<img src="/img/users/default.png" v-else>
					</div>
   				</transition>
			</div>
			<div class="user__details">
	            <transition name="transition">
					<div class="user__details_inner transition" v-if="isLoad">
						<ul>
							<li>
								<span class="key text">Nombre:</span>
								<p class="result text">{{user.profile.name}} </p>
								<div class="clear"></div>
							</li>
							<li>
								<span class="key text">Apellido:</span>
								<p class="result text">{{user.profile.last_name}} </p>
								<div class="clear"></div>
							</li>
							<li>
								<span class="key text">Correo Electronico:</span>
								<p class="result text">{{user.email}} </p>
								<div class="clear"></div>
							</li>
							<li>
								<span class="key text">Sexo:</span>
								<p class="result text">{{user.profile.sex}} </p>
								<div class="clear"></div>
							</li>
							<li>
								<span class="key text">Direccion:</span>
								<p class="result text">{{user.profile.direction}} </p>
								<div class="clear"></div>
							</li>
							<li>
								<span class="key text">Telefono:</span>
								<p class="result text">{{user.profile.phone}} </p>
								<div class="clear"></div>
							</li>
							<li>
								<span class="key text">Fecha de Nacimiento:</span>
								<p class="result text">{{user.profile.birthdate}} </p>
								<div class="clear"></div>
							</li>
						</ul>
						<div class="user__btn" v-if="auth.api_token && auth.user_id === user.id">
							<router-link :to="`/users/${user.id}/edit`" class="btn btn__primary">
								Editar
							</router-link>
						</div>
					</div>
   				</transition>
			</div>
		</div>
		<div class="user__row">
			<div class="user__ingredients">
				<transition name="transition">
					<div class="user__box transition" v-if="isLoad">
					</div>
				</transition>

			</div>
			<div class="user__recipes">
				<transition name="transition">
					<div class="user__recipes_inner transition" v-if="isLoad">
						<h3 class="recipe__sub_title">
							Recetas
						</h3>
						<ul>
							<li v-for="recipe in user.recipes">
								<router-link :to="`/recipes/${recipe.id}`">
									<img :src="`/img/recipes/${recipe.image}`">
									<h3> {{recipe.name}} </h3>
								</router-link>
							</li>
							<div class="clear"></div>
						</ul>
					</div>
				</transition>
			</div>
		</div>
	</div>
</template>

<script type="text/javascript">
	import Vue from 'vue'
	import Flash from '../../helpers/flash.js'
	import { post, get } from '../../helpers/api.js'
	import { toMultipartedForm } from '../../helpers/form.js'
	import ImageUpload from '../../components/ImageUpload.vue'
	import Auth from '../../store/auth.js'


	export default {
		components: {
			ImageUpload
		},
		created() {
			get(`/api/users/${this.$route.params.id}`)
				.then((res) => {
					Vue.set(this.$data, 'user', res.data.user)
					this.isLoad = true
					console.log(res.data)
				})
		},
		data() {
			return {
				user: {
					profile: {}
					//ingredients: [],
					//directions: []
				},
				auth: Auth.state,
				error: {},
				isLoad: false,
			}
		}
	}
</script>