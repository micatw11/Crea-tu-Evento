<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-default collapsed-box">
                         <!-- search form -->
                        <form v-on:submit.prevent="searchPublicacion()" class="sidebar-form" >
                            <div class="input-group">
                                <input type="text" name="q" class="form-control" v-model="q" placeholder="Busqueda">
                                <span class="input-group-btn">
                                    <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                                    <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>
                        <!-- /.search form -->
                        <div class="box-header with-border">
                            <h3 class="box-title">Filtro</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                            <!-- /.box-tools -->
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body" style="display: none;">
                            <form role="form">
                                <div class="col-sm-12">
                                    <div class="col-sm-4">
                                        <div class="form-group has-feedback">
                                            <div class="col-sm-12">
                                                <v-select
                                                    :on-search="getLocalidades" 
                                                    :options="localidades"
                                                    v-model="localidad_id" 
                                                    placeholder="Ubicación">
                                                </v-select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-8">
                                        <div class="form-group has-feedback">
                                            <div class="col-sm-4">
                                                <select 
                                                    class="form-control"
                                                    v-model="categoria_id"
                                                    @change="changeCategory()">

                                                    <option value="" disabled>Categoria</option>
                                                    <option v-for="option in categorias" v-bind:value="option.value">{{ option.text }}</option>

                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select 
                                                    class="form-control" 
                                                    v-bind:disabled="categoria_id == '' || subcategorias.length == 0"
                                                    v-model="subcategoria_id" 
                                                    @change="changeSubcategory()">

                                                    <option value="" disabled>Subcategoria</option>
                                                    <option v-for="option in subcategorias" v-bind:value="option.value">{{ option.text }}</option>

                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <select 
                                                    class="form-control" 
                                                    v-bind:disabled="subcategoria_id == '' || rubros.length == 0"
                                                    v-model="rubro_id">
                                                    <option value="" disabled>Rubro</option>
                                                    <option v-for="option in rubros" v-bind:value="option.value">{{ option.text }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer" style="display: none;">
                            <div class="pull-right">
                                <button class="btn btn-primary" @click="searchPublicacion()">Aplicar Filtro</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <search
            :publicaciones="publicaciones">
            </search> 
        </section>
    </div>
</template>

<script>
    import VSelect from "vue-select";
    import route from '../routes.js';
    import Search from './Proveedores/Publicaciones/SearchPublicacion.vue';


    export default {
        data(){
            return {
                titlePath: 'Home',
                listaPath: [{route: '/', name: 'Home'}],
                publicaciones : [],
                localidades: [],
                localidad_id: null,
                categoria_id: '',
                categorias: [],
                subcategoria_id: '',
                subcategorias: [],
                rubro_id: '',
                rubros: [],
                q: ''
            }
        },
        mounted() {
            this.$events.fire('changePath', this.listaPath, this.titlePath);
            //this.$events.on('search', value => this.searchPublication(value));
        },
        beforeMount() {
            this.searchPublication('');
            this.getCategorias();
        },
        methods: {
            searchPublication(filter){
                let filtro = ' '
                if (filter!= ' '){
                     let localidad= this.localidad_id==null? '':this.localidad_id.value  

                    filtro = 'api/publicacion?filter='+ filter +'&with_category='+this.categoria_id+'&with_subcategory='+this.subcategoria_id+'&with_localidad='+localidad+'&with_denomination='+this.rubro_id
                console.log(filtro)
                }else{
                    filtro = 'api/publicacion'
                }
                this.$http.get(filtro)
                .then(response => {
                    this.publicaciones = response.data.publicaciones.data;
                    console.log(this.publicaciones)
                }, response => {

                })
            },
            getLocalidades: function(search, loading) {
                loading(true)
                this.$http.get('api/localidades/?q='+ search
                    ).then(response => {
                        this.localidades = response.data.data
                        loading(false)
                    })
            },
            getCategorias: function(){
                this.$http.get('api/categoria/')
                    .then(response => {
                        let data = response.data.data
                        for (let categoria of data){
                            this.categorias.push({ value: categoria.id, text: categoria.nombre });
                        }
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se han podido cargar opciones de filtrado. :('
                        });
                    })
            },
            changeCategory: function(){
                this.subcategorias = [];
                this.subcategoria_id = "";
                this.$http.get('api/categoria/' +this.categoria_id
                    ).then(response => {
                        let data = response.data.data
                        for (let subcategoria of data.subcategorias){
                            this.subcategorias.push({ text: subcategoria.nombre, value: subcategoria.id });
                        }
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se han podido cargar opciones de filtrado. :('
                        });
                    })
            },
            changeSubcategory: function(){
                this.rubros = [];
                this.rubro_id= '';
                this.$http.get('api/rubros/'+ this.subcategoria_id)
                .then(response => {
                    let options = response.data
                    for (let rubros of options){
                        this.rubros.push({ text: rubros.nombre, value: rubros.id })
                    }
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar opciones de filtrado. :('
                    });
                })
            },
            searchPublicacion: function(){
            if(route.path !== '/' && this.q !== '' )
                route.push('/')
            //this.$events.fire('search', this.q);
                this.searchPublication(this.q)
            }
        },
        components: {
            VSelect, Search
        }
    }
</script>
