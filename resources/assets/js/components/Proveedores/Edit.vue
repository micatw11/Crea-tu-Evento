<template>
    <div>
        <div class="modal-body">
        	<form-proveedor 
                :proveedor="proveedor" 
                :validarProveedor="validarProveedor" 
                :errorsApi="errorsApi"
                @validadoEditProveedor="sendForm()" 
                :nuevo= "nuevo">
                
            </form-proveedor>
        </div>
        <div class="modal-footer">
            <div class="col-sm-12 box-footer clearfix" style="text-align:center;">
                <button class="btn btn-default" @click="atras()">
                    <i class="glyphicon glyphicon-chevron-left"></i>
                    Atras
                </button>
                <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                    Guargar
                </button>
            </div>
        </div>
    </div>   
</template>

<script>
import auth from '../../auth.js';
import FormProveedor from './Form.vue';

export default {
    props: {
            idProveedor: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            Proveedores: { type: Object, default: null},//Peticion de datos
            proveedor: { type: Object, default: null},
            validarProveedor: false,
            errorsApi: {},
            error: false,
            fecha: null,
            nuevo: false
        }
    },
    components: {
        FormProveedor
    },
    beforeMount: function(){
        //selected data
        this.getProveedor();
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
            this.$http.post(
                'api/proveedor/'+ this.proveedor.id+'/edit', 
                {
                    _method: 'PATCH',
                    user_id: this.proveedor.user_id.value,
                    nombre: this.proveedor.nombre,
                    cuit: this.proveedor.cuit,
                    ingresos_brutos: this.proveedor.ingresos_brutos,
                    email: this.proveedor.email,
                    dni: this.proveedor.dni,
                    calle: this.proveedor.domicilio.calle,
                    numero: this.proveedor.domicilio.numero,
                    piso: this.proveedor.domicilio.piso,
                    localidad_id: this.proveedor.domicilio.localidad_id.value
                })
                .then(response => {

                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                    this.$events.fire('reloadIndexProveedor');
                    this.atras();
                }, response => {
                    this.validar= false;
                    this.validarProveedor= false;
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios. :('
                    });
                    if(response.status === 422)
                    {
                        this.errorsApi = response.body;
                    }
                })
        },

        //form validation
        validateBeforeSubmit: function() {                 
                    this.validarProveedor = true;
                    this.$events.fire('validarFormProveedor')

        },
        getProveedor: function(){
            this.$http.get('api/proveedor/'+ this.idProveedor )
                .then(response => {
                this.proveedores = response.data.data
                   this.cargarProveedor()

                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        cargarProveedor:function(){
            this.proveedor= this.proveedores,
            this.proveedor.user_id = {
               'value':this.proveedor.user_id,
               'label':this.proveedor.user.usuario.apellido+', '+this.proveedor.user.usuario.nombre+' ('+this.proveedor.user.email+')'
            },
            this.proveedor.domicilio.localidad_id = {
               'value':this.proveedor.domicilio.localidad.id,
               'label':this.proveedor.domicilio.localidad.nombre+' ('+this.proveedor.domicilio.localidad.provincia.nombre+')'
            }
        },
        atras: function(){
            this.$events.fire('cerrar');
        }

    }
}



        
</script>