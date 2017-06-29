<template>
<div>
    <div v-for="item in perfil.user.proveedor.rubro">
        <div class="content">
            <p>
                <b>Tipo de Rubro: </b>
                {{item.tipo_rubro}}
            </p>

            <p>
                <b>Nro Habilitación: </b>
                {{item.habilitacion}}
            </p>
        
            <p>
                <b>Denominacion: </b>
               {{item.denominacion}}
            </p>
            <p>
                <b>Descripcion: </b>
               {{item.desripcion}}
            </p>
        
            <p>
                <b>Fecha de habilitacion: </b>
            
                {{item.fecha_habilitacion}}
            </p>
        </div>
    </div>
</div>
</template>

<script>
import auth from '../../auth.js';

export default {
    props: {
            perfil: {
                type: Object,
                required: true
            },
    }
}
</script>