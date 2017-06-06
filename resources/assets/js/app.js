import './bootstrap';

import router from './routes';

import App from './components/App.vue';

/* validation componente */
import messages from 'vee-validate/dist/locale/es';
import VeeValidate, { Validator } from 'vee-validate';



const config = {
    locale: 'es'
};

Validator.addLocale(messages);

Vue.use(VeeValidate, config);
/* fin validacion componente */

/* notification conponent */
//https://github.com/chengxulvtu/cxlt-vue2-toastr
//Demo: https://chengxulvtu.github.io/toastr/index.html
import CxltToastr from 'cxlt-vue2-toastr'
var toastrConfigs = {
    position: 'top right',
    showDuration: 2000,
    hideDuration: 4000
}
Vue.use(CxltToastr, toastrConfigs)
/* fin notification conponent */

var app = new Vue({
    el: '#app',
    render: h => h(App),
    router
});
