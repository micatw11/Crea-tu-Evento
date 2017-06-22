<template>
    <div class="content-wrapper">
     <path-content :titleContent="titleContent"></path-content>
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
                        <!--<div class="box-footer clearfix">
                                <button class="btn btn-default" >
                                    <i class="glyphicon glyphicon-chevron-left"></i>
                                    Atras
                                </button>
                                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                                    Guargar
                                </button>
                        </div>-->
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
            titleContent: 'Nuevo Proveedor',
            
        }
    },
    components: {
        FormProv,PathContent
    },
}
</script>
