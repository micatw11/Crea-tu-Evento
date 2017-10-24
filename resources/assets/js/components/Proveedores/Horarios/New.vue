<template>
	<div v-if="showFormH">
	    <div class="box-header">
	        <div class="col-sm-12">
	            <button class="btn btn-primary btn-sm"
	            @click="showNewHorario = true">
	            Agregar Horario
	            </button>
	        </div>
	    </div>
		<div v-if="showNewHorario" class="modal" role="dialog" :style="{ display : showNewHorario  ? 'block' : 'none' }">
	        <div class="modal-dialog">
	        <!-- Modal content-->
	            <div class="modal-content">
	                <div class="modal-header">
	                    <button type="button" class="close" @click="closeModal()">&times;</button>
	                    <h4 class="modal-title">Agregar Horario</h4>
	                </div>
	                <div class="modal-body">
	        			<div class="box-body">
							<form-horario :horario="newHorario" @validoFormHorario="sendHorario">
							</form-horario>
						</div>
				        <div class="modal-footer" style="text-align:center;">
				            <button class="btn btn-default" @click="closeModal()">
				                <i class="glyphicon glyphicon-chevron-left"></i>
				                Atras
				            </button>
				            <button @click="validateBeforeSubmit()" type="button" class="btn btn-primary">
				                Agregar
				            </button>
				        </div>
					</div>
					<!-- /.box-body -->
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import FormHorario from './Form'
	export default {
		props: {
			publicacionId:{
				required: true
			}
		},
		data(){
			return {
				newHorario:{
					hora_inicio: '',
					hora_fin: '',
					dias: {
							id:'',
							nombre: ''
						},
					precio: 0,
					publicacion_id: ''
				},
				showFormH: false,
				showNewHorario: false,
			}
		},
		components:{
			FormHorario
		},
		mounted() {
			this.$events.on('showFormH', () => this.showFormH = true);
		},
		methods:{
			validateBeforeSubmit(){
				this.$events.fire('validarFormHorario')
			},
			sendHorario(){
				this.$http.post('api/horario',{
					hora_inicio: this.newHorario.hora_inicio,
					hora_fin: this.newHorario.hora_fin,
					dia: this.newHorario.dias,
					precio: this.newHorario.precio,
					publicacion_id: this.publicacionId
				}).then(response => {
					this.newHorario = {
						hora_inicio: '',
						hora_fin: '',
						dias: {},
						precio: 0,
						publicacion_id: ''
					}
					this.$events.fire('reloadIndexHorario');
					this.showNewHorario= false; 
				}, response => {
					console.log('Error en Horario');
				});
			},
			closeModal(){
				this.showNewHorario= false;
			}
		}
	}
</script>