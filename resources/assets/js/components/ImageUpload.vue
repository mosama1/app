<template>
	<div class="image">
		<image-preview :preview="value" :tipo="tipo" @close="$emit('input', null)" v-if="value">
			
		</image-preview>
		<div class="image__upload" v-else>
			<img src="/img/icons/subir_imagen.png">
			<input type="file" accept="images/*" @change="upload">
		</div>
	</div>
</template>

<script type="text/javascript">
	import ImagePreview from './ImagePreview.vue'

	export default {
		components: {
			ImagePreview
		},
		props: {
			value: {
				type: [String, File],
				default: null
			},
			tipo: [String]
		},
		methods: {
			upload(e) {
				const files = e.target.files
				//console.log(files.length)
				if (files && files.length > 0) {
					this.$emit('input', files[0])
				}

			}
		}
	}
</script>