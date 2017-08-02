<template>
<div>
    <form role="form">
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarRubro}">
                <div class="col-sm-12">
                    <label for="inputRubro" class="control-label">Nombre</label><br>
                       <input name="nombre" v-validate="'required'" type="text" v-model="rubro.nombre" 
                        class="form-control" placeholder="Nombre">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarRubro" class="help-block">{{ errors.first('nombre') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.nombre">
                        <div v-for="msj in errorsApi.nombre">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form> 
</div>
</template>



<script>
import auth from '../../../auth.js';
import vSelect from "vue-select";

export default {
    props: {
            rubro: {
                type: Object,
                required: true
            },
            nuevo: {
                type: Boolean,
                required: true
            },
            errorsApi: {
                type: Object,
                required: true
            }
    },
    data() {
        return {
            validarRubro: false,
            error: false
        }
    },
    components: {
        vSelect
    },
   mounted() {
        this.$events.$on('validarFormRubro', () =>this.validateBeforeSubmit());
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
         this.$validator.validateAll().then(() => {
                    this.validarRubro = false; 
                    if (this.nuevo){
                        this.$emit('validadoNewRubro')
                    }else{
                        this.$emit('validadoEditRubro')
                    }
                }).catch(() => {
                    this.validarRubro = true;
                });
        }
    }
}
</script>