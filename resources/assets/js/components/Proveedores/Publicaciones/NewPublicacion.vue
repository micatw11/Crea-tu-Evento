<template>
	<div class="default-content">
        <div class="content">
        	<div class="box box-primary">
        		<div class="box-header with-border">
        			<h3 class="box-title">Crear Publicaci&oacute;n</h3>
        		</div>
        		<div class="box box-body">
		        	<form-publicacion 
		        		:publicacion="publicacion" 
		        		:validarPublicacion="validarPublicacion"
		        		@update:validarPublicacion="val => validarPublicacion = val"
		        		:errorsApi="errorsApi">
		        	</form-publicacion>
		        </div>
		        <div class="box box-footer">
		        	<button class="btn btn-primary" @click="validateBeforeSubmit()">Crear Publicaci&oacute;n</button>
		        </div>
        	</div>
        </div>
    </div>
</template>
<script>
	import FormPublicacion from './Form';

	export default {
		data() {
			return {
				publicacion: {
					type: Object
				},
				validarPublicacion: false,
				errorsApi:[]
			}
		},
		mounted(){
			this.$events.on("validadoFormPublicacion", () => this.sendNewForm());
		},
		components: {
			FormPublicacion
		},
		methods: {
			validateBeforeSubmit: function() {                 
	            this.validarPublicacion = true;
	            this.$events.fire('validarFormPublicacion')
	        },
	        sendNewForm(){
	            this.$http.post(
	                'api/publicacion/', 
	                {
	                    titulo: this.publicacion.titulo,
	                    descripcion: this.publicacion.descripcion,
	                    rubros: this.publicacion.rubros,
	                    oferta: this.publicacion.oferta,
	                    fotos: this.publicacion.fotos

	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Publiacion Creada!',
	                        message:'Se creado correctamente su publicación. :D'
	                    });
	                    this.resetForm();
	                }, response => {
	                    this.validarPublicacion= false;
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido crear su publicación. :('
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                })
	        },
	        resetForm(){
	        	this.publicacion = null;
	        }
		}
	}
</script>