<template>
	<div>
		<form role="form">
			<template v-if="requiredDate">
				<div class="col-sm-4">
		            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validarReserva}">
		                <div class="col-sm-12">
		                    <label for="fecha" class="control-label">Fecha del evento </label><br>
		                    <el-date-picker
						    	v-model="reserva.fecha"
						    	data-vv-name="fecha"
						    	v-validate="'required'" 
						    	@change="changeDate"
						    	type="date"
						    	:picker-options="pickerOptions"
						    	placeholder="Seleccione un dia">
						    </el-date-picker>
						    <!-- validacion vee-validation -->
                    		<span v-show="errors.has('fecha')" class="help-block">{{ errors.first('fecha') }}</span>
		                </div>
		            </div>
		        </div>

		        <template v-if="showEstadoReserva">
		        	<div v-if="opcionesReservas.length > 0" class="col-sm-8">
		        		<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('horario')&&validarReserva}">
			        		<div class="col-sm-12">
			        			<label for="horario" class="control-label">Horarios disponibles </label><br>
				        		<el-select v-model="reserva.horario_id" placeholder="Seleccione un horario" 
				        			data-vv-name="horario" v-validate="'required'" style="width:100%;" @change="calularCoste()">
									<el-option
										v-for="item in opcionesReservas"
										:key="item.id"
										:label="'Desde ' + item.hora_inicio + ' hs Hasta ' + item.hora_finalizacion+ ' hs | precio: $' + item.precio"
										:value="item.id">
									</el-option>
				        		</el-select>
				        		<span v-show="errors.has('horario')" class="help-block">{{ errors.first('horario') }}</span>
				        	</div>
				        </div>
		        	</div>
		        	<div v-else class="col-sm-12">
		        		<div class="col-sm-12">
		        			No se han encontrado horarios disponibles este d&iacute;a.
		        		</div>	        		
		        	</div>
		        </template>
		    </template>
	        <template v-if="isRequiredDomicilio">
				<div class="col-sm-12">
					<hr>
	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validarReserva}">
	                    <div class="col-sm-12">
	                        <label class="control-label">Localidad</label><br>
	                        <v-select
	                            :debounce="250" 
	                            :on-search="getOptions" 
	                            :options="localidades"
	                            data-vv-name="localidad"
	                            v-validate="'required'"
	                            v-model="domicilio.localidad_id" 
	                            placeholder="Seleccione una localidad">
	                        </v-select>
	                       
	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('localidad')&&validarReserva" class="help-block">{{ errors.first('localidad') }}</span>
	                    </div>
	                </div>

	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarReserva}">
	                    <div class="col-sm-8">
	                        <label for="inputCalle" class="control-label">Direccion </label><br>
	                        <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="Calle">
	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('calle')&&validarReserva" class="help-block">{{ errors.first('calle') }}</span>
	                    </div>
	                </div>
	                
	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('numero')||('piso'))&&validarReserva}">
	                    <div class="col-sm-2">
	                            <label for="inputNro" class="control-label col-sm-12">N° </label><br>
	                            <input 
	                                name="numero" 
	                                v-validate="'numeric'" 
	                                type="text" v-model="domicilio.numero" 
	                                value="numero" 
	                                class="form-control"
	                                placeholder="Numero">

	                            <!-- validacion vee-validation -->
	                            <span v-show="errors.has('numero')&&validarReserva" class="help-block">{{ errors.first('numero') }}</span>
	                    </div>

	                    <div class="col-sm-2">
	                        
	                        <label for="inputPiso" class="control-label">Dpto. </label><br>
	                        <input name="piso" type="text" v-model="domicilio.piso" placeholder="Piso" class="form-control col-sm-12">

	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('piso')&&validarReserva" class="help-block">{{ errors.first('piso') }}</span>

	                    </div>
		            </div>
		        </div>
	        </template>
			<div class="col-sm-12">
				<hr>
	            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubros')&&validarReserva}">
	                <div class="col-sm-12">
	                    <label for="rubros" class="control-label">{{ nameRubro }}</label><br>
						<el-select v-model="reserva.rubros" multiple placeholder="Seleccione" data-vv-name="rubros" v-validate="'required'" style="width:100%;" @change="callbackSelectRubros">
							<el-option
								v-for="item in opcionesRubros"
								:key="item.value"
								:label="item.label"
								:value="item.value">
							</el-option>
						</el-select>
	                </div>
	            </div>
	        </div>
	        <div class="col-sm-12" v-if="opcionesArticulos.length > 0">
	        	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('articulos')&&validarReserva}">
	        		<div class="col-sm-12">
	        			<label for="articulos">Articulos</label><br>
	        			<div class="col-sm-12">
	        				<div class="col-sm-offset-2 col-sm-3">
	        					producto
	        				</div>
	        				<div class="col-sm-2">
	        					precio
	        				</div>
	        				<div class="col-sm-5">
	        					cantidad
	        				</div>
	        			</div>
	        			<hr>
	        			<div v-for="opcion in opcionesArticulos">

		        			<div class="col-sm-12">
		        				<div class="col-sm-2">
		        					<input type="checkbox" name="articulos" v-validate:articulosArray="'required'" 
		        					:value="opcion.id" @change="setOrSpliceArticulo(opcion.id)">
								</div>
								<div class="col-sm-3">
		        					{{  opcion.nombre }}
		        				</div>
		        				<div class="col-sm-2">
		        					$ {{ opcion.precio }}
		        				</div>
								<div class="col-sm-5">
									<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('articulo' + opcion.id)&&validarReserva}">
			        					<input class="form-control" type="number" min="0"
			        						value="0"
			        						:name="'articulo' + opcion.id"
			        						v-validate="ruleValidation(opcion.id) ? 'required|min_value:1' : ''"
			        						v-bind:disabled="isInputDisable(opcion.id)" 
			        						@change="setCantidad(opcion.id, $event)">
			        				</div>
		        				</div>
		        			</div>
		        		</div>
		        		<!-- validacion vee-validation -->
                    	<span v-show="errors.has('articulos') && validarReserva" class="help-block">{{ errors.first('articulos') }}</span>
	        		</div>
	        	</div>
	        </div>
	       <div class="col-sm-12">
	       		<div class="col-sm-12">
	       			<label for="precio_total">Precio: ${{ reserva.precio_total }}
	       			</label>
	       		</div>
	       	</div>
		</form>
	</div>
