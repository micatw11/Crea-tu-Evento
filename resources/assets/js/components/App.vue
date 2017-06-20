<template>
    <div>
        <bar v-if="auth.user.authenticated && showComponent"></bar>
        <barra-lateral v-if="auth.user.authenticated & showComponent"></barra-lateral>
        <div v-bind:class="classWrapper">
            <path-content 
                v-if="auth.user.authenticated"
                :listPath="listPath"
                :titlePath="titlePath">
            </path-content>
            <router-view 
                @reloadComponents="reloadComponents()" 
                :titlePath="titlePath" 
                :listPath="listPath">
            </router-view>
        </div>
    </div>
</template>
<script>
import auth from './../auth.js'
import Bar from './Layouts/Barra.vue'
import BarraLateral from './Layouts/BarraLateral.vue'
import Foo from './Layouts/Footer.vue'
import PathContent from './Layouts/Path.vue'

export default {
    data() {
        return {
            auth: auth,
            showComponent: true,
            listPath: [],
            titlePath: ''
        }
    },
    computed: {
        classWrapper: function () {
            return {
                'content-wrapper': this.auth.user.authenticated,
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