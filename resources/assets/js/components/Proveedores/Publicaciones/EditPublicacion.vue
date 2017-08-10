<template>
	<div class="default-content">
        <div class="content">
        	<div class="box box-primary">
        		<div class="box-header with-border">
        			<h3 class="box-title">Editar Publicaci&oacute;n</h3>
        		</div>
        		<div class="box box-body">
		        	<form-publicacion v-if="showForm"
		        		:publicacion="publicacion" 
		        		:nuevo="false"
		        		:validarPublicacion="validarPublicacion"
		        		@validadoEditPublicacion="sendEditForm()"
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
		        		<button class="btn btn-primary" @click="validateBeforeSubmit()">Ediat Publicaci&oacute;n</button>
		        	</div>
		        </div>
        	</div>
        </div>
    </div>
</template>
<script>
	import FormPublicacion from './Form';
	import router from './../../../routes.js'

	export default {
		data() {
			return {
				publicacion: {
					type: Object
				},
				validarPublicacion: false,
				errorsApi:[],
				showForm: false
			}
		},
		beforeMount: function(){
			this.getPublicacion();
		},
		mounted(){
			//this.$events.on("validadoFormPublicacion", () => this.sendEditForm());
		},
		components: {
			FormPublicacion
		},
		methods: {
			validateBeforeSubmit: function() {                 
	            this.validarPublicacion = true;
	            this.$events.fire('validarFormPublicacion')
	        },
	        sendEditForm(){
	            this.$http.patch(
	                	'api/publicacion/'+this.$route.params.publicacionId,
	                {
	                    titulo: this.publicacion.titulo,
	                    descripcion: this.publicacion.descripcion,
	                    rubros_detalle_id: this.publicacion.rubros_detalle_id,
	                    oferta: this.publicacion.oferta,
	                    fotos: this.publicacion.fotos

	                })
	                .then(response => {
	                    this.$toast.success({
	                        title:'¡Publiacion Creada!',
	                        message:'Se editado correctamente su publicación. :D'
	                    });
	                    this.goBack();
	                }, response => {
	                    this.validarPublicacion= false;
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se han podido edita su publicación. :('
	                    });
	                    if(response.status === 422)
	                    {
	                        this.errorsApi = response.body;
	                    }
	                })
	        },
	        getPublicacion: function(){
	        	this.$http.get('api/publicacion/'+this.$route.params.publicacionId)
	        	.then(response =>{
		        		this.publicacion = response.data.publicacion
		        		this.showForm = true;
		        	}, response => {
	                    if(response.status === 404){
	                        router.go(-1)
		                    this.$toast.error({
		                        title:'¡Error!',
		                        message:'No se han obtener sus rubros. :('
		                    });
	                    }
		        	});
	        },
	        goBack: function(){
	            router.go(-1)
	        }
		}
	}
</script>