/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('vue2-datepicker', require('./components/DatePicker.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

if (document.querySelector('#app')) {
    const app = new Vue({
        el: '#app',
    });
}

if (document.querySelector('#app1')) {
    const app1 = new Vue({
        el: '#app1',
        data: {
            // serviciul pentru care este programarea
            serviciu: ((typeof serviciu !== 'undefined') ? serviciu : ''),

            // data_initiala si ora_nitiala = necesare pentru returnarea cu axios a orelor disponibile pe ziua respectiva, inclusiv cea salvata la programarea curenta
            data_initiala: ((typeof dataVecheInitiala !== 'undefined') ? dataVecheInitiala : ''),
            ora_initiala: ((typeof oraVecheInitiala !== 'undefined') ? oraVecheInitiala : ''),

            // data programarii = se incarca initial cu data programarii, dar apoi se tot schimba in functie de ce alege clientul
            data: ((typeof dataVeche !== 'undefined') ? dataVeche : ''),

            // ora programarii = se incarca initial cu ora programarii, dar apoi se tot schimba in functie de ce alege clientul din lista
            ora: ((typeof oraVeche !== 'undefined') ? oraVeche : ''),

            // se incarca prin axios orele disponibile din data aleasa
            ore:'',
        },
        watch: {
            data: function () {
                this.getOre();
            },
        },
        created: function () {
            this.getOre()
        },
        methods: {
            getOre: function () {
                if (this.data) {
                    axios.get('/evidenta-persoanelor/programari/axios', {
                        params: {
                            request: 'ore',
                            serviciu: this.serviciu,
                            data: this.data,
                            data_initiala: this.data_initiala,
                            ora_initiala: this.ora_initiala
                        }
                    })
                        .then(function (response) {
                            app1.ore = response.data.raspuns;
                        });
                } else {
                    this.ore = '';
                }
            },
            dataProgramareTrimisa(data) {
                this.data = data;
            },
        }
    });
}
