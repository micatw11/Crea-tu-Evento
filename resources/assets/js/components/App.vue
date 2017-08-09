<template>
    <div v-bind:class="classWrapper">
        <bar v-if="auth.user.authenticated && showComponent"></bar>
        <barra-lateral v-if="auth.user.authenticated & showComponent"></barra-lateral>
        <div v-bind:class="classContentWrapper">
            <path-content 
                v-if="auth.user.authenticated">
            </path-content>

            <router-view transition="fade"></router-view>
        </div>
    </div>
</template>
<script>
import auth from './../auth.js'
import routes from './../routes.js'
import Bar from './Layouts/Barra.vue'
import BarraLateral from './Layouts/BarraLateral.vue'
import Foo from './Layouts/Footer.vue'
import PathContent from './Layouts/Path.vue'

export default {
    data() {
        return {
            auth: auth,
            routes: routes,
            showComponent: true
        }
    },
    mounted() {
        this.$events.listen('reloadComponents', this.reloadComponents);
        $(this).ajaxStart(function() { Pace.restart(); });
    },
    computed: {
        classContentWrapper: function () {
            return {
                'content-wrapper': this.auth.user.authenticated,
                '': !this.auth.user.authenticated,
            }
        },
        classWrapper: function () {
            return {
                'wrapper': this.auth.user.authenticated,
                '': !this.auth.user.authenticated,
            }
        }
    },
    components: {Bar, BarraLateral, Foo, PathContent },
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
<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .7s
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0
}
</style>