</template>
<script>
	import auth from './../../../auth.js'
	import vSelect from "vue-select";

	export default {
		props: {
			rubros: {
				required: true,
				type: Array
			},
			articulos: {
				required: true,
				type: Array
			},
			tipo: {
				required: true,
				type: String
			},
			reserva: {
				required: true,
				type: Object
			},
			domicilio: {
				dafault: {
	                calle: null,
	                numero: null,
	                piso: null,
	                localidad_id: null
				},
				required: true
			}
		},
		data(){
			return {
				validarReserva: false,
				localidades: [],
				opcionesRubros: [],
				rubrosSelect: [],
				opcionesArticulos: [],
				showEstadoReserva: false,
				opcionesReservas: [],
		        pickerOptions: {
					disabledDate: function(date){
						const tomorrow = new Date();
						tomorrow.setDate(tomorrow.getDate() + 1);
						return date < tomorrow;
					}
				}
			}
		},
	    beforeMount(){
	    	this.loadOpcions();
	    },
	    mounted(){
	    	this.$events.on('validarFormReserva', () => this.validateBeforeSubmit());
	    },
	    beforeDestroy() {
		    this.$events.$off('validarFormReserva');
		},
		components: {
			vSelect
		},
	    methods: {
	    	validateBeforeSubmit: function() {
		        this.$validator.validateAll().then(() => { 
		                    this.validarReserva = false; 
		                    this.$emit('validado');
		                }).catch(() => {
		                    this.validarReserva = true;
		                });
		        },
	    	callbackSelectRubros: function(val) {
	    		this.opcionesArticulos = [];
	    		var selectableItems = [];
	    		for(var articulo of this.articulos){
	    			for(var rubro of val){
	    				if(articulo.rubro_id == rubro){
	    					selectableItems.push(articulo.id);
	    					this.opcionesArticulos.push(
	    							{id: articulo.id, nombre: articulo.nombre, stock: articulo.stock, precio: articulo.precio});
	    					break;
	    				}
	    			}
	    		}

	    		for(var i = 0; i < this.reserva.articulos.length; i++){
	    			var esta = false;
	    			for(var articulo_id of selectableItems){
	    				if(this.reserva.articulos[i].articulo_id == articulo_id){
	    					esta = true;
			    		}
	    			}
	    			if(!esta){
	    				this.reserva.articulos.splice(i, 1);
	    			}
	    		}

	    		if(this.isRequiredSalon && !this.selectRubroSalon){
		    		for(var rubro of this.rubros){
		    			if( rubro.salon )
		    			{
							this.reserva.rubros.push(rubro.id);
		    				break;
		    			}
		    		}
		    	}
	    	},
	    	setCantidad(articulo_id, e){
	    		for (var i = 0; i < this.reserva.articulos.length; i++) {
	    			if(articulo_id == this.reserva.articulos[i].articulo_id){
	    				this.reserva.articulos[i].cantidad = e.srcElement.value;
	    				break;
	    			}
	    		}
	    		this.calularCoste();
	    	},
	    	getOptions: function(search, loading) {
	            loading(true)
	            this.$http.get('api/localidades/?q='+ search
	                ).then(response => {
	                    this.localidades = response.data.data
	                    loading(false)
	                })
        	},
	    	setOrSpliceArticulo(articulo_id){
	    		var esta = false;
	    		
	    		for (var i = 0; i < this.reserva.articulos.length; i++) {
	    			if(this.reserva.articulos[i].id == articulo_id){
	    				esta = true;
	    				this.reserva.articulos.splice(i, 1);
	    				break
	    			}
	    		}
	    		if(!esta){
	    			this.reserva.articulos.push({articulo_id: articulo_id, cantidad: 0});
	    		} 
	    	},
	    	isInputDisable(articulo_id){

	    		for(var articulo of this.reserva.articulos){
	    			if(articulo.articulo_id == articulo_id)
	    				return false;
	    		}
	    		return true;
	    	},
	    	changeDate(val){
	    		this.showStatus(val)
	    	},
	    	showStatus(date){
	    		this.showEstadoReserva = true;
	    		this.opcionesReservas = [
	    			{
	    				id: 2,
	    				hora_inicio: '12:00',
	    				hora_finalizacion: '14:00',
	    				precio: 400
		    		},
		    		{
		    			id: 3,
	    				hora_inicio: '15:00',
	    				hora_finalizacion: '17:00',
	    				precio: 700
		    		}]
	    	},
	    	loadOpcions(){
	    		var rubrosArticulos = [];
	    		for(var rubro of this.rubros){
	    			if(rubro.servicio || rubro.salon)
	    			{
		    			var option = {value: rubro.id, label: rubro.nombre};
		    			if(rubro.salon){
		    				this.reserva.rubros.push(rubro.id);
		    			}
		    			if(!rubro.servicio && !rubro.salon && rubro.producto)
		    				rubrosArticulos.push(rubro.id);

		    			this.opcionesRubros.push(option);
		    		}
	    		}
	    		for(var articulo of this.articulos){
	    			for(rubro of rubrosArticulos){
	    				if(articulo.rubro_id == rubro.id){
	    					this.opcionesArticulos.push({articulo});
	    					break;
	    				}
	    			}
	    		}
	    	},
	    	ruleValidation(articulo_id){
	    		for(var articulo of this.reserva.articulos){
	    			if(articulo.id == articulo_id)
	    				return true;
	    		}
	    		return false;
	    	},
	    	calularCoste(){
	    		var precie = 0.00;
	    		for (var reservado of this.reserva.articulos) {

	    			for (var articulo of this.articulos) {
		    			if(reservado.articulo_id == articulo.id){
		    				console.log(articulo);
		    				precie = precie + (articulo.precio * reservado.cantidad);
		    				break;
		    			}
	    			}
	    		}
	    		if(this.reserva.horario_id != ''){
	    			for (var horario of this.opcionesReservas) {
	    				if(horario.id == this.reserva.horario_id){
	    					precie = precie + horario.precio;
	    					break;
	    				}
	    			}
	    		}
	    		this.reserva.precio_total = precie;
	    	}
	    },
	    computed:{
	    	articulosArray(){
	    		return this.reserva.articulos;
	    	},
	    	nameRubro(){
	    		for(var rubro of this.rubros){
	    			if(rubro.servicio)
	    				return 'Servicios';
	    		}
	    		return 'Productos';
	    	},
	    	requiredDate(){
	    		for(var rubro of this.rubros){
	    			if(rubro.servicio || rubro.salon)
	    				return true;
	    		}
	    		return false;
	    	},
	    	isRequiredDomicilio(){
	    		for(var rubro of this.rubros){
	    			if( rubro.salon )
	    				return false;
	    		}
	    		return true;
	    	},
	    	isRequiredSalon(){
	    		for(var rubro of this.rubros){
	    			if( rubro.salon )
	    				return true;
	    		}
	    		return false;
	    	},
	    	selectRubroSalon(){
	    		var rubro_id = null;
	    		for(var rubro of this.rubros){
	    			if( rubro.salon ){
	    				rubro_id = rubro.id;
	    				break;
	    			}
	    		}
	    		for(var rubro of this.reserva.rubros){
	    			if(rubro == rubro_id)
	    				return true
	    		}
	    		return false;
	    	}
	    }
	}
</script>