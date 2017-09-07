<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <form v-on:submit.prevent="searchPublicacion()" role="form" >
                        <div class="input-group stylish-input-group">
                            <input type="text" name="q" class="form-control" v-model="q" placeholder="Busqueda">
                            <span class="input-group-addon">
                                <button type="submit" name="search" id="search-btn">
                                <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-xs-12">&nbsp;</div>
                <div class="col-xs-12">
                    <div class="box box-default collapsed-box">
                         <!-- search form -->
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

                                                    <option value="">Categoria</option>
                                                    <option disabled>────────────</option>
                                                    <option v-for="option in categorias" v-bind:value="option.value">{{ option.text }}</option>

                                                </select>
                                            </div>
                                            <div class="col-sm-4" v-if="categoria_id != ''">
                                                <select 
                                                    class="form-control" 
                                                    v-bind:disabled="categoria_id == '' || subcategorias.length == 0"
                                                    v-model="subcategoria_id" 
                                                    @change="changeSubcategory()">

                                                    <option value="">Subcategoria</option>
                                                    <option disabled>────────────</option>
                                                    <option v-for="option in subcategorias" v-bind:value="option.value">{{ option.text }}</option>

                                                </select>
                                            </div>
                                            <div class="col-sm-4" v-if="subcategoria_id != ''">
                                                <select 
                                                    class="form-control" 
                                                    v-bind:disabled="subcategoria_id == '' || rubros.length == 0"
                                                    v-model="rubro_id">
                                                    <option value="">Rubro</option>
                                                    <option disabled>────────────</option>
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
            <div class="box">
                <div class="box-body">
                    <search
                        :publicaciones="publicaciones">
                    </search>  
                </div>
                <div class="box-footer">
                    <pagination-home
                        ref="paginationH"
                        :current-page="pageOne.current_page"
                        :items-per-page="pageOne.per_page"
                        :total-pages="pageOne.last_page"
                        :total-items="pageOne.total"
                        @page-changed="pageOneChanged">
                    </pagination-home> 
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import VSelect from "vue-select";
    import route from '../routes.js';
    import Search from './Proveedores/Publicaciones/Index.vue';
    Vue.component('pagination-home', require('./Plugins/pagination/pagination-v1.vue'));

    export default {
        data(){
            return {
                api: 'api/publicacion',
                pageOne: {
                    total:0,
                    per_page:10,
                    current_page: 1,
                    last_page:1,
                    next_page_url:null,
                    prev_page_url:null,
                    from:0,
                    to:0 
                },
                titlePath: 'Inicio',
                listaPath: [{route: '/', name: 'Inicio'}],
                publicaciones : [],
                localidades: [],
                localidad_id: null,
                categoria_id: '',
                categorias: [],
                subcategoria_id: '',
                subcategorias: [],
                rubro_id: '',
                rubros: [],
                q: '',
                nombre: false
            }
        },
        beforeMount() {
            this.getCategorias();
            this.$nextTick( function() {
                this.$events.fire('changePath',[{route: '/', name: 'Inicio'}], 'Inicio');
            });
        },
        mounted() {
            this.queryPath();
            console.log('montado')
            console.log(this.q)
            this.searchPublicacion();
            this.categoria_id = '';
            this.subcategoria_id = '';
            this.rubro_id = '';
        },
        components: {
            VSelect, Search
        },
        methods: {
            /** 
            * Armar las consultas de publicaciones
            * para luego realizarlas al servidor. 
            * 
            * @managerSearch 
            * @ Param {String} filter Argumento 1 
            */
            managerSearch(filter){

                let filtro = this.api+'?filter='+ filter; 

                if(this.categoria_id != undefined && this.categoria_id != '')
                    filtro = filtro + '&with_category='+this.categoria_id;
                if(this.subcategoria_id != undefined && this.subcategoria_id != '')
                    filtro = filtro + '&with_subcategory='+this.subcategoria_id;
                if(this.rubro_id  != undefined && this.rubro_id  != '') 
                    filtro = filtro + '&with_denomination='+this.rubro_id;
                if(this.localidad_id !=  null)
                    filtro = filtro + '&with_localidad='+this.localidad_id.value ;

                filtro = filtro + '&page='+this.pageOne.current_page+'&per_page='+this.pageOne.per_page;

                this.$http.get(filtro).then(response => {
                    this.publicaciones = response.data.publicaciones.data;
                    this.setDataPagination(response.data.publicaciones);
                    this.nombre=false;

                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido cargar las publicaciones. :('
                    });
                })
            },
            /** 
            * Envia consultas de localidades. 
            * 
            * @getLocalidades 
            * @ Param {String} search Argumento 1 
            * @ Param {Function} loading Argumento 2 
            */
            getLocalidades: function(search, loading) {
                loading(true)
                this.$http.get('api/localidades/?q='+ search
                    ).then(response => {
                        this.localidades = response.data.data
                        loading(false)
                    })
            },
            /** 
            * Consulta de todas las categorias.
            * 
            * @getCategorias 
            */
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
            /** 
            * Consulta por una categoria al servidor
            * 
            * @changeCategory 
            */
            changeCategory: function(){
                this.subcategorias = [];
                this.subcategoria_id = '';
                this.rubros = [];
                this.rubro_id = '';
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
            /** 
            * Consulta por una subcategoria al servidor
            * 
            * @changeSubcategory 
            */
            changeSubcategory: function(){
                this.rubros = [];
                this.rubro_id = '';

                if(this.subcategoria_id != undefined && this.subcategoria_id != '')
                {
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
                }
                
            },
            /** 
            * Cambia el pagina en la cual se ecuntra posicionado.
            * 
            * @pageOneChanged 
            * @ Param {Number} pageOne Argumento 1  
            */
            pageOneChanged (pageOne) {
                this.pageOne.current_page = pageOne;
                this.searchPublicacion()
            },
            /** 
            * Encapsula a la function @managerSearch .
            * 
            * @searchPublicacion  
            */
            searchPublicacion: function(){

                if(this.q != ''){
                    this.nombre=true;
                    this.$router.replace({ path : '/', query : {q: this.q} });
                    console.log('push');
                } else{
                    this.$router.replace({ path : '/'})
                }
                if (this.nombre){
                this.managerSearch(this.q)
                }
            },
            /** 
            * actualiza los datos del paginador de publicaciones. 
            * 
            * @setDataPagination 
            * @ Param {Object} data Argumento 1 
            */
            setDataPagination(data){
                    this.pageOne.total = data.total;
                    this.pageOne.per_page = data.per_page;
                    this.pageOne.last_page = data.last_page;
                    this.pageOne.next_page_url = data.next_page_url;
                    this.pageOne.prev_page_url = data.prev_page_url;
                    this.pageOne.from = data.from;
                    this.pageOne.to = data.to;
            },
            /** 
            * Captura las consultas de del path (si los hay) 
            * 
            * @queryPath 
            */
            queryPath(){
                if(this.$route.query.with_denomination != undefined){
                    this.rubro_id = this.$route.query.with_denomination;
                    this.changePath(this.rubro_id, 'api/rubro/', 'rubro');
                }
                else
                {
                    if(this.$route.query.with_subcategory != undefined){
                        this.subcategoria_id = this.$route.query.with_subcategory
                        this.changePath(this.subcategoria_id, 'api/subcategoria/', 'subcategoria');
                    }
                    else
                    {
                        if(this.$route.query.with_category != undefined){
                            this.categoria_id = this.$route.query.with_category
                            this.changePath(this.categoria_id, 'api/categoria/', 'categoria');
                        }
                        else if(this.$route.query.q != undefined && this.$route.query.q != '')
                        {
                            this.q = this.$route.query.q;
                            console.log('query path');


                        }
                    }
                }

                this.nombre=true;
            },
            /** 
            * Consulta por el parametro dado y 
            * actuliza la ruta en la que se encuentra.
            * 
            * @changePath 
            * @ Param {String} id Argumento 1 
            * @ Param {String} api Argumento 2
            * @ Param {String} filtro Argumento 3 
            */
            changePath(id, api, filtro){
                this.$http.get(api+''+id).then(response => {
                    var responseData = response.data.data
                    if(filtro == 'rubro')
                    {
                        this.listaPath.push( {
                            route: '/?with_category='+responseData.subcategoria.categoria.id, 
                            name: responseData.subcategoria.categoria.nombre
                        } )
                        this.listaPath.push( {
                            route: '/?with_subcategory='+responseData.subcategoria.id, 
                            name: responseData.subcategoria.nombre
                        } )
                        this.listaPath.push( {
                            route: '/?with_denomination='+responseData.id, 
                            name: responseData.nombre
                        } )
                    }
                    else
                    {
                        if(filtro == 'subcategoria')
                        {
                            this.listaPath.push( {
                                route: '/?with_category='+responseData.categoria.id, 
                                name: responseData.categoria.nombre
                            } )
                            this.listaPath.push( {
                                route: '/?with_subcategory='+responseData.id, 
                                name: responseData.nombre
                            } )
                        } 
                        else 
                        {
                            if(filtro == 'categoria')
                            {
                                this.listaPath.push( {
                                    route: '/?with_category='+responseData.id, 
                                    name: responseData.nombre
                                } )
                            }
                        }
                    }
                    this.$events.fire('changePath', this.listaPath,  responseData.nombre);
                })
            },
            /** 
            * Cambia los valoresde los datos de consulta a sus valores por defecto.
            * 
            * @clearData 
            */
            clearData(){
                this.rubro_id = '';
                this.subcategoria_id = '';
                this.categoria_id = '';
                this.q = '';
                this.pageOne= {
                    total:0,
                    per_page:10,
                    current_page: 1,
                    last_page:1,
                    next_page_url:null,
                    prev_page_url:null,
                    from:0,
                    to:0 
                }
            }
        },
        watch: {
            '$route.query'() {

                if (!this.nombre){
                    this.clearData();
                    this.listaPath = [{route: '/', name: 'Inicio'}];
                    this.titlePath = 'Inicio';
                    this.$events.fire('changePath', this.listaPath, this.titlePath);
                    this.queryPath();
                    this.searchPublicacion();
                    this.categoria_id = '';
                    this.subcategoria_id = '';
                    this.rubro_id = '';
                }

            }
        }
    }
</script>
