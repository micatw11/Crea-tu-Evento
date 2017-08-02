<template>
<div>
    <form role="form">
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarSubcategoria}">
                <div class="col-sm-12">
                    <label for="inputSubcategoria" class="control-label">Nombre</label><br>
                       <input name="nombre" v-validate="'required'" type="text" v-model="subcategoria.nombre" 
                        class="form-control" placeholder="Nombre">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarSubcategoria" class="help-block">{{ errors.first('nombre') }}</span>
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
            subcategoria: {
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
            validarSubcategoria: false
        }
    },
    components: {
        vSelect
    },
   mounted() {
        this.$events.$on('validarFormSubcategoria', () => this.validateBeforeSubmit());
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
         this.$validator.validateAll().then(() => {
                    this.validarSubcategoria = false; 
                    if (this.nuevo){
                        this.$emit('validadoNewSubcategoria')
                    }else{
                        this.$emit('validadoEditSubcategoria')
                    }
                }).catch(() => {
                    this.validarSubcategoria = true;
                });
        }
    }
}
</script>