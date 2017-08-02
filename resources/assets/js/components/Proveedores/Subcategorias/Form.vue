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
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarSubcategoria}">
                <div class="col-sm-12">
                    <label class="control-label">Categoria</label><br>
                    <label class="control-label">Crear Categoria</label> 
                     <input type="checkbox" name="newCategoria" v-model="newCategoria">{{ formatNewCategory() }}
                    <template  v-if="!newCategoria">
                        <v-select
                            :debounce="250"
                            v-validate="'required'" 
                            data-vv-name="categoria"
                            v-model="categoriaSelect"
                            :on-search="getOptions" 
                            :options="categorias"
                            placeholder="Seleccione una categoria">
                        </v-select>
                    </template>
                    <template v-else>
                        <input type="text" name="categoria" placeholder="Nombre">
                    </template>
                    
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
            validarSubcategoria: false,
            crearCategoria: false,
            categorias: [],
            categoriaSelect: null,
            newCategoria: false
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
                    this.subcategoria.categoria_id = this.categoriaSelect.value
                    if (this.nuevo){
                        this.$emit('validadoNewSubcategoria')
                    }else{
                        this.$emit('validadoEditSubcategoria')
                    }
                }).catch(() => {
                    this.validarSubcategoria = true;
                });
        },
        //obtiene lista de categorias segun correponda
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/categoria/search/?q='+ search
                ).then(response => {
                    this.categorias = response.data
                    loading(false)
                })
        },
        formatNewCategory(){
            return this.newCategoria ? 'Si' : 'No';
        }
    }
}
</script>