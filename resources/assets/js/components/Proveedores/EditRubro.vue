<template>
    <div>
        <div class="modal-body">
                Formulario
        	<form-rubro :rubro="rubro" :domicilio="domilio" :validarDomicilio="validarDomicilio" :validarRubro="validarRubro" :errorsApi="errorsApi"></form-rubro>
        </div>
        <div class="modal-footer">
            <div class="col-sm-12 box-footer clearfix" style="text-align:center;">
                <button class="btn btn-default">
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
            rubros: { type: Object},
            domicilio: null,
            rubro: null,
            validarRubro: false,
            validarDomicilio: false,
            showModificar: false,
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
        this.getUserPerfil();
        //this.setDefaultLocalidad();
    },
     mounted() {
         //rangos maximos de fechas
        //this.fecha_habilitacion = new Date();
        //this.disabled.from = this.fecha.getFullYear()+'-'+this.fecha.getMonth()+'-'+this.fecha.getDate();

        this.$events.$on('validado', () =>this.sendForm());
    },
    methods: {
        //envio de formulario de modificación de informacion de usuario
        


        //form validation
        validateBeforeSubmit: function() {                 
                    this.validarRubro = true;
                    this.validarDomicilio = true;
                    this.$events.fire('validarForm')

        },
        getUserPerfil: function(){
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
           this.domicilio= {
                calle: this.rubros.domicilio.calle,
                numero: this.rubros.domicilio.numero,
                piso: this.rubros.domicilio.piso,
                localidad_id: this.rubros.domicilio.localidad_id
            },
            this.rubro= {
                tipo_rubro: this.rubros.tipo_rubro,
                categoria_id: this.rubros.categoria_id,
                denominacion: this.rubros.denominacion,
                descripcion: this.rubros.descripcion,
                habilitacion: this.rubros.habilitacion,
                fecha_habilitacion: this.rubros.fecha_habilitacion
            }
        }
    }
}



        
</script>