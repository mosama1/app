<template>
    <div>
        {{options.items}}
    </div>
</template>

<script type="text/javascript">
	export default {
		props: {
			options: {
				items: {
                    type: Number,
    				default: null

                },
			}
		},
		data() {
			return {
			}
		},
		 created() {
		 },
		 methods: {
		 }
	}
</script>
