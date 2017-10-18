<template>

	<div class="row" v-if="optionsRubros.length>0">
		<div class="box-header">
			<h3 class="box-title">Agregar</h3>
		</div>
		<div class="col-xs-3">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarArticulo}">
				<input type="text" v-model="articulo.nombre" v-validate="'required'" name="nombre" class="form-control" placeholder="Nombre">
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('nombre')&&validarArticulo" class="help-block">{{ errors.first('nombre') }}</span>
        	</div>
		</div>
		<div class="col-xs-2">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('stock')&&validarArticulo}">
				<input type="text" name="stock" v-model="articulo.stock" v-validate="'numeric'" class="form-control" placeholder="Stock">
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('stock')&&validarArticulo" class="help-block">{{ errors.first('stock') }}</span>
        	</div>
		</div>
		<div class="col-xs-2">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('precio')&&validarArticulo}">
				<money v-model="articulo.precio" v-bind="money" v-validate="'required|min_value:1'" data-vv-name="precio"></money>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('precio')&&validarArticulo" class="help-block">{{ errors.first('precio') }}</span>
	        </div>
		</div>
		<div class="col-xs-5">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubro')&&validarArticulo}">
				<select name="rubro" v-model="articulo.rubro_id" class="form-control" v-validate="'required'">
					<option value="" disabled>Selecione rubros</option>
					<option v-for="option in optionsRubros" :value="option.id">{{option.nombre}}</option>
				</select>
				<!-- validacion vee-validation --> 
	            <span v-show="errors.has('rubro')&&validarArticulo" class="help-block">{{ errors.first('rubro') }}</span>
        	</div>
		</div>
	</div>

</template>
<script>
	import Money from './../../Plugins/v-money/src/component'
	export default {
		props: {
			rubros: {
				required: true,
				type: Array
			},
			articulo:{
				required: true,
				type: Object
			}
		},
		data(){
			return {
				optionsRubros:[],
				validarArticulo: false,
				money: {
                    decimal: ',',
                    thousands: '.',
                    prefix: '$ ',
                    precision: 2,
                    masked: false
                }
			}
		},
		beforeMount(){
			this.getRubros();
		},
		mounted(){
			this.$events.on("validarFormArticulo", () => this.validateSubmit())
		},
		components: {Money},
		methods:{
	        validateSubmit: function() {

	            this.$validator.validateAll().then(() => {
	                    this.validarArticulo = false; 
	                    this.$emit('validoForm'); 
	                }).catch(() => {

	                    this.validarArticulo = true;
	                });
	        },
			getRubros(){
				this.$http.get('api/rubro').then(response => {
					for(var rubro of response.data){
						for(var id of this.rubros){
							if(id == rubro.id && !rubro.salon && rubro.producto ){
								this.optionsRubros.push(rubro);
							}
							if(id == rubro.id && (rubro.salon || rubro.servicio) ){
								this.$events.emit('showFormH');
							}
						}
					}
					if (this.optionsRubros.length == 0) {
						this.$events.emit('showForm');
					}
				}, response => {
					console.log('Error en rubros');
				});
			}
		}
	}
</script>