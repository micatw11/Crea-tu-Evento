<template>
	<div class="modal-body">
		<div class="box-body">
        	<form-presupuesto 
                :rubros="publicacion.prestacion.rubros"
                :articulos="publicacion.articulos"
                :presupuesto="presupuesto"
                :tipo="'tipo'"
                :errorsApi="errorsApi"
                @validado="sendForm()">
            </form-presupuesto>
		</div>
		<div class="modal-footer" style="text-align:center;">
            <button class="btn btn-default" @click="closeModal()">
                <i class="glyphicon glyphicon-chevron-left"></i>
                Atras
            </button>
            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
                Reservar
            </button>
		</div>
	</div>
</template>
<script>

	import FormPresupuesto from './Form';

	export default {
		props: {
			publicacion: {
				required: true,
				type: Object
			}
		},
		data(){
			return {
				tipo: 'presupuesto',
				presupuesto: {
					rubros: [],
					articulos: [],
					fecha: '',
					horario_id: '',
					estado: 'reserva',
					precio_total: '',
					comentario: ''
				},
				errorsApi: []
			}
		},
		components: { FormPresupuesto },
		methods: {
			validateBeforeSubmit: function() {                 
	            this.$events.fire('validarFormPresupuesto')
	        },
	        sendForm: function(){
	            this.$http.post(
	                'api/publicacion/'+this.publicacion.id+'/presupuesto', 
	                {
	                	rubros: this.presupuesto.rubros,
	                	articulos: this.presupuesto.articulos,
	                	fecha_hora: this.presupuesto.fecha_hora,
	                	hora_finalizacion: this.presupuesto.hora_finalizacion,
	                	estado: this.presupuesto.estado,
	                	precio_total: this.presupuesto.precio_total,
	                	comentario: this.presupuesto.comentario
	                })
	                .then(response => {
	                    this.closeModal(),
	                    this.errorsApi= {},
	                    this.$toast.success({
	                        title:'Reserva',
	                        message:'Se ha realizado correctamente su reserva'
	                    });
	                    this.resetForm()
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido realizado correctamente su reserva'
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                })
	        },
			closeModal: function(){
            	this.$events.fire('cerrar')
        	}
		}
	}
</script>