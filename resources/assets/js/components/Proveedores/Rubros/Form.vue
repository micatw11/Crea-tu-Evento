<template>
<div>
    <form role="form">
        <div class="col-sm-12">
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

        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarSubcategoria}">
                <div class="col-sm-12">
                    <label class="control-label">Categoria</label><br>

                    <select 
                        name="categoria" 
                        @change="changeCategory()"
                        v-model="categoria_id" 
                        class="form-control" 
                        placeholder="Seleccione una categoria">
                        <option disabled value="">Seleccione una categoria</option>
                        <option v-for="option in categorias" v-bind:value="option.value">{{option.text}}</option>
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

        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('subcategoria')&&validarRubro}">
                <div class="col-sm-12">
                    <label class="control-label">Subcategoria</label><br>

                    <select 
                        name="subcategoria" 
                        @change="changeSubcategory()"
                        v-model="subcategoria_id" 
                        class="form-control" 
                        v-validate data-vv-rules="required">
                        <option disabled value="">Seleccione una subcategoria</option>
                        <option v-for="option in subcategorias" v-bind:value="option.value">{{option.text}}</option>
                    </select>

                    
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('subcategoria')&&validarRubro" class="help-block">{{ errors.first('subcategoria') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.subcategoria">
                        <div v-for="msj in errorsApi.subcategoria">
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
            subcategorias: [],
            subcategoria_id: '',
            categoria_id: '',
            categorias: []
        }
    },
    beforeMount(){
        this.getOptionsSubcategory();
        this.getOptionsCategory();
    },
    components: {
        vSelect
    },
    mounted() {
        this.$events.$on('validarFormRubro', () => this.validateBeforeSubmit());
    },
    created(){
        this.loadDefaultOptions()
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
        },
        //obtiene lista de categorias s
        getOptionsSubcategory: function() {
            this.$http.get('api/subcategoria/'
                ).then(response => {
                    let data = response.data.data
                    for (let subcategoria of data){
                        if(subcategoria.categoria_id == this.categoria_id)
                        this.subcategorias.push({ text: subcategoria.nombre, value: subcategoria.id });
                    }
                })
        },
        changeSubcategory: function(){
            this.rubro.subcategoria_id = this.subcategoria_id;
        },
        //obtiene lista de categorias s
        getOptionsCategory: function() {
            this.$http.get('api/categoria/'
                ).then(response => {
                    let data = response.data.data
                    for (let categoria of data){
                        this.categorias.push({ text: categoria.nombre, value: categoria.id });
                    }
                })
        },
        changeCategory: function(){
            //this.subcategoria.categoria_id = this.categoria_id;
            this.subcategorias = [];
            this.subcategoria_id = null;
            this.$http.get('api/categoria/' +this.categoria_id
                ).then(response => {
                    let data = response.data.data
                    for (let subcategoria of data.subcategorias){
                        this.subcategorias.push({ text: subcategoria.nombre, value: subcategoria.id });
                    }
                })
        },
        loadDefaultOptions: function(){
            this.categoria_id = this.rubro.subcategoria.categoria.id
            this.subcategoria_id = this.rubro.subcategoria.id
        }
    }
}
</script>