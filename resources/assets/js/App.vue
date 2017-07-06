<template>
    <div id="app">
        <div class="navbar">
            <div class="container">
                <div class="navbar__brand">
                    <router-link to="/">Recipe Box</router-link>
                </div>
                <ul class="navbar__list">
                    <li class="navbar__item">
                        <router-link to="/users">Usuarios</router-link>
                    </li>
                   <li class="navbar__item">
                        <router-link to="/recipes">Recetas</router-link>
                    </li>
                    <li class="navbar__item" v-if="!check">
                        <router-link to="/login">Iniciar Sesion</router-link>
                    </li>
                    <li class="navbar__item" v-if="!check">
                        <router-link to="/register">Registrarse</router-link>
                    </li>
                    <li class="navbar__item" v-if="check">
                        <router-link :to="`/users/${auth.user_id}`">Perfil</router-link>
                    </li>
                    <li class="navbar__item" v-if="check">
                        <router-link to="/recipes/create">Crear Receta</router-link>
                    </li>
                    <li class="navbar__item" v-if="check">
                        <a @click.stop="logout">Salir</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <transition name="flash">
                <div class="Flash flash__error" v-if="flash.error">
                    {{flash.error}}
                </div>
            </transition>
            <transition name="flash">
                <div class="flash flash__success" v-if="flash.success">
                    {{flash.success}}
                </div>
            </transition>
            <transition name="transition_router" mode="out-in">
                <router-view></router-view>
            </transition>

        </div>

    </div>
</template>
<script type="text/javascript">
                            //<img :src="`/img/users/${auth.user.profile.image}`">

import Flash from './helpers/flash.js'
import Auth from './store/auth'
import { post } from './helpers/api.js'
import router from './router/index.js'
    export default {
        created() {
            Auth.inicialize()
        },
        data() {
            return {
                flash: Flash.state,
                auth: Auth.state,
            }
        },
        computed: {
            check() {
                if (this.auth.api_token && this.auth.user_id) {
                    return true
                }
                return false
            }
        },
        methods: {
            logout() {
                post('/api/logout')
                    .then((res) => {
                        if (res.data.logged_out) {
                            Auth.remove()
                            Flash.setSuccess('Desconectado !!')
                            this.$router.push('/login')
                        }
                    })
            }
        },      

    }

</script>
