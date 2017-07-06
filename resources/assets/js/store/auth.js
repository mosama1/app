import { post } from '../helpers/api.js'

export default {
    state: {
        api_token: null,
        user_id: null,
        name: null,
        image: null,
    },
    inicialize() {
        this.state.api_token = localStorage.getItem('api_token')
        this.state.user_id = parseInt(localStorage.getItem('user_id'))
        this.state.name = localStorage.getItem('name')
        this.state.image = localStorage.getItem('image')
    },
    set(api_token, user_id, profile) {
        localStorage.setItem('api_token', api_token)
        localStorage.setItem('user_id', user_id)
        localStorage.setItem('name', profile.name)
        localStorage.setItem('image', profile.image)
        this.inicialize();
    },
    remove() {
        localStorage.removeItem('api_token')
        localStorage.removeItem('user_id');
        localStorage.removeItem('name');
        localStorage.removeItem('image');
        this.inicialize();
    },
    setImage(image) {
        localStorage.setItem('image', image)
        this.inicialize();
    },
}
