<template>
	<div>
        <div class="col-sm-4">
            <button type="button" class="btn btn-block btn-primary btn-sm" @click="showModificar = true">Nuevo Proveedor</button>
        </div>

        <!-- Modal Modificar-->
        <div id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
            <div class="modal-dialog">
            	<div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()">&times;</button>
                        <h4 class="modal-title">Alta Proveedor</h4>
                    </div>
                    <div class="modal-body">
		            	<form-prov :proveedor="proveedor"></form-prov>
		            </div>
		            <div class="text-center mrg-top-1em">
		                <button class="btn btn-default" >
		                    <i class="glyphicon glyphicon-chevron-left"></i>
		                    Atras
		                </button>
		            </div>
                </div>
            </div>
        </div>
    </div> 
</template>


<script>
import auth from '../../auth.js';
import vSelect from "vue-select";
import FormProv from './Form.vue';

export default {
    data() {
        return {
            validar: false,
            showModificar: false,
            proveedor: { 
            },
            error: false,
            errorsApi: []
        }
    },
    components: {
        FormProv
    },
    ready: function() {
        console.log('Vue ready...');
    },
    methods: {
        closeModal: function(){
            this.errorsApi = [];
            //this.getUserPerfil();
            //this.setDefaultLocalidad();
            this.validar = false;
            this.showModificar = false;
        },
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(() => {
                    this.sendForm();
                }).catch(() => {
                    this.validar = true;
                });
        },
    }
}
</script>
