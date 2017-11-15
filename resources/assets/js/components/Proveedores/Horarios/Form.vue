<template>

	<div class="row">
		<div v-if="nuevo">
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('dia')&&validarHorario}" style=" text-align: center;" data-toggle="buttons">
				 <label v-for="item in dias" class="btn bg-purple margin" @click="AddDia(item.id)">
				    <input type="checkbox" autocomplete="off"> {{item.nombre}}
				  </label>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('dia')&&validarHorario" class="help-block">{{ errors.first('dia') }}</span>
        	</div>
		</div>
		<div v-else>
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('dia')&&validarHorario}" style=" text-align: center;" data-toggle="buttons">
				     <h4>{{horario.dia.toUpperCase()}}</h4>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('dia')&&validarHorario" class="help-block">{{ errors.first('dia') }}</span>
        	</div>
		</div>
		<div class="col-sm-3 col-sm-offset-1">
			<label>Hora Inicio</label><br>
			<el-time-select placeholder="Desde" style="width: 100%;" v-model="horario.hora_inicio" :picker-options="{
		      start: '01:00',
		      step: '00:30',
		      end: '24:30'
		    }">
		  </el-time-select>
		  </div>
		  <div class="col-sm-3">
		  <label>Hora Fin</label><br>
		  <el-time-select placeholder="Hasta"  style="width: 100%;" v-model="horario.hora_fin" :picker-options="{
		      start: '01:30',
		      step: '00:30',
		      end: '24:30',
		      minTime: horario.hora_inicio
		    }"></el-time-select>
		</div>
		<div class="col-sm-4 col-xs-4">
			<label>Precio</label><br>
			<div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('precio')&&validarHorario}">
				<vue-numeric currency="$" separator="," v-bind:precision="2" v-model="horario.precio" v-validate="'required|min_value:1'" data-vv-name="precio"></vue-numeric>
				<!-- validacion vee-validation -->
	            <span v-show="errors.has('precio')&&validarHorario" class="help-block">{{ errors.first('precio') }}</span>
	        </div>
		</div>
	</div>

</template>

<script>
	import ElTimeSelect from 'element-ui/lib/time-select.js'

	export default {
		props: {
			horario:{
				required: true,
				type: Object
			},
			nuevo:{
				required: true	
			}
		},
		data(){
			return {
				optionsRubros:[],
				validarHorario: false,
                dias: [],
                showFormH: false,
                diasSelect: [],
			}
		},
		beforeMount(){
			this.cargarDias();
		},
		mounted(){
			this.$events.on("validarFormHorario", () => this.validateSubmit())
			this.$events.on('showFormH', () => this.showFormH = true);
		},
		components: {ElTimeSelect},
		methods:{
	        validateSubmit: function() {
	            this.$validator.validateAll().then((result) => {
	                if (result) {
	                    this.validarHorario = false; 
	                    this.horario.dias = this.diasSelect
	                    this.$emit('validoFormHorario'); 
	                } else {
	                	this.validarHorario = true;
	                }
	                return;
                }).catch(() => {
                    
                });
	        },
	        AddDia(dia){
	        	for (var i = 0; i < this.diasSelect.length; i++) {
	        		if (dia == this.diasSelect[i]){
	        			this.diasSelect.splice(i, 1)
	        			return
	        		}
	        	}
   	        	this.diasSelect.push(dia)
	        },
	        cargarDias(){
	        	this.dias.push({id: 'domingo',nombre: 'Do'},
	        					{id:'lunes',nombre:'Lu'},
	        					{id:'martes',nombre:'Ma'},
	        					{id:'miercoles',nombre:'Mi'},
	        					{id:'jueves',nombre:'Ju'},
	        					{id:'viernes',nombre:'Vi'},
	        					{id:'sabado',nombre:'Sa'})
	        }
		}
	}
</script>