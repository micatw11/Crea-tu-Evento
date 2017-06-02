<template>

    <form @submit.prevent="validateBeforeSubmit" class="form-horizontal">

        <div class="form-group">
            <label for="inputApellido" class="col-sm-2 control-label">Apellido</label>
            <div class="col-sm-10">
                <input name="apellido"  v-validate:usuario.apellido="'min:2'" type="text" class="form-control" v-model="usuario.apellido" placeholder="Apellido">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('apellido')" class="help-block">{{ errors.first('apellido') }}</span>
            </div>
        </div>
        <div class="form-group">
            <label for="inputNombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
                <input name="nombre"  v-validate:usuario.nombre="'min:2'" type="text" class="form-control" v-model="usuario.nombre" placeholder="Nombre">
                <!-- validacion vee-validation -->
                <span v-show="errors.has('nombre')" class="help-block">{{ errors.first('nombre') }}</span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Localidad</label>
            <div class="col-sm-10">
                <v-select 
                    :debounce="250" 
                    :on-change="changeSelect"
                    :on-search="getOptions" 
                    v-model="localidadDeafult" 
                    :options="localidades"
                    placeholder="Seleccione una localidad">
                </v-select>
                <!-- validacion vee-validation -->
                <span v-show="usuario.localidad_id == null" class="help-block">El campo localidad es requerido.</span>
            </div>
        </div>

        <div class="form-group">
            <label for="inputSexo" class="col-sm-2 control-label">Sexo</label>
            <div class="col-sm-10">
                <input name="sexo"  v-validate:usuario.sexo="'required'" type="radio" v-model="usuario.sexo" value="M" :checked="{'false': true, 'true': usuario.sexo == 'M'}" >Masculino</input><br>
                <input name="sexo" type="radio" v-model="usuario.sexo" value="F" :checked="{'false': true, 'true': usuario.sexo == 'F'}">Femenino</input>
                <!-- validacion vee-validation -->
                <span v-show="errors.has('sexo')" class="help-block">{{ errors.first('sexo') }}</span>
            </div>
        </div>

        <div class="form-group">
            <label for="inputNombre" class="col-sm-2 control-label">Fecha de Nacimiento</label>
            <div class="col-sm-10"><br/>

                <input 
                    v-model="usuario.fecha_nac" 
                    type="date" 
                    name="facha de nacimiento"
                    v-validate:usuario.fecha_nac="'required'"
                    :min="disabled.to"
                    :max="disabled.from"
                    >
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-danger">Guargar</button>
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
            usuario: auth.user.profile.usuario,
            error: false,
            fecha: null,
            disabled: {
                to: '1920-01-01',
                from: ''
            },
            localidades: [],
            localidadDeafult: []
        }
    },
    beforeMount: function(){
        //selected data
       this.localidadDeafult = {
           'value':auth.user.profile.usuario.localidad_id,
           'label':auth.user.profile.usuario.localidad.nombre+' ('+auth.user.profile.usuario.localidad.provincia.nombre+')'
        }
    },
    mounted: function(){
        //rangos maximos de fechas
        this.fecha = new Date();
        this.disabled.from = this.fecha.getFullYear()+'-'+this.fecha.getMonth()+'-'+this.fecha.getDate();

    },
    components: {
        vSelect
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
                
            this.$http.post(
                'api/usuario/'+this.$route.params.userId,
                {
                    _method: 'PATCH',
                    nombre:  this.usuario.nombre,
                    apellido:  this.usuario.apellido,
                    fecha_nac:  this.usuario.fecha_nac,
                    localidad_id:  this.usuario.localidad_id,
                    sexo:  this.usuario.sexo
                })
                .then(response => {
                    this.$emit('reload')
                }, response => {
                    console.log(response);
                })

        },

        //form validation
        validateBeforeSubmit: function(e) {
            this.$validator.validateAll().then(() => {
                this.sendForm();
                }).catch(() => {
                    // failed
                });
        },
        //obtiene lista de localidades segun correponda
        getOptions: function(search, loading) {
            loading(true)
            this.$http.get('api/localidades/?q='+ search
                ).then(response => {
                    this.localidades = response.data.data
                    loading(false)
                })
        },
        changeSelect: function(val){
            this.usuario.localidad_id = val.value
        }
    }
}
</script>