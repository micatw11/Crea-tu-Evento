<template>
    <div>
        <bar v-if="auth.user.authenticated && showComponent"></bar>
        <barra-lateral v-if="auth.user.authenticated & showComponent"></barra-lateral>
        <div v-bind:class="classObject">
            <router-view @reloadComponents="reloadComponents()"></router-view>
        </div>
        <foo v-if="auth.user.authenticated"></foo>
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
    computed: {
        classObject: function () {
            return {
                'content-wrapper': auth.user.authenticated,
                'container': !auth.user.authenticated,
            }
        }
    },
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