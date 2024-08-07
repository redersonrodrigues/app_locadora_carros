/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import './bootstrap';

/** Instalando e configurando o vuex */
import { createApp } from 'vue';
import { createStore } from 'vuex';

// Create a new store instance.
const store = createStore({
    state() {
        return {
            item: {},
            transacao: {status: '', mensagem:'', dados: ''}
        }
    }
})
/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */
const app = createApp({});

// Registrar a função global de formatação de data e tempo (criação de filtro global)
app.config.globalProperties.$formataDataTempoGlobal = function (d) {
    if (!d) return '';
    d = d.split('T');

    let data = d[0];
    let tempo = d[1];

    // Formatando a data
    data = data.split('-');
    data = data[2] + '/' + data[1] + '/' + data[0];

    // Formatando o tempo
    tempo = tempo.split('.');
    tempo = tempo[0];

    return data + ' ' + tempo;
};





import ExampleComponent from './components/ExampleComponent.vue';
//  created  of the component
import LoginComponent from './components/LoginComponent.vue';
import Home from './components/Home.vue';
import Marcas from './components/Marcas.vue';
import InputContainer from './components/InputContainer.vue';
import Table from './components/Table.vue';
import Card from './components/Card.vue';
import Modal from './components/Modal.vue';
import Alert from './components/Alert.vue';
import Paginate from './components/Paginate.vue';




// register of the components created for me
app.component('example-component', ExampleComponent);
app.component('login-component', LoginComponent);
app.component('home-component', Home);
app.component('marcas-component',Marcas);
app.component('input-container-component', InputContainer);
app.component('table-component', Table);
app.component('card-component',Card);
app.component('modal-component',Modal);
app.component('alert-component',Alert);
app.component('paginate-component',Paginate);

// Use the store instance
app.use(store);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });
/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */
app.mount('#app');