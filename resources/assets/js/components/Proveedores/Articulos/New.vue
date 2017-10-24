<template>
	<div class="pull-right">
		<button class="btn btn-primary" @click="showModal = !showModal">Agregar Articulo</button>
        <div v-if="showModal" class="modal" role="dialog" :style="{ display : showModal  ? 'block' : 'none' }">
            <div class="modal-dialog">
            <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="closeModal()">&times;</button>
                        <h4 class="modal-title">Agregar Articulo</h4>
                    </div>
					<div class="modal-body">
			        	<div class="box-body">
							<form-rubro 
								:rubros="rubros" 
								:articulo="newArticulo" 
								@validoForm="sendArticulo">
							</form-rubro>
						</div>
					</div>
					<div class="modal-footer">
						<button @click.stop="validateBeforeSubmit" class="btn btn-info pull-right">Agregar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import FormArticulo from './Form'
	export default {
		props: {
			rubros:{
				type: Array,
				required: true
			}
		},
		data(){
			return {
				showModal: false,
				newArticulo:{
					nombre: '',
					stock: null,
					precio: 0,
					rubro_id: ''
				},
				showForm: true
			}
		},
		components:{
			FormArticulo
		},
		mounted() {
			this.$events.on('showForm', () => this.showForm = false);
		},
		methods:{
			validateBeforeSubmit(){
				this.$events.fire('validarFormArticulo')
			},
			sendArticulo(){
				this.$http.post('api/articulo',{
					nombre: this.newArticulo.nombre,
					stock: this.newArticulo.stock,
					precio: this.newArticulo.precio,
					rubro_id: this.newArticulo.rubro_id
				}).then(response => {
					this.closeModal();
					this.$events.fire('reloadIndexArticulo'); 
				}, response => {
					console.log('Error en articulos');
				});
			},
			closeModal(){
				this.showModal = !this.showModal;
				this.newArticulo = {
					nombre: '',
					stock: null,
					precio: 0,
					rubro_id: ''
				}
			}
		}
	}
</script>