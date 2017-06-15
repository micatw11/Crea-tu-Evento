<template>
        <div class="content">
            <p> 
                <b>Apellido: </b>
            
                {{usuario.apellido}}
            </p>

            <p>
                <b>Nombre: </b>
                {{usuario.nombre}}
            </p>

            <p>
                <b>Localidad: </b>
                {{usuario.localidad.nombre}} - {{usuario.localidad.provincia.nombre}}
            </p>
        
            <p>
                <b>Sexo: </b>
               {{usuario.sexo=='F'?'Femenino':'Masculino'}}
            </p>
        
            <p>
                <b>Fecha de Nacimiento: </b>
            
                {{usuario.fecha_nac}}
            </p>
        </div>
</template>

<script>
import auth from '../../auth.js';

export default {
    data() {
        return {
            usuario: auth.user.profile.usuario
        }
    }
}
</script>