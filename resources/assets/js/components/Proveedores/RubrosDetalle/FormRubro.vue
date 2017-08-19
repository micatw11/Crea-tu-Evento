<template>
<div>
    <form role="form">
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validarRubro}">
            <div class="col-sm-12">
                <label for="inputCategoria" class="control-label">Categoria</label><br>
                <select
                    class="form-control"
                    name='categoria'
                    v-model="categoria_id"
                    @change="cambiarCategoria()"
                    v-validate="'required'"
                    placeholder="Seleccione la Categoria" >
                    <option disabled value="">Seleccione una categoria</option>
                    <option 
                        v-for="option in optionsCategorias" 
                        v-bind:value="option.value">
                        {{ option.text }}
                    </option>

                </select>
                <!-- validacion vee-validation -->
                <span v-show="errors.has('categoria')&&validarRubro" class="help-block">{{ errors.first('categoria') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.categoria">
                    <div v-for="msj in errorsApi.categoria">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('subcategoria')&&validarRubro}">
            <div v-if="categoria_id != null" class="col-sm-12">
                <label for="inputSubcategoria" class="control-label">Subcategoria</label><br>
                <select
                    class="form-control"
                    name='subcategoria'
                    v-model="subcategoria_id"
                    @change="cambiarSubcategoria()"
                    v-validate="'required'"
                    placeholder="Seleccione una subCategoria" 
                    v-bind:disabled="categoria_id == '' || optionsSubcategorias.length == 0">
                    <option disabled value="">Seleccione una Subcategoria</option>
                    <option 
                        v-for="option in optionsSubcategorias" 
                        v-bind:value="option.value">
                        {{ option.text }}
                    </option>

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

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubro')&&validarRubro}">
            <div v-if="subcategoria_id != null" class="col-sm-12">
                <label for="inputRubro" class="control-label">Denominación</label><br>
                <select
                    class="form-control"
                    v-model="rubro_id"
                    name='rubro'
                    @change="cambiarRubro()"
                    v-bind:disabled="subcategoria_id == '' || optionsRubros.length == 0"
                    v-validate="'required'"
                    placeholder="Seleccione un Rubro" >
                    <option disabled value="">Seleccione un Rubro</option>
                    <option 
                        v-for="option in optionsRubros" 
                        v-bind:value="option.value">
                        {{ option.text }}
                    </option>

                </select>
                <!-- validacion vee-validation -->
                <span v-show="errors.has('rubro')&&validarRubro" class="help-block">{{ errors.first('rubro') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.rubro_id">
                    <div v-for="msj in errorsApi.rubro_id">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div></br>
        <!--Datos de Habilitación y dirección del comercio-->
        <div v-if="rubro_id != ''" class="col-sm-12">
            <label class="control-label">Cuenta con Comercio de atención. </label> <br>  
            <input type="checkbox" id="checkbox" v-model="rubro.comercio" @click="$forceUpdate()" style="text-align:center;">
            <label for="checkbox">{{ rubro.comercio == true ? "Si" : "No" }}</label>
        </div>
        <div v-if="rubro.comercio == true">
            <div>
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validarRubro}">
                    <div class="col-sm-6">
                        <label for="inputHabilitacion" class="control-label">Habilitación</label><br>
                         <input name="habilitacion" v-validate="'numeric'" type="text" v-model="rubro.habilitacion" value="habilitacion" class="form-control">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('habilitacion')&&validarRubro" class="help-block">{{ errors.first('habilitacion') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.habilitacion">
                            <div v-for="msj in errorsApi.habilitacion">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
              
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validarRubro}">
                    <div class="col-sm-6">
                        <label for="inputNombre" class="control-label">Fecha de habilitación</label><br>
                        <input 
                            v-if="rubro.habilitacion != null && rubro.habilitacion != ''"
                            v-model="rubro.fecha_habilitacion" 
                            type="date"
                            v-validate="'required'"
                            value="fecha"
                            name="fecha"
                            class="form-control">
                        <input 
                            v-else
                            v-model="rubro.fecha_habilitacion" 
                            type="date"
                            value="fecha"
                            name="fecha"
                            class="form-control">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('fecha')&&validarRubro" class="help-block">{{ errors.first('fecha') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.fecha_habilitacion">
                            <div v-for="msj in errorsApi.fecha_habilitacion">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validarRubro}">
                    <div class="col-sm-12">
                        <label class="control-label">Localidad</label><br>
                        <v-select
                            :debounce="250" 
                            :on-search="getOptions" 
                            :options="localidades"
                            data-vv-name="localidad"
                            v-validate="'required'"
                            v-model="domicilio.localidad_id" 
                            placeholder="Seleccione una localidad">
                        </v-select>
                       
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('localidad')&&validarRubro" class="help-block">{{ errors.first('localidad') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.localidad_id">
                            <div v-for="msj in errorsApi.localidad_id">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarRubro}">
                    <div class="col-sm-8">
                        <label for="inputCalle" class="control-label">Direccion </label><br>
                        <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="calle">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('calle')&&validarRubro" class="help-block">{{ errors.first('calle') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.calle">
                            <div v-for="msj in errorsApi.calle">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('numero')||('piso'))&&validarRubro}">
                    <div class="col-sm-2">
                            <label for="inputNro" class="control-label col-sm-12">N° </label><br>
                            <input 
                                name="numero" 
                                v-validate="'numeric'" 
                                type="text" v-model="domicilio.numero" 
                                value="numero" 
                                class="form-control">

                            <!-- validacion vee-validation -->
                            <span v-show="errors.has('numero')&&validarRubro" class="help-block">{{ errors.first('numero') }}</span>
                            <!-- validacion api-->
                            <div class="text-red">
                                <div v-if="errorsApi.numero" v-for="msj in errorsApi.numero">
                                    <p>{{ msj }}</p>
                                </div>
                            </div>
                    </div>

                    <div class="col-sm-2">
                        
                        <label for="inputPiso" class="control-label">Dpto. </label><br>
                        <input name="piso" type="text" v-model="domicilio.piso" value="piso" class="form-control col-sm-12">

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('piso')&&validarRubro" class="help-block">{{ errors.first('piso') }}</span>
                        <div class="text-red">
                            <div v-if="errorsApi.piso" v-for="msj in errorsApi.piso">
                                <p>{{ msj }}</p>
                            </div>
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
            domicilio: {
                type: Object,
                required: true
            },
            rubro: {
                type: Object,
                required: true
            },
            nuevo: {
                type: Boolean,
                required: true
            },
            errorsApi: {
                required: true
            },
            rubrosRegistrados: {
                type: Array,
                required: true
            }
    },
    data() {
        return {
            localidades: [],
            validar: false,
            validarRubro: false,
            error: false,
            optionsCategorias: [],
            categoria_id: '',
            optionsSubcategorias: [],
            subcategoria_id: '',
            optionsRubros: [],
            rubro_id: ''
        }
    },
    components: {
        vSelect
    },
    beforeMount() {
        this.getOptionsCategoria();
    },
    created: function() {
        this.loadDefaultOptions();
    },
    mounted() {
        this.$events.$on('validarForm', () => this.validateBeforeSubmit());     
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {

            this.$validator.validateAll().then(() => {
                    this.validarRubro = false; 
                    if (this.nuevo){

                        this.$emit('validado')
                    }else{

                        this.$emit('validadoEdit')
                    }
                }).catch(() => {

                    this.validarRubro = true;
                });
        },
        //obtiene lista de localidades 
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/localidades/?q='+ search
                ).then(response => {
                    this.localidades = response.data.data
                    loading(false)
                })
        },
        getOptionsCategoria: function() {
            this.$http.get('api/categoria'
                ).then(response => {
                    let optionscat = response.data.data
                    for (let categoria of optionscat){
                        this.optionsCategorias.push({ text: categoria.nombre, value: categoria.id })
                    }
                })

        },
        getOptionsSubcategorias: function(categoria) {
            this.$http.get('api/subcategoria/'
                ).then(response => {
                    let options = response.data.data
                    for (let subcategoria of options){
                        if(categoria == subcategoria.categoria_id){
                            this.optionsSubcategorias.push({ text: subcategoria.nombre, value: subcategoria.id })
                        }
                    }
                })

        },
        getOptionsRubros: function(subcategoria) {
            this.$http.get('api/rubros/'+ subcategoria
                ).then(response => {
                    let options = response.data
                    for (let rubros of options){
                        console.log(this.rubrosRegistrados.length)
                        if(this.rubrosRegistrados.length > 0)
                        {
                            for (var i = 0; i < this.rubrosRegistrados.length; i++) {
                                if(this.rubrosRegistrados[i].rubro_id == rubros.id && this.nuevo){break;}
                                if((i+1) == this.rubrosRegistrados.length)
                                {
                                    this.optionsRubros.push({ text: rubros.nombre, value: rubros.id })
                                }
                            }
                        } else {
                            this.optionsRubros.push({ text: rubros.nombre, value: rubros.id })
                        }
                    }
                    if(this.optionsRubros.length == 0){
                        this.$toast.error({
                            title:'¡Vaya!',
                            message:'Se encuentra registrado en estos rubros. Elija otra subcategoria o categoria'
                        })
                    }
                })

        },
        cambiarCategoria(){
            this.rubro.categoria_id = this.categoria_id;
               if (this.categoria_id!=''){
                 this.optionsSubcategorias = []
                 this.optionsRubros = []
                 this.subcategoria_id =  ''
                 this.rubro_id = ''
                 this.getOptionsSubcategorias(this.categoria_id);
                }
        },
        cambiarSubcategoria(){
            this.rubro.subcategoria_id = this.subcategoria_id;
            if (this.subcategoria_id!=''){
                  this.optionsRubros = []
                  this.rubro_id = ''
                  this.getOptionsRubros(this.subcategoria_id);
             }
        },
        cambiarRubro(){
            this.rubro.rubro_id = this.rubro_id;
        },
        loadDefaultOptions: function(){
            if(!this.nuevo){
                this.categoria_id = this.rubro.rubro.subcategoria.categoria_id;
                this.subcategoria_id = this.rubro.rubro.subcategoria_id;
                this.rubro_id = this.rubro.rubro_id;
                this.getOptionsSubcategorias(this.categoria_id);
                this.getOptionsRubros(this.rubro.rubro.subcategoria_id);
            }
        }
    }
}
</script>