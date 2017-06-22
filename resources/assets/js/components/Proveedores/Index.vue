<template>
	<div class="content-wrapper">
		<path-content :titleContent="titleContent"></path-content>
        <div class="content"> 
            <div class="box-header">
                <div class="col-sm-2">
                    <router-link to='/proveedor/new'><button type="button" class="btn btn-block btn-primary btn-sm">Nuevo Proveedor</button></router-link>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <filter-bar></filter-bar>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <vuetable
                                :fields="colums"
                                tableClass="table table-bordered"
                                :noDataTemplate="noDataTemplate"
                                :css="css"
                                :append-params="moreParams"
                                ref="vuetable"
                                :api-url="url"
                                pagination-path=""
                                @vuetable:pagination-data="onPaginationData"
                                detail-row-component="my-detail-row"
                                @vuetable:cell-clicked="onCellClicked">

                                    <template slot="actions" scope="props">
                                        <div class="custom-actions">

                                            <button class="btn-xs btn-default"
                                                @click="onActionVer('view-item', props.rowData, props.rowIndex)">
                                                <i class="glyphicon glyphicon-search"></i> Ver
                                            </button>

                                             <button v-if="props.rowData.estado === 'Tramite'" class="btn-xs btn-default"
                                                @click="onActionConfirmar(1, props.rowData, props.rowIndex)">
                                                <i class="fa fa-check"></i> Aceptar
                                            </button>
                                             <button v-else class="btn-xs btn-default"
                                                @click="onActionRechazar(2, props.rowData, props.rowIndex)">
                                                <i class="fa fa-close"></i> Rechazar
                                            </button>

                                        </div>
                                    </template>

                            </vuetable>
                        </div>

                        <div class="box-footer clearfix">

                            <vuetable-pagination-info 
                                ref="paginationInfo"
                                :info-template='info'
                                :no-data-template='noData'>
                            </vuetable-pagination-info>

                            <vuetable-pagination 
                                ref="pagination"
                                :css="css.pagination"
                                @vuetable-pagination:change-page="onChangePage">
                            </vuetable-pagination>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</template>

<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable';
    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo';
    import Style from './../Layouts/Style-css.js';
    import FilterBar from './FilterBarProveedor';
    import DetailRow from './DetailRowProveedor';
    import colums from './colums.js';
    import route from '../../routes.js';
    import PathContent from '../Layouts/Path';
    import NewProveedor from './New';

    Vue.component('filter-bar', FilterBar);
    Vue.component('my-detail-row', DetailRow);

    export default {
        data(){
            return {
                titleContent: 'Proveedores',
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} proveedores',
                noData:'No hay datos',
                moreParams: {},
                colums: colums,
                url: '/api/proveedor'
            }
        },
        components: {
            Vuetable, VuetablePagination, VuetablePaginationInfo, PathContent, NewProveedor
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));
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
        },

    }
</script>