<template>
	<div class="default-content">
        <section class="content">
            <div class="row">
        		<div class="col-xs-12">
		        	<div class="box box-primary">
		        		<div class="box-header with-border">
		        			<h3 class="box-title">Crear Publicaci&oacute;n</h3>
		        		</div>
		        		<div class="box box-body">
				        	<form-publicacion 
				        		:publicacion="publicacion"
				        		:nuevo="true"
				        		:validarPublicacion="validarPublicacion"
				        		@validadoNewPublicacion="sendNewForm()"
				        		@update:validarPublicacion="val => validarPublicacion = val"
				        		:errorsApi="errorsApi">
				        	</form-publicacion>
				        </div>
				        <div class="box box-footer">
				        	<div style="text-align:center;">
					            <button @click="goBack()" class="btn btn-default">
			                        <i class="glyphicon glyphicon-chevron-left"></i>
			                        Atras
			                    </button>
				        		<button class="btn btn-primary" @click="validateBeforeSubmit()">Crear Publicaci&oacute;n</button>
				        	</div>
				        </div>
		        	</div>
		        </div>
		    </div>
        </section>
    </div>
</template>
<script>
	import FormPublicacion from './Form';
	import route from './../../../routes.js';

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
			this.resetForm();
			//this.$events.on("validadoFormPublicacion", () => this.sendNewForm());
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
	                    rubros_detalle_id: this.publicacion.rubros_detalle_id,
	                    oferta: this.publicacion.oferta,
	                    fecha_finalizacion: this.fecha_finalizacion,
	                    fotos: this.publicacion.fotos

	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Publiacion Creada!',
	                        message:'Se creado correctamente su publicación. :D'
	                    });
	                    this.resetForm();
	                    this.goBack();
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
	        	this.publicacion = {
	        		'titulo':'',
	        		'descripcion': '',
	        		'oferta': '',
	        		'fotos': null,
	        		'rubros_detalle_id': '',
	        		'fecha_finalizacion': null

	        	}
	        },
	        goBack(){
	            route.go(-1)
	        }
		}
	}
</script>