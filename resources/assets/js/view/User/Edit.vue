<template>
	<div class="user__show">
		<transition name="header_transition">
			<div class="user__header" v-if="isLoad">
				<h3 class="">Perfil: <strong>{{form.user_name}}</strong> </h3>
				<div>
					<button class="btn btn__primary" @click="save" :disabled="isProcessing">Guardar</button>
					<button class="btn" @click="$router.back()" :disabled="isProcessing">Cancelar</button>
				</div>
			</div>
		</transition>

		<div class="user__row">
			<div class="user__image">
	            <transition name="transition">
					<div class="user__box transition" v-if="isLoad">
						<image-upload v-model="form.image" tipo="users"></image-upload>
			            <transition name="error__control__transition">
			            	<small class="error__control" v-if="error.image">
			            		{{error.image[0]}}
			            	</small>
	       				</transition>
					</div>
   				</transition>
			</div>
			<div class="user__details">
				<transition name="transition">
					<div class="user__details_inner transition" v-if="isLoad">
						<div class="form__group">
							<label>Nombre</label>
							<input type="text" class="form__control" v-model="form.profile.name">
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error[`profile.name`]">
				            		{{error[`profile.name`][0]}}
				            	</small>
	           				</transition>
						</div>
						<div class="form__group">
							<label>Apellido</label>
							<input type="text" class="form__control" v-model="form.profile.last_name">
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error[`profile.last_name`]">
				            		{{error[`profile.last_name`][0]}}
				            	</small>
	           				</transition>
						</div>
						<div class="form__group">
							<label>Sexo</label>
							<input type="text" class="form__control" v-model="form.profile.sex">
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error[`profile.sex`]">
				            		{{error[`profile.sex`][0]}}
				            	</small>
	           				</transition>
						</div>
						<div class="form__group">
							<label>Direccion</label>
							<textarea class="form__control" v-model="form.profile.direction"></textarea>
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error[`profile.direction`]">
				            		{{error[`profile.direction`][0]}}
				            	</small>
	           				</transition>
						</div>
						<div class="form__group">
							<label>Telefono</label>
							<input type="text" class="form__control" v-model="form.profile.phone">
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error[`profile.phone`]">
				            		{{error[`profile.phone`][0]}}
				            	</small>
	           				</transition>
						</div>
						<div class="form__group">
							<label>Fecha de nacimiento</label>
							<input type="date" class="form__control" v-model="form.profile.birthdate">
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error[`profile.birthdate`]">
				            		{{error[`profile.birthdate`][0]}}
				            	</small>
	           				</transition>
						</div>
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
	import Auth from '../../store/auth'


	export default {
		components: {
			ImageUpload
		},
		created() {
			get(`/api/users/${this.$route.params.id}/edit`)
				.then((res) => {
					Vue.set(this.$data, 'form', res.data.form)
					this.isLoad = true
				})
		},
		data() {
			return {
				form: {
					profile: {}
				},
				error: {
					profile: {}
				},
				isProcessing: false,
				isLoad: false,
			}
		},
		methods: {
			save() {
				this.isProcessing = true;
				const form = toMultipartedForm(this.form, 'edit')
				post(`/api/users/${this.$route.params.id}?_method=PUT`, form)
					.then((res) => {
						if (res.data.saved) {
							Flash.setSuccess(res.data.message)
							if (Auth.state.api_token && Auth.state.user_id === parseInt(this.$route.params.id)) {
								Auth.setImage(res.data.image)
							}
							this.$router.push(`/users/${this.$route.params.id}`)
							this.isProcessing = false
						}
					})
					.catch((err) => {
						if (err.response.status === 422) {
                            this.error = err.response.data
                            //console.log(this.error)
                        }
						this.isProcessing = false
					})
			},
		}
	}
</script>
