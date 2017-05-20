<template>
    <div>
        <bar></bar>
            <div v-bind:class="classObject">
                <barra-lateral v-if="auth.user.authenticated"></barra-lateral>
                <section class="section">
                    <div class="container">
                        <router-view></router-view>
                    </div>
                </section>
            </div>
        <footer></footer>
    </div>
</template>
<script>
import auth from '../auth.js'
import Bar from './Layouts/Barra.vue'
import BarraLateral from './Layouts/BarraLateral.vue'
import Footer from './Layouts/Footer.vue'

export default {
    data() {
        return {
            auth: auth
        }
    },
    components: {Bar, BarraLateral, Footer },
    computed: {
        classObject: function () {
            return {
            'content-wrapper': auth.user.authenticated,
            'container': !auth.user.authenticated,
            }
        }
    }

}
</script>