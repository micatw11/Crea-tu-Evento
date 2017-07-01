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
               {{item.descripcion}}
            </p>
        
            <p>
                <b>Fecha de habilitacion: </b>
            
                {{item.fecha_habilitacion}}
            </p>
           <div class="col-sm-4">
                <button type="button" class="btn-block" @click="showModificar = true, idRubro=item.id" >Modificar Rubro</button>
            </div>
        </div>
    </div>
    <!-- Modal Modificar-->
    <div v-if="idRubro" id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
        <div class="modal-dialog">
          <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" @click="closeModal()">&times;</button>
                    <h4 class="modal-title">Modificar datos de Rubro</h4>
                </div>
                <edit-rubro :idRubro="idRubro"></edit-rubro>
            </div>
        </div>
    </div>
</div>
        
</template>

<script>
import auth from '../../auth.js';
import EditRubro from './EditRubro.vue';

export default {
    props: {
            perfil: {
                type: Object,
                required: true
            },
    },
    data() {
        return {
            idRubro: null,
            showModificar: false
        }
    },
    components: {
        EditRubro
    },
    methods: {
        closeModal: function(){
            this.showModificar = false;
        }
    }
}
</script>