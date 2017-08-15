<template >
<div>
    Habilitación de ingresos brutos: {{perfil.user.proveedor.ingresos_brutos}}
    <div v-if="rubrosRegistrados.length > 0" v-for="item in rubrosRegistrados">
        <div class="content">
            <div class="content" style="border-style: double;">
                <div class="col-sm-8">
                    <p>
                        <b>Tipo de Proveedor: </b>
                        {{item.rubro.subcategoria.categoria.tipo_proveedor}}
                    </p>
                    <div v-if="item.habilitacion != null">
                        <p>
                            <b>Nro Habilitación: </b>
                            {{item.habilitacion}}
                        </p>
                    
                        <p>
                            <b>Fecha de habilitacion: </b>
                        
                            {{item.fecha_habilitacion}}
                        </p>
                    </div>
                    <div v-else>
                        <p><b>Habilitación: </b>Sin habilitación</p>
                    </div>
                    <p>
                        <b>Categoria: </b>
                    
                        {{item.rubro.subcategoria.categoria.nombre}} / {{ item.rubro.subcategoria.nombre }} / {{item.rubro.nombre}}
                    </p>

                    <p if= "item.domicilio">
                        <b>Domicilio: </b><br>
                        calle: {{item.domicilio.calle}}<br>
                        numero: {{item.domicilio.numero}}<br>
                        piso: {{item.domicilio.piso}}
                    </p>
                </div>
                <div class="col-sm-4" style="text-align: center;">
                    <button type="button" class="btn-block" @click="modificar(item.id)" >Modificar Rubro</button>
                </div>

            </div>
        </div>
    </div>
    <div v-else class="content">
        <div class="col-sm-12">
            <h3 class="text-center">No se ha registrado en ningun rubro</h3>
            <p>¡Para realizar publicaciones debe de encontrarse registrado en un rubro!.</p>
        </div>
    </div>
    <!-- Modal Modificar-->
    <div v-if="showModificar &&rubrosRegistrados.length > 0" 
        id="modificar" class="modal" role="dialog" :style="{ display : showModificar  ? 'block' : 'none' }">
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
            showNew: false,
            rubrosRegistrados: []
        }
    },
    beforeMount(){
        this.getRubrosRegistrados();
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
            this.idRubro=id
            this.showModificar = true
        },
        getRubrosRegistrados: function() {
            this.$http.get('api/proveedor/'+auth.user.profile.id+'/rubro/'
                ).then(response => {
                    this.rubrosRegistrados = response.data.rubros
                });
        },
    }
}
</script>