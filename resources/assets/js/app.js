import './bootstrap';

import router from './routes';

import App from './components/App.vue';

new Vue({
    el: '#app',
    render: h => h(App),
    router
});
