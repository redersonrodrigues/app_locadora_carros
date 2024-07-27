import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

/* Interceptar os requests da aplicação */
axios.interceptors.request.use(
    config => {
        // definir para todas as requisições os parâmetros de accept e authorization
        config.headers.Accept = 'application/json'

        /* Recuperando o token de autorização dos cookies */
        let token = document.cookie.split(';').find(indice => {
            return indice.includes('token=')
        })

        token = token.split('=')[1]
        token = 'Bearer ' + token

        config.headers.Authorization = token

        console.log('Interceptando o request antes do envio', config)
        return config
    },
    error => {
        console.log('Erro na requisição: ', error)
        return Promise.reject(error)
    })

/* Interceptar os responses da aplicação */
axios.interceptors.response.use(
    response => {
        console.log('Interceptando a resposta antes da aplicação', response)
        return response
    },
    error => {
        console.log('Erro na resposta: ', error.response)
        if (error.response.status == 401 && error.response.data.message == 'Unauthenticated.') {
            console.log('Fazer uma nova requisição para a rota refresh')

            axios.post('http://localhost:8000/api/refresh')
                .then(response => {
                    console.log('Refresh com sucesso: ', response);

                    document.cookie = 'token='+response.data.token
                    console.log('Token atualizado', response.data.token)
                    window.location.reload()
                })
        }


        return Promise.reject(error)
    }
)