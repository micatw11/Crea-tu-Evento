<template>
    <div>
        <bar v-if="auth.user.authenticated && showComponent"></bar>
        <barra-lateral v-if="auth.user.authenticated & showComponent"></barra-lateral>
        <router-view @reloadComponents="reloadComponents()"></router-view>
    </div>
</template>
<script>
import auth from './../auth.js'
import Bar from './Layouts/Barra.vue'
import BarraLateral from './Layouts/BarraLateral.vue'
import Foo from './Layouts/Footer.vue'

export default {
    data() {
        return {
            auth: auth,
            showComponent: true
        }
    },
    components: {Bar, BarraLateral, Foo },

    methods: {
        reloadComponents(){
            this.showComponent = false;
            setTimeout(this.showComponentes, 1);
            
        },
        showComponentes(){
            this.showComponent = true;
        }
    }
}
</script>