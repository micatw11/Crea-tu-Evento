<template>
    <form class="form-horizontal">

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('categoria')&&validar}">
            <label for="inputCategoria" class="col-sm-2 control-label">Categoria</label>
            <div class="col-sm-10">
                 <input name="categoria" v-validate="'required'" type="number" v-model="rubro.categoria" value="categoria">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('categoria')&&validar" class="help-block">{{ errors.first('categoria') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.categoria">
                    <div v-for="msj in errorsApi.categoria">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('denominacion')&&validar}">
            <label for="inputDenominacion" class="col-sm-2 control-label">Denominacion del rubro: </label>
            <div class="col-sm-10">
                <input name="denominacion"  v-validate:rubro.denominacion="'required|min:4'" type="text" class="form-control" v-model="rubro.denominacion" placeholder="Nombre">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('denominacion')&&validar" class="help-block">{{ errors.first('denominacion') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.denominacion">
                    <div v-for="msj in errorsApi.denominacion">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('codigo')&&validar}">
            <label for="inputCodigo" class="col-sm-2 control-label">Codigo de Rubro</label>
            <div class="col-sm-10">
                 <input name="codigo" v-validate="'required'" type="number" v-model="rubro.codigo" value="codigo" class="form-control">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('codigo')&&validar" class="help-block">{{ errors.first('codigo') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.codigo">
                    <div v-for="msj in errorsApi.codigo">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('habilitacion')&&validar}">
            <label for="inputHabilitacion" class="col-sm-2 control-label">Habilitacion</label>
            <div class="col-sm-10">
                 <input name="habilitacion" v-validate="'required'" type="number" v-model="rubro.habilitacion" value="habilitacion" class="form-control">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('habilitacion')&&validar" class="help-block">{{ errors.first('habilitacion') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.habilitacion">
                    <div v-for="msj in errorsApi.habilitacion">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>
        
     <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('facha')&&validar}">
            <label for="inputNombre" class="col-sm-2 control-label">Fecha de Nacimiento</label>
            <div class="col-sm-10"><br/>

                <input 
                    v-model="rubro.fecha_habilitacion" 
                    type="date" 
                    v-validate="'required'"
                    name="facha"
                    v-validate:rubro.fecha_habilitacion="'required'"
                    >
                <!-- validacion vee-validation -->
                <span v-show="errors.has('facha')&&validar" class="help-block">{{ errors.first('facha') }}</span>
                <!-- validacion api-->
                <div class="text-red" v-if="errorsApi.fecha_habilitacion">
                    <div v-for="msj in errorsApi.fecha_habilitacion">
                        <p>{{ msj }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
      
            <div class="col-sm-offset-2 col-sm-10">
              
                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                    Guargar
                </button>
            </div>
        </div>
    </form>
</template>



<script>
import auth from '../../auth.js';
import vSelect from "vue-select";

export default {
    data() {
        return {
            validar: false,
            showModificar: false,
            rubro: { type: Object, default: null},
            error: false,
            errorsApi: []
        }
    },
    components: {
        vSelect
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then(() => {
                    this.sendForm();
                }).catch(() => {
                    this.validar = true;
                });
        },
        //obtiene lista de usuarios segun requiera
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/busqueda/usuarios/?q='+ search
                ).then(response => {
                    this.usuarios = response.data
                    loading(false)
                })
        }
    }
}
</script>