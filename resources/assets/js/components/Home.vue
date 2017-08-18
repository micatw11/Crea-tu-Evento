<template>
    <div class="default-content">
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Busqueda</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row margin">
                                <div class="col-sm-4">
                                    <v-select
                                        :on-search="getLocalidades" 
                                        :options="localidades"
                                        v-model="localidad_id" 
                                        placeholder="Ubicación">
                                    </v-select>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import VSelect from "vue-select";
    export default {
        data(){
            return {
                titlePath: 'Home',
                listaPath: [{route: '/', name: 'Home'}],
                publicaciones : [],
                localidades: [],
                localidad_id: null
            }
        },
        mounted() {
            this.$events.fire('changePath', this.listaPath, this.titlePath);
            this.$events.on('search', value => this.searchPublication(value));
        },
        beforeMount() {
            this.searchPublication('');
        },
        methods: {
            searchPublication(filter){
                console.log(filter)
                this.$http.get('api/publicacion?filter='+ filter)
                .then(response => {
                    this.publicaciones = response.data.publicaciones;
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
            }
        },
        components: {
            VSelect
        }
    }
</script>
