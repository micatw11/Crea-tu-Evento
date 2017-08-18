<template>
	<div class="default-content">
		<section class="content">
	      <!-- Default box -->
	      <div class="box">
	        <div class="box-header with-border">
	          <h3 class="box-title">{{publicacion.titulo}}</h3>
	          <div class="box-tools pull-right">
	            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
	              <i class="fa fa-minus"></i></button>
	            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
	              <i class="fa fa-times"></i></button>
	          </div>
	        </div>
	        <div class="box-body">

			      <hr>
			      <h4>{{publicacion.oferta}}</h4>
		          <br>
		          <div class="row">
		          		<div class="col-xs-12">
<div id="myCarousel" class="carousel slide" data-ride="carousel" style="max-width:400px;max-height:400;">
  <!-- Wrapper for slides -->
  <div class="carousel-inner">

    <div v-for="(img, index) in publicacion.fotos" class="item" v-bind:class="{'active': (activeId==index)}">
      <img :src="'/storage/proveedores/publicaciones/'+img.nombre" class="img-responsive" style="max-width:400px;max-height:400px;">
    </div>
  </div>

  <!-- Left and right controls -->
  <a @click="setActiveItem('prev')" class="left carousel-control">
    <span class="glyphicon glyphicon-chevron-left"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a @click="setActiveItem('next')" class="right carousel-control">
    <span class="glyphicon glyphicon-chevron-right"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
		          		</div>
				       <div class="col-xs-12 text-center box-group" id="accordion">
				          		<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
			                        Ver Datos Proveedor
			                      </a>
				      </div>
				      <div id="collapseOne" class="panel-collapse collapse in">
		                   	 <div class="box-body">
		                       <!--vista proveedor-->
		                       proveedor
		                    </div>
	                  </div>
		          </div>
		          <hr>
		          <div class="nav-tabs-custom">
	               		<div class="col-sm-12" v-html= 'publicacion.descripcion'></div>
	               </div>
	        </div>
	        <!-- /.box-body -->
	        <div class="box-footer">
	          Footer
	        </div>
	        <!-- /.box-footer-->
	      </div>
	      <!-- /.box -->

	    </section>
	</div>
</template>
<script>
	import route from './../../../routes.js'
	import auth from './../../../auth.js'
	export default {
		
		data() {
			return {
				publicacion: [],
				productoId: null,
				listPath : [{route: '/', name: 'Home'}, {route: '/usuario/'+auth.user.profile.id +'/perfil', name: 'Perfil'}],
				auth: auth,
				activeId: 0
			}
		},
		mounted(){
			this.getProductos();
		},
		methods: {
			getProductos: function(){
	            this.$http.get('api/publicacion/'+this.$route.params.publicacionId )
	                .then(response => {
	                    this.publicacion = response.data.publicacion
	                }, response => {
	                    this.$toast.error({
	                        title:'¡Error!',
	                        message:'No se ha podido cargar la publicacion. :('
	                    });

	                })
			},
			verProveedor(id){
				this.$events.fire('changePath', this.listPath, 'Ver Proveedor');
				route.push('/proveedor/'+id);
			},
		    setActiveItem(action) {
		    	if(this.publicacion.fotos != 'undefined')
		    	{
			        if(action === 'next'){
			        	if(this.activeId < (this.publicacion.fotos.length - 1)){
	 						this.activeId = this.activeId + 1
			        	}
	 					else
	 					{
	 						this.activeId = 0
	 					}
			        }
			        if(action === 'prev'){
			        	if(this.activeId > 0){
	 						this.activeId = this.activeId - 1;
			        	}
			        	else
			        	{
			        		this.activeId = (this.publicacion.fotos.length - 1)
			        	}
			        }
			    }
			    
		    }
		}
	}
</script>