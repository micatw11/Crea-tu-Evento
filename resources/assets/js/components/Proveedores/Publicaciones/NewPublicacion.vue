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
				errorsApi:[],
				id: null,
				listPath : [{route: '/', name: 'Home'}, {route: '/publicacion/new', name: 'Nueva Publicación'}],
			}
		},
		mounted(){
			this.resetForm();
			this.$events.fire('changePath', this.listPath, 'Nueva Publicación');
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
	                    fotos: this.publicacion.fotos,
	                    caracteristicas: this.publicacion.caracteristicas
	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Publiacion Creada!',
	                        message:'Se creado correctamente su publicación. :D'
	                    });
	                    this.$events.fire('changePath', this.listPath, 'Ver Publicacion');
	                    this.id = response.data.id;
						route.push('/publicacion/'+ this.id);
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
	        goBack: function(){
	            route.go(-1)
	        }
		}
	}
</script>