<template>
<div>
    <form role="form">
        <div class="col-sm-12">
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
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarSubcategoria}">
                <div class="col-sm-12">
                    <label class="control-label">Categoria</label><br>

                    <select 
                        nombre="categoria" 
                        @change="changeCategory()"
                        v-model="categoria_id" 
                        class="form-control" 
                        placeholder="Seleccione una categoria">
                        <option 
                            v-for="option in categorias" 
                            v-bind:value="option.value" 
                            v-bind:selected="option.value == subcategoria.categoria_id">
                            {{option.text}}
                        </option>
                    </select>

                    
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('categoria')&&validarSubcategoria" class="help-block">{{ errors.first('categoria') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.categoria">
                        <div v-for="msj in errorsApi.categoria">
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

export default {
    props: {
            categorias:{
                required: true
            },
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
            validarSubcategoria: false,
            categoria_id: null
        }
    },
    mounted() {
        this.$events.$on('validarFormSubcategoria', () => this.validateBeforeSubmit());
        //this.loadDefaultOption();  
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
        },
        changeCategory: function(){
            this.subcategoria.categoria_id = this.categoria_id;
        }
    }
}
</script>