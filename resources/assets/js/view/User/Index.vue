<template>
	<div class="user__list">
		<transition-group name="user_index" tag="ul">
			<li class="user__item" v-for="user in users" :key="user">
				<router-link class="user__inner" :to="`/users/${user.id}`">
					<img :src="`/img/users/${user.profile.image}`" v-if="user.profile.image">
					<div>
						<p class="user__name"><strong>Usuario: </strong>{{user.user_name}}</p>
						<p class="user__name"><strong>Nombre: </strong>{{user.profile.name}} {{user.profile.last_name}}</p>

					</div>
				</router-link>
			</li>
		</transition-group>
		
	</div>
</template>
<script type="text/javascript">
	// `
	import { get } from '../../helpers/api.js'

	export default {
		data() {
			return {
				users: []
			}
		},
		created() {
			get('/api/users')
				.then((res) => {
					this.users = res.data.users
				})
		}
	}
</script>