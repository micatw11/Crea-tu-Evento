<template >
<div>
    <div v-for="item in perfil.user.proveedor.rubro">
        <div class="content">
            <div class="content" style="border-style: double;">
                <div class="col-sm-8">
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
                </div>
                <div class="col-sm-4" style="text-align: center;">
                    <button type="button" class="btn-block" @click="modificar(item.id)" >Modificar Rubro</button>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal Modificar-->
    <div v-if="showModificar" id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
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
    mounted() {
        this.$events.$on('cerrar', () =>this.closeModal());
    },
    components: {
        EditRubro
    },
    methods: {
        closeModal: function(){
            this.showModificar = false;
        },
        modificar: function(id){
            this.showModificar = true, 
            this.idRubro=id
        }
    }
}
</script>