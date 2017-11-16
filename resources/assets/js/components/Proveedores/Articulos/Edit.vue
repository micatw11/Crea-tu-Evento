<template>
	<div class="box-body">

		<div class="row">
			<div class="col-xs-6">
				<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarArticulo}">
					<input type="text" v-model="articulo.nombre" v-validate="'required'" name="nombre" class="form-control" placeholder="Nombre">
					<!-- validacion vee-validation -->
		            <span v-show="errors.has('nombre')&&validarArticulo" class="help-block">{{ errors.first('nombre') }}</span>
	        	</div>
			</div>
			<div class="col-xs-6">
				<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('stock')&&validarArticulo}">
					<input type="text" name="stock" v-model="articulo.stock" v-validate="'numeric'" class="form-control" placeholder="Stock">
					<!-- validacion vee-validation -->
		            <span v-show="errors.has('stock')&&validarArticulo" class="help-block">{{ errors.first('stock') }}</span>
	        	</div>
			</div>
			<div class="col-xs-6">
				<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('precio')&&validarArticulo}">
					<vue-numeric currency="$" separator="," v-bind:precision="2" v-model="articulo.precio" v-validate="'required|min_value:1'" data-vv-name="precio"></vue-numeric>
					<!-- validacion vee-validation -->
		            <span v-show="errors.has('precio')&&validarArticulo" class="help-block">{{ errors.first('precio') }}</span>
		        </div>
			</div>
		</div>
	</div>
					
</template>
<script>
	export default {
		props: {
			id:{
				required: true
			}
		},
		data(){
			return {
				showModal: false,
				articulo:{
					type: Object
				}
			}
		},
		methods:{
			validateBeforeSubmit(){
            	this.$validator.validateAll().then((result) => {
                	if (result) {
	                    this.validarArticulo = false; 
	                    this.sendArticulo();
	                } else {
						this.validarArticulo = true;
	                }
	                return;
                }).catch(() => {
                    
                });
			},
			getArticulo(){
				this.$http.get('api/articulo/'+this.id
				).then(response => {
					this.articulo = response.data
				}, response => {
					console.log('Error en articulos');
				});
			},
			sendArticulo(){
				this.$http.path('api/articulo/'+this.id,{
					nombre: this.newArticulo.nombre,
					stock: this.newArticulo.stock,
					precio: this.newArticulo.precio,
				}).then(response => {
					this.closeModal();
					this.$events.fire('reloadIndexArticulo'); 
				}, response => {
					console.log('Error en articulos');
				});
			}
		}
	}		
</script>