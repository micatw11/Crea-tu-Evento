<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h4>Crear Rubro</h4>
                        </div>
                        <div class="box-body table-responsive no-padding">
                        	<form-rubro></form-rubro>
                        </div>
                    </div>
                </div>
            </div>    
        </section>
    </div> 
</template>

<script>
import auth from '../../auth.js';
import FormRubro from './FormRubro.vue';

export default {
    data() {
        return {
        }
    },
    components: {
        FormRubro
    }
}
</script>
