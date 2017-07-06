<template>
    <form class="form" @submit.prevent="login">
        <h1 class="form__title">Iniciar Sesion</h1>
        <div class="form__group">
            <label>Correo Electronico</label>
            <input type="text" class="form__control" v-model="form.user_name">
            <transition name="error__control__transition">
                <small class="error__control" v-if="error.user_name">{{error.user_name[0]}}</small>
            </transition>
        </div>
        <div class="form__group">
            <label>Contraseña</label>
            <input type="password" class="form__control" v-model="form.password">
            <transition name="error__control__transition">
                <small class="error__control" v-if="error.password">{{error.password[0]}}</small>
            </transition>
        </div>
        <div class="form__group">
            <button :disabled="isProcessing" class="btn btn__primary">Iniciar Sesion</button>
        </div>
    </form>
</template>

<script type="text/javascript">
    import Flash from '../../helpers/flash.js'
    import Auth from '../../store/auth.js'
    import { post } from '../../helpers/api.js'
    export default {
        data() {
            return {
                form: {
                    user_name: '',
                    password: ''
                },
                error: {},
                isProcessing: false
            }
        },
        methods: {
            login() {
                this.isProcessing = true;
                this.error = {}
                post('/api/login', this.form)
                    .then((res) => {
                        if (res.data.authenticated) {
                            Auth.set(res.data.api_token, res.data.user_id, res.data.profile)
                            Flash.setSuccess('Bienvenido!!')
                            this.$router.push('/')
                        }
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        if (err.response.status === 422) {
                            this.error = err.response.data
                        }
                        this.isProcessing = false
                    })
            }
        }
    }
</script>
