<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h4>Alta Proveedor</h4>
                        </div>
                        <div class="box-body table-responsive no-padding">
                        	<form-prov></form-prov>
                        </div>
                    </div>
                </div>
            </div>    
        </section>
    </div> 
</template>


<script>
import auth from '../../auth.js';
import vSelect from "vue-select";
import FormProv from './Form.vue';
import PathContent from '../Layouts/Path.vue';

export default {
    data() {
        return {
            titlePath: 'Nuevo Proveedor',
            listaPath: [{route: '/', name: 'Home'},{route: '/proveedor/new', name: 'Nuevo Proveedor'}]
        }
    },
    components: {
        FormProv,PathContent
    },
    mounted() {
            this.$events.fire('changePath', this.listaPath, this.titlePath);
        }
}
</script>
