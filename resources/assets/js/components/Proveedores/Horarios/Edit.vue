<template>
	<div>
        <div class="modal-body">
			<div class="box-body">
				<form-horario v-if="showModificarHorario==true" :horario="Horario" :nuevo="false" @validoFormHorario="sendHorario">
				</form-horario>
			</div>
	        <div class="modal-footer" style="text-align:center;">
	            <button class="btn btn-default" @click="closeModal()">
	                <i class="glyphicon glyphicon-chevron-left"></i>
	                Atras
	            </button>
	            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
	                Modificar
	            </button>
	            <button @click="showDeleteH = true" type="button" class="btn btn-danger">
	                Eliminar
	            </button>
	        </div>
		</div>
        <!-- Modal Eliminar Horario-->
        <div class="modal" role="dialog" :style="{ display : showDeleteH  ? 'block' : 'none' }">
            <div class="modal-dialog" style="width: 300px; text-align: center;">

                <!-- Modal content-->
                <div class="modal-content" style="width: 350px; text-align: center;">
                    <div class="modal-body">
                        <button type="button" class="close" @click="showDeleteH = false">&times;</button>
                        <div class="box-body">
                            <p>¿Esta seguro que desea </p>
                            <p>eliminar el horario?</p>
                        </div>
                            <button
                                @click="deleteH()" 
                                type="button" class="btn btn-danger">
                             Aceptar
                            </button>
                               <button type="button" @click="showDeleteH = false" class="btn btn-default">
                                Cancelar
                            </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import FormHorario from './Form'
	export default {
		props: {
			horarioId:{
				required: true
			},
			publicacionId:{
				type: Number
			},
			horariosId:{
				type: Array
			}
		},
		data(){
			return {
				Horario:{
					hora_inicio: '',
					hora_fin: '',
					dia: '',
					precio: 0,
					publicacion_id: ''
				},
				showModificarHorario: false,
				showDeleteH: false
			}
		},
		beforeMount: function(){
			this.getHorario()
		},
		components:{
			FormHorario
		},
		methods:{
			validateBeforeSubmit(){
				this.$events.fire('validarFormHorario')
			},
			sendHorario(){
				this.$http.patch('api/horario/'+this.horarioId,{
					hora_inicio: this.Horario.hora_inicio,
					hora_fin: this.Horario.hora_fin,
					dia: this.Horario.dia,
					precio: this.Horario.precio,
					publicacion_id: this.publicacionId
				}).then(response => {
					this.Horario = {
						hora_inicio: '',
						hora_fin: '',
						dias: {},
						precio: 0,
						publicacion_id: ''
					}
					this.showModificarHorario= false;
					this.$events.fire('reloadIndexHorario')
	        		this.$events.fire('close'); 
				}, response => {
						this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha guardado el horario. :('
	                    });
				});
			},
			getHorario: function(){
	        	this.$http.get('api/horario/'+this.horarioId)
	        	.then(response =>{
	        		this.Horario = response.data
	        		this.showModificarHorario=true
	        	}, response => {
                    if(response.status === 404){
                        router.go(-1)
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha cargado los horarios. :('
	                    });
                    }
	        	});
	        },
			closeModal(){
				this.$events.fire('close');

			},
			deleteH(){
				
	        	this.$http.post('api/horario/'+this.horarioId, 
                {
                    _method: 'DELETE'
                })
	        	.then(response =>{

	        		this.showModificarHorario=false
	        		this.showDeleteH=false
	        		
	        		this.$events.fire('deleteId', this.horarioId);
		        	this.$events.fire('reloadIndexHorario');
	        		this.$events.fire('close');
	        	}, response => {
                    if(response.status === 404){
                        router.go(-1)
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha cargado su publicación. :('
	                    });
                    }
	        	});
			}

		}
	}
</script>