<template >
<div>
Estas Habilitado a Ser Proveedor con Habilitación de ingresos brutos: {{perfil.user.proveedor.ingresos_brutos}}
    <div>
        <div class="box-header">
            <div class="col-xs-2">
                <button class="btn btn-block btn-primary btn-sm"
                    @click="showNew = true">
                    Crear Rubro
                </button>
            </div>
        </div>

        <div v-if="showNew" class="modal" role="dialog" :style="{ display : showNew  ? 'block' : 'none' }">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()">&times;</button>
                        <h4 class="modal-title">Crear Rubro</h4>
                    </div>
                    <new-rubro></new-rubro>
                </div>
            </div>    
        </div>
    </div> 
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

                    <p>
                        <b>Categoria: </b>
                    
                        {{item.categoria.nombre}}
                    </p>

                    <p if= "item.domicilio">
                        <b>Domicilio: </b>
                        calle: {{item.domicilio.calle}}
                        numero: {{item.domicilio.numero}}
                        piso: {{item.domicilio.piso}}
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
import auth from '../../../auth.js';
import EditRubro from './EditRubro.vue';
import NewRubro from './NewRubro.vue';

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
            showModificar: false,
            showNew: false
        }
    },
    mounted() {
        this.$events.$on('cerrar', () =>this.closeModal());
    },
    components: {
        EditRubro, NewRubro
    },
    methods: {
        closeModal: function(){
            this.showModificar = false;
            this.showNew = false;
        },
        modificar: function(id){
            this.showModificar = true, 
            this.idRubro=id
        }
    }
}
</script>