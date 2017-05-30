<template>
    <form @submit.prevent="create" class="form-horizontal container row">
        <div class="form-group">
            <label for="inputName" class="col-sm-2 control-label">Imagen</label>
            <div class="col-sm-10">
                <image-input @avatar="imageSrc" :image-src="imageSrc"></image-input>

                <!--<div class="mrg-top-1em text-danger" v-for="error of errors['avatar']">
                    @{{ error }}
                </div>-->
            </div>
        </div>
        <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('apellido')}">
                    <input name="apellido" v-model="usuario.apellido" type="text" class="form-control" placeholder="Ingresar apeido">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('apellido')" class="help-block">{{ errors.first('apellido') }}</span>
                    <!-- validacion api-->
                    <!--<div class="help-block" v-if="errorsApi.apelido">
                        <div v-for="msj in errorsApi.apeido">
                            <p>{{ msj }}.</p>
                        </div>
                    </div>-->
           </div>
        <!--<div class="form-group">
            <label for="inputApellido" class="col-sm-2 control-label">Apellido</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" v-model="usuario.apellido" placeholder="Apellido">
            </div>
        </div>-->
        <div class="form-group">
            <label for="inputNombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" v-model="usuario.nombre" placeholder="Nombre">
            </div>
        </div>
        <div class="form-group">
            <label for="inputFech_nac" class="col-sm-2 control-label">Fecha Nacimiento</label>

            <div class="col-sm-10">
            <input type="date" class="form-control" v-model="usuario.fech_nac" placeholder="Fecha Nacimiento">
            </div>
        </div>
        <div class="form-group">
            <label for="inputSexo" class="col-sm-2 control-label">Sexo</label>
            <div class="col-sm-10">
                <input type="radio" v-model="usuario.sexo" value="M">Masculino</input><br>
                <input type="radio" v-model="usuario.sexo" value="F">Femenino</input>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
            <button class="btn btn-danger">Guargar</button>
            </div>
        </div>
    </form>
</template>


<script>
import auth from '../../auth.js';
import ImageInput from './ImageInput.vue';

export default {
    props: {
            auth: auth,
            usuario: {
                type: Object
            }
     },
    components: {ImageInput},
    created() {
            if(! this.usuario.fecha_nac) {
                this.usuario.fecha_nac = moment().format('YYYY-MM-DD')
            }
    },
    data() {
        return {
            
            error: false
        }
    },
    methods: {
            imageSrc() {
                if(this.usuario.avatar instanceof File === false) {
                    if(this.usuario.avatar !== undefined) {
                        let avatar = this.usuario.avatar;

                        if(! avatar) {
                            avatar = 'default.png';
                        }

                        return '/storage/avatars/' + avatar;
                    }
                }
            },
            
        create() {
                let formData = new FormData();
                if(this.usuario.avatar instanceof File) {
                    formData.set('avatar', this.usuario.avatar);
                }
                formData.set('nombre', this.usuario.nombre);
                formData.set('apellido', this.usuario.apellido);
                formData.set('fecha_nac', this.usuario.fecha_nac);
                formData.set('sexo', this.usuario.sexo);
                formData.set('user_id', auth.user.profile.id);
                this.$emit('usuario', formData);
            }     
    }
}
</script>