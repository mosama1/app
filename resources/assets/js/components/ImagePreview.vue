<template>
	<div class="image__preview">
		<img :src="image">
		<button class="btn btn__danger image__close" @click="$emit('close')">
			&times;
		</button>
	</div>
</template>

<script type="text/javascript">
	export default {
		props: {
			preview: {
				type: [String, File],
				default: null
			},
			tipo: [String]
		},
		data() {
			return {
				image: null
			}
		},
		 created() {
		 	this.setPreview()
		 	//console.log(this.tipo)
		 },
		 watch: {
		 	'preview': 'setPreview'
		 },
		 methods: {
		 	setPreview() {
		 		//console.log('asd')
		 		if (this.preview instanceof File) {
		 			const fileReader = new FileReader()
		 			fileReader.onload = (event) => {
		 				this.image = event.target.result
		 			}
		 			fileReader.readAsDataURL(this.preview)
		 		}else if (typeof this.preview === 'string') {
		 			this.image = `/img/${this.tipo}/${this.preview}`
		 		}else {
		 			this.image = null
		 		}

		 	}
		 }
	}
</script>