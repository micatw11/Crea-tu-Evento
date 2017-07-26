<template>
        <div class="content">
            <p>
                <b>Nombre: </b>
                {{usuario.apellido}}, {{usuario.nombre}}
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


export default {
    props: {
        usuario: {
            required: true
        }
    }
}
</script>