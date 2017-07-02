<template>
    <div>
        <div class="modal-body">
        	<form-proveedor 
                :proveedor="proveedor" 
                :domicilio="domicilio" 
                :validarDomicilio="validarDomicilio" 
                :validarProveedor="validarProveedor" 
                :errorsApi="errorsApi" >
                
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
            domicilio: { type: Object, default: null}, 
            proveedor: { type: Object, default: null},
            validarProveedor: false,
            validarDomicilio: false,
            errorsApi: {},
            error: false,
            fecha: null,
            disabled: { to: '1920-01-01', from: null }
        }
    },
    components: {
        FormProveedor
    },
    beforeMount: function(){
        //selected data
        console.log("proveedoreesss")
        this.getProveedor();
        
    },
     mounted() {
        this.$events.$on('validadoprove', () =>this.sendForm());
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
            this.$http.post(
                'api/proveedor/'+ this.proveedor.id+'/edit', 
                {
                    _method: 'PATCH',
                    tipo_rubro: this.proveedor.tipo_rubro,
                    categoria_id: this.proveedor.categoria_id,
                    denominacion: this.proveedor.denominacion,
                    descripcion: this.proveedor.descripcion,
                    habilitacion: this.proveedor.habilitacion,
                    fecha_habilitacion: this.proveedor.fecha_habilitacion,
                    calle: this.domicilio.calle,
                    numero: this.domicilio.numero,
                    piso: this.domicilio.piso,
                    localidad_id: this.domicilio.localidad_id.value
                })
                .then(response => {
                    this.$emit('reload')
                    this.atras();
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se han realizado correctamente los cambios. :D'
                    });
                }, response => {
                    this.validar= false;
                    this.validarDomicilio= false;
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
                    this.validarDomicilio = true;
                    this.$events.fire('validarForm')

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
            this.domicilio= this.proveedores.domicilio,
            this.domicilio.localidad_id = {
               'value':this.domicilio.localidad_id,
               'label':this.domicilio.localidad.nombre+' ('+this.domicilio.localidad.provincia.nombre+')'
            },
            this.proveedor= this.proveedores,
            this.proveedor.user_id = {
               'value':this.proveedor.user_id,
               'label':this.proveedor.user.usuario.apellido+', '+this.proveedor.user.usuario.nombre+' ('+this.proveedor.user.email+')'
            }
        },
        atras: function(){
            this.$events.fire('cerrar');
            this.$events.fire('reloadComponentPerfil');
        }

    }
}



        
</script>