<template>
	<div class="recipe__show">
		<transition name="header_transition">
			<div class="recipe__header" v-if="isLoad">
				<h3 class="">{{action}} Recetas</h3>
				<div>
					<button class="btn btn__primary" @click="save" :disabled="isProcessing">Guardar</button>
					<button class="btn" @click="$router.back()" :disabled="isProcessing">Cancelar</button>

				</div>
			</div>
		</transition>

		<div class="recipe__row">
			<div class="recipe__image">
	            <transition name="transition">
					<div class="recipe__box transition" v-if="isLoad">
						<image-upload v-model="form.image" tipo="recipes"></image-upload>
			            <transition name="error__control__transition">
			            	<small class="error__control" v-if="error.image">
			            		{{error.image[0]}}
			            	</small>
	       				</transition>
					</div>
   				</transition>


			</div>
			<div class="recipe__details">
				<transition name="transition">
					<div class="recipe__details_inner transition" v-if="isLoad">
						<div class="form__group">
							<label>Nombre</label>
							<input type="text" class="form__control" v-model="form.name">
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error.name">
				            		{{error.name[0]}}
				            	</small>
	           				</transition>
						</div>
						<div class="form__group">
							<label>Descripcion</label>
							<textarea class="form__control" v-model="form.description"></textarea>
				            <transition name="error__control__transition">
				            	<small class="error__control" v-if="error.description">
				            		{{error.description[0]}}
				            	</small>
	           				</transition>
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
						<transition-group name="add_remove" tag="div">
							<div v-for="(ingredient, index) in form.ingredients" :key="ingredient" class="recipe__form">
								<input type="text" class="form__control" v-model="ingredient.name" :class="[error[`ingredients.${index}.name`] ? 'error__bg' : '']">
								<input type="text" class="form__contro form__qty" v-model="ingredient.qty" :class="[error[`ingredients.${index}.qty`] ? 'error__bg' : '']">
								<button class="btn btn__danger" @click="remove('ingredients', index)">&times;</button>
							</div>
						</transition-group>
						<button class="btn" @click="addIngredient">
							Agregar Ingrediente
						</button>
			            <transition name="error__control__transition">
			            	<small class="error__control" v-if="error.ingredients">
			            		{{error.ingredients[0]}}
			            	</small>
	       				</transition>
					</div>
				</transition>

			</div>
			<div class="recipe__directions">
				<transition name="transition">
					<div class="recipe__directions_inner transition" v-if="isLoad">
						<h3 class="recipe__sub_title">Direcciones</h3>
						<transition-group name="add_remove" tag="div">
							<div v-for="(direction, index) in form.directions" class="recipe__form" :key="direction">
								<textarea class="form__control" v-model="direction.description" :class="[error[`directions.${index}.description`] ? 'error__bg' : '']"></textarea>
								<button class="btn btn__danger" @click="remove('directions', index)">&times;</button>
							</div>
						</transition-group>

						<button class="btn" @click="addDirection">
							Agregar Direccion
						</button>
			            <transition name="error__control__transition">
			            	<small class="error__control" v-if="error.directions">
			            		{{error.directions[0]}}
			            	</small>
	       				</transition>
					</div>
				</transition>
			</div>
		</div>

	</div>
</template>

<script type="text/javascript">
	import Vue from 'vue'
	import Flash from '../../helpers/flash.js'
	import { get, post } from '../../helpers/api.js'
	import { toMultipartedForm } from '../../helpers/form.js'
	import ImageUpload from '../../components/ImageUpload.vue'

	export default {
		components: {
			ImageUpload
		},
		created() {
			if (this.$route.meta.mode === 'edit') {
				this.initializeURL = `/api/recipes/${this.$route.params.id}/edit`
				this.storeURL = `/api/recipes/${this.$route.params.id}?_method=PUT`
				this.action = 'Editar'
			}
			get(this.initializeURL)
				.then((res) => {
					Vue.set(this.$data, 'form', res.data.form)
					this.isLoad = true
				})
		},
		data() {
			return {
				form: {
					ingredients: [],
					directions: []
				},
				error: {},
				isProcessing: false,
				typeImg: 'recipes',
				isLoad: false,
				initializeURL: '/api/recipes/create',
				storeURL: `/api/recipes`,
				action: 'Crear'
			}
		},
		methods: {
			save() {
				this.isProcessing = true;
				const form = toMultipartedForm(this.form, this.$route.meta.mode)
				//console.log(this.form)
				//console.log(form)
				post(this.storeURL, form)
					.then((res) => {
						if (res.data.saved) {
							Flash.setSuccess(res.data.message)
							this.$router.push(`/recipes/${res.data.id}`)
							this.isProcessing = false
						}
					})
					.catch((err) => {
						if (err.response.status === 422) {
                            this.error = err.response.data
                        }
						this.isProcessing = false
					})
			},
			addDirection() {
				this.form.directions.push({ description: '' })
			},
			addIngredient() {
				this.form.ingredients.push({
					name: '',
					qty: ''
				})
			},
			remove(type, index) {
				//console.log(this.form);
				if (type == 'ingredients') {
					this.form.ingredients.splice(index, 1)
				}else if (type == 'directions') {
					this.form.directions.splice(index, 1)
				}
			}
		}
	}
</script>