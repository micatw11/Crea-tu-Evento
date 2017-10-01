<template>
		<div class="box">
		<div class="box-header">
			<h3 class="box-title">Agregar</h3>
		</div>
		<div class="box-body">
			<form-rubro :rubros="rubros" :articulo="newArticulo" @validoForm="sendArticulo">
				
			</form-rubro>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button @click.stop="validateBeforeSubmit" class="btn btn-info pull-right">Agregar</button>
		</div>
	</div>
</template>
<script>
	import FormRubro from './Form'
	export default {
		props: {
			rubros:{
				type: Array,
				required: true
			}
		},
		data(){
			return {
				newArticulo:{
					nombre: '',
					stock: null,
					precio: 0,
					rubro_id: ''
				}
			}
		},
		components:{
			FormRubro
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
					this.newArticulo = {
						nombre: '',
						stock: null,
						precio: null,
						rubro_id: ''
					}
					this.$events.fire('reloadIndexArticulo'); 
				}, response => {
					console.log('Error en articulos');
				});
			},
		}
	}
</script>