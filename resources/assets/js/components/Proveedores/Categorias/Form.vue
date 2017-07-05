<template>
<div>
    <form role="form">
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('tipo_rubro')&&validarCategoria}">
                <div class="col-sm-12">
                    <label for="inputCategoria" class="control-label">Nombre</label><br>
                       <input name="categoria" v-validate="'required'" type="text" v-model="categoria.nombre" value="categoria" class="form-control" placeholder="Nombre Categoria">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarCategoria" class="help-block">{{ errors.first('nombre') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.nombre">
                        <div v-for="msj in errorsApi.nombre">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('descripcion')&&validarCategoria}">
                <div class="col-sm-12">
                    <label for="inputDescripcion" class="control-label">Descripcion</label><br>
                     <input name="descripcion" v-validate="'max:15'" type="text" v-model="categoria.descripcion" value="categoria" class="form-control" placeholder="Categoria">
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('descripcion')&&validarCategoria" class="help-block">{{ errors.first('descripcion') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.descripcion">
                        <div v-for="msj in errorsApi.descripcion">
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
            categoria: {
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
            localidades: [],
            validarCategoria: false,
            error: false
        }
    },
    components: {
        vSelect
    },
   mounted() {
        this.$events.$on('validarFormCategoria', () =>this.validateBeforeSubmit());
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
         this.$validator.validateAll().then(() => {
                    this.validarCategoria = false; 
                    if (this.nuevo){
                        this.$emit('validado')
                    }else{
                        this.$emit('validadoEdit')
                    }
                }).catch(() => {
                    this.validarCategoria = true;
                });
        }
    }
}
</script>