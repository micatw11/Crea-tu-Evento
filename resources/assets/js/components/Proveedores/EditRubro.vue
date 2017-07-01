<template>
    <div>
        <div class="modal-body">
                Formulario
        	<form-rubro :rubro="rubro" :domicilio="domicilio" :validarDomicilio="validarDomicilio" :validarRubro.sync="validarRubro" :errorsApi.sync="errorsApi" ></form-rubro>
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
import FormRubro from './FormRubro.vue';

export default {
    props: {
            idRubro: {
                type: Number,
                required: true
            },
    },
    data() {
        return {
            rubros: { type: Object, default: null},//Peticion de datos
            domicilio: { type: Object, default: null}, 
            rubro: { type: Object, default: null},
            validarRubro: false,
            validarDomicilio: false,
            errorsApi: {},
            error: false,
            Comercio: null,
            fecha: null,
            disabled: { to: '1920-01-01', from: null }
        }
    },
    components: {
        FormRubro
    },
    beforeMount: function(){
        //selected data
        this.getRubro();
        
    },
     mounted() {
        this.$events.$on('validado', () =>this.sendForm());
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        sendForm: function() {
             console.log('send', this.rubros)
            this.$http.post(
                'api/proveedor/rubro/'+ this.rubros.id+'/edit', 
                {
                    _method: 'PATCH',
                    tipo_rubro: this.rubro.tipo_rubro,
                    categoria_id: this.rubro.categoria_id,
                    denominacion: this.rubro.denominacion,
                    descripcion: this.rubro.descripcion,
                    habilitacion: this.rubro.habilitacion,
                    fecha_habilitacion: this.rubro.fecha_habilitacion,
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
                    this.validarRubro= false;
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
                    this.validarRubro = true;
                    this.validarDomicilio = true;
                    this.$events.fire('validarForm')

        },
        getRubro: function(){
            console.log(this.idRubro)
            this.$http.get('api/proveedor/'+ this.idRubro +'/rubro' )
                .then(response => {
                    
                    this.rubros = response.data.data
                   console.log(this.rubros)
                   this.cargarRubro()

                }, response => {
                    if(response.status === 404){
                        router.push('/404');
                    }
                })
        },
        cargarRubro:function(){
            this.domicilio= this.rubros.domicilio,
            this.domicilio.localidad_id = {
               'value':this.domicilio.localidad_id,
               'label':this.domicilio.localidad.nombre+' ('+this.domicilio.localidad.provincia.nombre+')'
            }
            this.rubro= this.rubros
        },
        atras: function(){
            this.$events.fire('cerrar');
            this.$events.fire('reloadComponentPerfil');
        }

    }
}



        
</script>