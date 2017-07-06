<template>
	<div>
		<div class="search">
			<input type="" v-model="query" @keyup.enter="search">
			<button type="button" @click="search">
				Buscar
			</button>
		</div>
			<ul class="pagination">
				<li :class="pagination.current_page === 1 ? 'disabled' : '' ">
					<a href="" @click.prevent="paginate(1, (pagination.current_page === 1) ? false : true)">
						<<
					</a>
				</li>
				<li :class="pagination.current_page === 1 ? 'disabled' : '' ">
					<a href="" @click.prevent="paginate(pagination.current_page - 1, (pagination.current_page === 1) ? false : true)">
						<
					</a>
				</li>
				<li v-for="page in pagination.last_page" :class="pagination.current_page === page ? 'active' : ''">
					<a href="" @click.prevent="paginate(page, (pagination.current_page === page) ? false : true)">
						{{page}}
					</a>
				</li>
				<li :class="pagination.current_page === pagination.last_page ? 'disabled' : '' ">
					<a href="" @click.prevent="paginate(pagination.current_page + 1, (pagination.current_page === pagination.last_page) ? false : true)">
						>
					</a>
				</li>
				<li :class="pagination.current_page === pagination.last_page ? 'disabled' : '' ">
					<a href="" @click.prevent="paginate(pagination.last_page, (pagination.current_page === pagination.last_page) ? false : true)">
						>>
					</a>
				</li>
			</ul>
		<div class="recipe__list">
			<transition name="loading">
				<div class="loading absolute" v-if="isLoad"></div>
			</transition>

			<transition-group name="recipe_index" tag="ul">
				<li class="recipe__item" v-for="(recipe, index) in recipes" :key="recipe">
					<div class="recipe__inner">
						<router-link :to="`/recipes/${recipe.id}`">
							<img :src="`/img/recipes/${recipe.image}`" v-if="recipe.image">
							<p class="recipe__name">{{recipe.name}}</p>
						</router-link>
						<small class="recipe_user"><strong>Creado por</strong> <router-link :to="`/users/${recipe.user_id}`">{{recipe.user.user_name}}</router-link> </small>
					</div>
				</li>
			</transition-group>
		</div>

	</div>
</template>
<script type="text/javascript">
	// `
	import { get } from '../../helpers/api.js'


	export default {
		data() {
			return {
				recipes: [],
				page: (this.$route.params.page === undefined) ? 1 : this.$route.params.page,
				pagination: {},
				router: this.$route,
			    query: '',
			    seach: [],
			    isLoad: false
			}
		},
		created() {
			this.paginate(this.page, true);
		},
		methods: {
			paginate(page, state) {
				if (!this.isLoad) {
					if (this.$route.params.string) {
						var param_page  = (page !== 1) ? `&page=${page}` : '' ;
						var url = `/api/recipes/search?string=${this.$route.params.string}${param_page}`
					}else {
						var param_page  = (page !== 1) ? `?page=${page}` : '' ;
						var url = `/api/recipes${param_page}`
					}
					if (state) {
						this.isLoad = true
						get(url)
							.then((res) => {
								this.recipes = res.data.recipes.data
								this.pagination = res.data.recipes
								delete this.pagination.data
								if (this.$route.params.string) {
									param_page = (page !== 1) ? `/page/${page}` : ''
									this.$router.push(`/recipes/search/${this.$route.params.string}${param_page}`)
								}else {
									param_page = (page !== 1) ? `/page/${page}` : ''
									this.$router.push(`/recipes${param_page}`)
								}
								setTimeout( () => {
									this.isLoad = false
								}, 500)
							})
					}
				}
			},
			search() {
				if (!this.isLoad) {
					if (this.query.trim().length > 0) {
						this.isLoad = true
						get('/api/recipes/search?string='+ this.query)
							.then((res) => {
								this.recipes = res.data.recipes.data
								console.log(this.recipes)
								this.pagination = res.data.recipes
								delete this.pagination.data

								this.$router.push(`/recipes/search/${this.query}`)
								this.isLoad = false
							})
					}
				}
			},
			ejemplo(link) {

			}
		},
		computed: {
	   		computedList: function () {
		      var vm = this
		      return this.recipes.filter(function (item) {
		        return item.name.toLowerCase().indexOf(vm.query.toLowerCase()) !== -1
		      })
		    },
		}
	}
</script>
