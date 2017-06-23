<template>
	<div class="default-content">
        <div class="content"> 
            <div class="box-header">
                <div class="col-sm-2">
                    <router-link to='/proveedor/new'><button type="button" class="btn btn-block btn-primary btn-sm">Nuevo Proveedor</button></router-link>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-header">
                            <filter-bar-proveedor></filter-bar-proveedor>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <vuetable-p
                                :fields="tableColumns"
                                tableClass="table table-bordered"
                                :noDataTemplate="noDataTemplate"
                                :css="css"
                                :append-params="moreParams"
                                ref="vuetable"
                                :api-url="url"
                                pagination-path=""
                                @vuetable:pagination-data="onPaginationData"
                                detail-row-component="detail-row-proveedor"
                                @vuetable:cell-clicked="onCellClicked">

                                    <template slot="actions" scope="props">
                                        <div class="custom-actions">

                                            <!-- Ver perfil -->
                                            <button class="btn-xs btn-default"
                                                @click="onActionShow(props.rowData, props.rowIndex)">
                                                <i class="glyphicon glyphicon-search"></i> Ver
                                            </button>

                                            <!-- Aprobar a proveedor -->
                                            <button v-if="props.rowData.estado === 'Tramite' ||
                                                        props.rowData.estado === 'Baja'" 
                                                        class="btn-xs btn-default"
                                                @click="onActionEstado('Aprobado', props.rowData, props.rowIndex)">
                                                <i class="fa fa-check"></i> Aprobar
                                            </button>

                                            <!-- Rechazar a proveedor -->
                                            <button v-if="props.rowData.estado === 'Tramite'"
                                                        class="btn-xs btn-default"
                                                @click="onActionEstado('Rechazado', props.rowData, props.rowIndex)">
                                                <i class="fa fa-close"></i> Rechazar
                                            </button>

                                            <!-- Baja a proveedor -->
                                            <button 
                                                v-if="props.rowData.estado === 'Aprobado'" 
                                                     class="btn-xs btn-default"
                                                @click="onActionEstado('Baja',props.rowData, props.rowIndex)">
                                                <i class="fa fa-close"></i> Baja
                                            </button>
                                        </div>
                                    </template>

                            </vuetable-p>
                        </div>

                        <div class="box-footer clearfix">

                            <vuetable-pagination-info-p 
                                ref="paginationInfo"
                                :info-template='info'
                                :no-data-template='noData'>
                            </vuetable-pagination-info-p>

                            <vuetable-pagination-p 
                                ref="pagination"
                                :css="css.pagination"
                                @vuetable-pagination:change-page="onChangePage">
                            </vuetable-pagination-p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
    import VuetableP from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationP from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoP from 'vuetable-2/src/components/VuetablePaginationInfo';
    import Style from './../Layouts/Style-css.js';
    import FilterBar from './FilterBarProveedor';
    import DetailRowProveedor from './DetailRowProveedor';
    import tableColumns from './colums.js';
    import route from '../../routes.js';
    import PathContent from '../Layouts/Path';
    import NewProveedor from './New';


    Vue.component('filter-bar-proveedor', FilterBar);
    Vue.component('detail-row-proveedor', DetailRowProveedor);

    export default {
        data(){
            return {
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} proveedores',
                noData:'No hay datos',
                moreParams: {},
                tableColumns: tableColumns,
                url: '/api/proveedor',
                titlePath: 'Proveedores',
                listPath : [{route: '/', name: 'Home'}, {route: '/proveedores', name: 'Proveedores'}]
            }
        },
        components: {
            VuetableP, VuetablePaginationP, VuetablePaginationInfoP, PathContent, NewProveedor
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));

            this.$events.fire('changePath', this.listPath, this.titlePath);

        },
        methods: {
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetable.toggleDetailRow(data.id)
            },

            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams = {
                    'filter': filterText
                }
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },

            onActionShow(data, index){
                this.listPath.push({route: '/usuario/'+data.user_id+'/perfil', name: 'Perfil'})
                this.$events.fire('changePath', this.listPath, 'Perfil');

                route.push("/usuario/"+data.user_id+"/perfil")
            },

            onActionEstado(action, data, index){
                this.$http.post(
                    'api/proveedor/'+data.user_id+'/estado',
                    {
                        _method: 'PATCH',
                        action: action
                    }
                ).then(response => {
                    this.$toast.success({
                        title:'¡Acción realizada!',
                        message:'La acción se ha realizado correctamente.'
                    });
                    data.estado = action;
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido realizar los cambios. :('
                    });
                })
            }
        },

    }
</script>