<template>
	<div>
		<form role="form">
			<template v-if="requiredDate">
				<div class="col-sm-4">
		            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validarPresupuesto}">
		                <div class="col-sm-12">
		                    <label for="fecha" class="control-label">Fecha del evento </label><br>
		                    <el-date-picker
						    	v-model="presupuesto.fecha"
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
		        		<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('horario')&&validarPresupuesto}">
			        		<div class="col-sm-12">
			        			<label for="horario" class="control-label">Horarios disponibles </label><br>
				        		<el-select v-model="presupuesto.horario_id" placeholder="Seleccione un horario" 
				        			data-vv-name="horario" v-validate="'required'" style="width:100%;">
									<el-option
										v-for="item in opcionesReservas"
										:key="item.id"
										:label="'Desde ' + item.hora_inicio + ' hs Hasta ' + item.hora_finalizacion+ ' hs'"
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
	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('localidad')&&validarPresupuesto}">
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
	                        <span v-show="errors.has('localidad')&&validarPresupuesto" class="help-block">{{ errors.first('localidad') }}</span>
	                    </div>
	                </div>

	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('calle')&&validarPresupuesto}">
	                    <div class="col-sm-8">
	                        <label for="inputCalle" class="control-label">Direccion </label><br>
	                        <input name="calle"  v-validate:domicilio.calle="'required|min:4'" type="text" class="form-control" v-model="domicilio.calle" placeholder="Calle">
	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('calle')&&validarPresupuesto" class="help-block">{{ errors.first('calle') }}</span>
	                    </div>
	                </div>
	                
	                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has(('numero')||('piso'))&&validarPresupuesto}">
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
	                            <span v-show="errors.has('numero')&&validarPresupuesto" class="help-block">{{ errors.first('numero') }}</span>
	                    </div>

	                    <div class="col-sm-2">
	                        
	                        <label for="inputPiso" class="control-label">Dpto. </label><br>
	                        <input name="piso" type="text" v-model="domicilio.piso" placeholder="Piso" class="form-control col-sm-12">

	                        <!-- validacion vee-validation -->
	                        <span v-show="errors.has('piso')&&validarPresupuesto" class="help-block">{{ errors.first('piso') }}</span>

	                    </div>
		            </div>
		        </div>
	        </template>
			<div class="col-sm-12">
				<hr>
	            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubros')&&validarPresupuesto}">
	                <div class="col-sm-12">
	                    <label for="rubros" class="control-label">{{ nameRubro }}</label><br>
						<el-select v-model="presupuesto.rubros" multiple placeholder="Seleccione" data-vv-name="rubros" v-validate="'required'" style="width:100%;" @change="callbackSelectRubros">
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
	        	<hr>
	        	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('productos')&&validarPresupuesto}">
	        		<div class="col-sm-12">
	        			<label for="productos">Productos</label><br>	        			
	        			<div v-for="opcion in opcionesArticulos">

		        			<div class="col-sm-4 col-xs-12">
		        				<div class="col-sm-2 col-xs-2">
		        					<input type="checkbox" name="productos" v-validate:articulosArray="'required'" 
		        					:value="opcion.id" @change="setOrSpliceArticulo(opcion.id)">
								</div>
								<div class="col-sm-10 col-xs-10">
		        					{{  opcion.nombre }}
		        				</div>
		        			</div>
		        		</div>
		        		<!-- validacion vee-validation -->
                    	<span v-show="errors.has('productos') && validarPresupuesto" class="help-block">{{ errors.first('productos') }}</span>
	        		</div>
	        	</div>
	        </div>
	        <div class="col-sm-12">
	        	<hr>
	        	<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('comentario')&&validarPresupuesto}">
	        		<div class="col-sm-12">
	        			<label for="comentario">Comentario</label><br>	
	        			<textarea class="form-control" data-vv-name="comentario" v-validate="'required|min:10|max:400'" v-model="presupuesto.comentario" placeholder="Ingrese aqui un comentario.">
	        				
	        			</textarea>
						<span v-show="errors.has('comentario') && validarPresupuesto" class="help-block">{{ errors.first('comentario') }}</span>
	        		</div>
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
			presupuesto: {
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
				validarPresupuesto: false,
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
	    	this.$events.on('validarFormPresupuesto', () => this.validateBeforeSubmit());
	    },
	    beforeDestroy() {
		    this.$events.$off('validarFormPresupuesto');
		},
		components: {
			vSelect
		},
	    methods: {
	    	validateBeforeSubmit: function() {
		        this.$validator.validateAll().then(() => { 
		                    this.validarPresupuesto = false; 
		                    this.$emit('validado');
		                }).catch(() => {
		                    this.validarPresupuesto = true;
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

	    		for(var i = 0; i < this.presupuesto.articulos.length; i++){
	    			var esta = false;
	    			for(var articulo_id of selectableItems){
	    				if(this.presupuesto.articulos[i].articulo_id == articulo_id){
	    					esta = true;
			    		}
	    			}
	    			if(!esta){
	    				this.presupuesto.articulos.splice(i, 1);
	    			}
	    		}

	    		if(this.isRequiredSalon && !this.selectRubroSalon){
		    		for(var rubro of this.rubros){
		    			if( rubro.salon )
		    			{
							this.presupuesto.rubros.push(rubro.id);
		    				break;
		    			}
		    		}
		    	}
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
	    		
	    		for (var i = 0; i < this.presupuesto.articulos.length; i++) {
	    			if(this.presupuesto.articulos[i].id == articulo_id){
	    				esta = true;
	    				this.presupuesto.articulos.splice(i, 1);
	    				break
	    			}
	    		}
	    		if(!esta){
	    			this.presupuesto.articulos.push({articulo_id: articulo_id, cantidad: 0});
	    		} 
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
		    				this.presupuesto.rubros.push(rubro.id);
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
	    	}
	    },
	    computed:{
	    	articulosArray(){
	    		return this.presupuesto.articulos;
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
	    		for(var rubro of this.presupuesto.rubros){
	    			if(rubro == rubro_id)
	    				return true
	    		}
	    		return false;
	    	}
	    }
	}
</script>