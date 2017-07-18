<template>
	<div>
		<carousel :options="options">

		</carousel>
	</div>
</template>
<script type="text/javascript">
	// `
	import { get } from '../../helpers/api.js'
	import carousel from '../../components/Carousel.vue'

	export default {
		components: {
			carousel
		},
		data() {
			return {
				recipes: {},
				options: {
					items: 'jose'
				}
			}
		},
		created() {
			get(`/api/recipes`)
				.then((res) => {
					this.recipes = res.data.recipes.data
				})
		},
		methods: {

		},
	}
</script>
