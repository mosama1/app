<template>
    <form class="form" @submit.prevent="register">
        <h1 class="form__title">Registrarse</h1>
        <div class="form__group">
            <label>Nombre de usuario</label>
            <input type="text" class="form__control" v-model="form.user_name">
            <transition name="error__control__transition">
                <small class="error__control" v-if="error.user_name">{{error.user_name[0]}}</small>
            </transition>
        </div>
        <div class="form__group">
            <label>Correo Electronico</label>
            <input type="text" class="form__control" v-model="form.email">
            <transition name="error__control__transition">
                 <small class="error__control" v-if="error.email">{{error.email[0]}}</small>
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
            <label>Confirmar Contraseña</label>
            <input type="password" class="form__control" v-model="form.password_confirmation">
        </div>
        <div class="form__group">
            <button :disabled="isProcessing" class="btn btn__primary">Registrarse</button>
        </div>
    </form>
</template>

<script type="text/javascript">
    import Flash from '../../helpers/flash.js'
    import { post } from '../../helpers/api.js'
    export default {
        data() {
            return {
                form: {
                    user_name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                error: {},
                isProcessing: false
            }
        },
        methods: {
            register() {
                this.isProcessing = true;
                this.error = {}
                post('/api/register', this.form)
                    .then((res) => {
                        if (res.data.registered) {
                            Flash.setSuccess('Se ha registrado exitosamente')
                            this.$router.push('/login')
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
