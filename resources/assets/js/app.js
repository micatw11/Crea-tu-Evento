import './bootstrap';

import router from './routes';

import App from './components/App.vue';

// validation
import messages from 'vee-validate/dist/locale/es';
import VeeValidate, { Validator } from 'vee-validate';

const config = {
    locale: 'es'
};

Validator.addLocale(messages);

Vue.use(VeeValidate, config);

var app = new Vue({
    el: '#app',
    render: h => h(App),
    router
});
