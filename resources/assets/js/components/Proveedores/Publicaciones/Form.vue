<template>
        <form role="form" enctype="multipart/form-data">
            <div class="col-sm-12">
                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('titulo')&&validarPublicacion}">
                    <div class="col-sm-12">
                        <label for="titulo" class="control-label">Titulo (*) </label>
                        <input 
                        	name="titulo"  
                        	v-validate:proveedor.nombre="'required|min:5'" 
                        	type="text" class="form-control" 
                        	v-model="publicacion.titulo" 
                        	placeholder="Titulo">
                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('titulo')&&validarPublicacion" class="help-block">{{ errors.first('titulo') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.titulo">
                            <div v-for="msj in errorsApi.titulo">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('rubros')&&validarPublicacion}">
                    <div class="col-sm-8">
                        <label class="control-label">Rubros</label><br>
                        <select
                            class="form-control"
                            v-model="publicacion.rubros"
                            name= "rubros"
                            data-vv-name="rubros"
                            @change="setValueRubro" 
                            v-validate="'required'"
                            placeholder="Seleccione un Rubro" >
                            <option disabled value="">Seleccione un Rubro</option>
                            <option 
                                v-for="option in rubros" 
                                v-bind:value="option.value">
                                {{ option.text }}
                            </option>

                        </select>

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('rubros')&&validarPublicacion" class="help-block">{{ errors.first('rubros') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.user_id">
                            <div v-for="msj in errorsApi.user_id">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="box-header">
                        <div>
                            <button class="btn btn-block btn-primary btn-sm"
                                @click="showNew = true">
                                Agregar Nueva Actividad
                            </button>
                        </div>
                    </div>

                    <div v-if="showNew" class="modal" role="dialog" :style="{ display : showNew  ? 'block' : 'none' }">
                        <div class="modal-dialog">
                        <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="closeModal()">&times;</button>
                                    <h4 class="modal-title">Crear Rubro</h4>
                                </div>
                                <new-rubro></new-rubro>
                            </div>
                        </div>    
                    </div>
                </div> 
                <div v-if="publicacion.rubros">
                    <div v-for="etiqueta in publicacion.rubros">
                        <span class="tag is-primary">
                        {{etiqueta}}
                          <button class="delete is-small"></button>
                        </span>
                     </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('oferta')&&validarPublicacion}">
                    <div class="col-sm-12">
                        <label for="oferta" class="control-label">Oferta (*) </label>
                        <textarea
                        	name="oferta" 
                        	type="text"
                        	style="min-height:100px;" 
                        	v-model="publicacion.oferta" 
                        	class="form-control">
                        </textarea>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fecha')&&validarPublicacion}">
                    <div class="col-sm-12">
                        <label for="fecha" class="control-label">Fecha de Finalización</label>
                        <input
                            name="fecha" 
                            type="date"
                            v-model="publicacion.fecha_finalizacion" 
                            class="form-control">
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('descripcion')&&validarPublicacion}">
                    <div class="col-sm-12">
                        <label for="descripcion" class="control-label">Descripci&oacute;n (*)</label>
                        <textarea 
                        	name="descripcion"  
                        	v-validate="'required|min:15'" 
                        	class="form-control" 
                        	style="min-height:100px;" 
                        	v-model="publicacion.descripcion" placeholder="Ingrese una Descripcion">
                        </textarea>

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('descripcion')&&validarPublicacion" class="help-block">{{ errors.first('descripcion') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.descripcion">
                            <div v-for="msj in errorsApi.descripcion">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('fotos')&&validarPublicacion}">
                    <div class="col-sm-12">
                        <label for="fotos" class="control-label">Fotos <i class="fa fa-file-image-o"></i></label><br>
                        <input v-if="nuevo"
                            type="file" 
                            v-validate.reject="'required|ext:jpg,png,jpeg|size:4096'" 
                            @change="onFilesChange" 
                            name="fotos[]"
                            multiple>

                        <input v-else
                            type="file" 
                            v-validate.reject="'ext:jpg,png,jpeg|size:4096'" 
                            @change="onFilesChange" 
                            name="fotos[]"
                            multiple>

                        <!-- validacion vee-validation -->
                        <span v-show="errors.has('fotos')&&validarPublicacion" class="help-block">{{ errors.first('fotos') }}</span>
                        <!-- validacion api-->
                        <div class="text-red" v-if="errorsApi.fotos">
                            <div v-for="msj in errorsApi.fotos">
                                <p>{{ msj }}</p>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </form>
</template>
<script>
	import auth from '../../../auth.js'
	import vSelect from "vue-select";
    import NewRubro from '../RubrosDetalle/NewRubro.vue';

	export default {
		props: {
			publicacion: {
				type: Object,
				required: true
			},
			validarPublicacion: {
				type: Boolean,
				required: true
			},
			errorsApi: {
				required: true
			},
            nuevo: {
                type: Boolean,
                required: true
            }
		},
		data() {
			return {
				auth: auth,
				rubros: [],
                showNew: false
			}
		},
        created: function() {
            this.getOptions();
        },
		mounted(){
			this.$events.on("validarFormPublicacion", () => this.validateSubmit())
		},
		components: {vSelect, NewRubro},
		methods: {
			getOptions: function() {
	            this.$http.get('api/proveedor/'+this.auth.user.profile.id+'/rubros/search'
	                ).then(response => {
	                    let options = response.data
	                    for (let option of options){
                        this.rubros.push({ text: option.rubro.nombre, value: option.id })
                         }
	                })
	        },
	        onFilesChange(e) {
	            var files = e.target.files || e.dataTransfer.files;
	            let fotos = []
	            if (!files.length){
	                return;
				}
				for (var i = 0; i < files.length; i++) {
					var file = files[i];
					var reader = new FileReader();
					reader.onload = (e) => {
						fotos.push(e.target.result);
					}
					reader.readAsDataURL(file);
				}
				this.publicacion.fotos = fotos;
	        },
	        validateSubmit: function() {
	        	console.log('validator form producto: ')
                this.$validator.validateAll().then(() => {
                    this.setValueRubro();
                    if(this.nuevo){
                        console.log('valido nuevo')
                        this.$emit('validadoNewPublicacion'); 
                    }
                    else
                    {
                        console.log('valido edita')
                        this.$emit('validadoEditPublicacion'); 
                    }

                    this.$emit('update:validarPublicacion', false);             
                }).catch(() => {
                	this.$emit('update:validarPublicacion', true);
                });
	        },
	        setValueRubro: function(){
	        	var values = [];
                console.log(values);
	        	for(let i = 0; i < this.publicacion.rubros.length; i++){
	        		values.push(this.publicacion.rubros[i].value)
	        	}
	        	this.publicacion.rubros = values;
                console.log(this.publicacion.rubros);
                console.log(this.publicacion.rubros.length);
	        }
	    }
	}
</script>