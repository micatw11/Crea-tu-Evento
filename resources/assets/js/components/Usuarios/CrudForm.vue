<template>
    <form @submit.prevent="create" class="form-horizontal container row">
        <!--<div class="form-group">
            <label for="inputName" class="col-sm-2 control-label">Imagen</label>

            <div class="col-sm-10">
                <image-input :image-src="imageSrc"></image-input>

                <div class="mrg-top-1em text-danger" v-for="error of errors['avatar']">
                    @{{ error }}
                </div>
            </div>
        </div>-->
        <div class="form-group">
            <label for="inputApellido" class="col-sm-2 control-label">Apellido</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" v-model="usuario.apellido" placeholder="Apellido">
            </div>
        </div>
        <div class="form-group">
            <label for="inputNombre" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-10">
            <input type="text" class="form-control" v-model="usuario.nombre" placeholder="Nombre">
            </div>
        </div>
        <!--<div class="form-group">
            <label for="inputFech_nac" class="col-sm-2 control-label">Fecha Nacimiento</label>

            <div class="col-sm-10">
            <input type="date" class="form-control" v-model="fech_nac" placeholder="Fecha Nacimiento">
            </div>
        </div>-->
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
export default {

     props: {
            auth: auth,
            usuario: {
                type: Object
            }
        },
   data() {
        return {
            
            error: false
        }
    },
    methods: {
        create() {
                let formData = new FormData();
                formData.set('nombre', this.usuario.nombre);
                formData.set('apellido', this.usuario.apellido);
                //formData.set('fecha_nac', this.fecha_nac);
                formData.set('sexo', this.usuario.sexo);
                formData.set('user_id', auth.user.profile.id);
                this.$emit('usuario', formData);

            }     
    }
}
</script>