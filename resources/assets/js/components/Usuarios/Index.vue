<template>
    <div class="content-wrapper">
        <div class="content"> 
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <filter-bar></filter-bar>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive no-padding">
                            <vuetable
                                tableClass="table table-bordered"
                                :css="css"
                                ref="vuetable"
                                :append-params="moreParams"
                                api-url="/api/usuario"
                                pagination-path=""
                                @vuetable:pagination-data="onPaginationData"
                                detail-row-component="my-detail-row"
                                @vuetable:cell-clicked="onCellClicked"
                                :fields="columns">
                                    <template slot="actions" scope="props">
                                            <div class="custom-actions">
                                                <button class="btn-xs btn-default"
                                                    @click="onAction('view-item', props.rowData, props.rowIndex)">
                                                    <i class="glyphicon glyphicon-search"></i>
                                                </button>
                                                <button class="btn-xs btn-default"
                                                    @click="onAction('edit-item', props.rowData, props.rowIndex)">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </button>
                                                <button class="btn-xs btn-default"
                                                    @click="onAction('delete-item', props.rowData, props.rowIndex)">
                                                    <i class="glyphicon glyphicon-trash"></i>
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
    import DetailRow from './DetailRowUsuario';
    import FilterBar from './FilterBarUsuario';
    import moment from 'moment';
    
    Vue.component('filter-bar', FilterBar)
    Vue.component('my-detail-row', DetailRow);

    //https://github.com/ratiw/vuetable-2-tutorial/wiki/lesson-13
    export default {

        data() {
            return {
                css: Style,
                info: 'Mirando de {from} a {to} de {total} usuarios',
                noData:'No hay usuario',
                moreParams: {},
                columns: [
                    {
                    name: '__sequence',   // <----
                    title: '#',
                    titleClass: 'center aligned',
                    dataClass: 'right aligned'
                    },
                    {
                        name: 'usuario.nombre',
                        title: 'Nombre',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                        name: 'usuario.apellido',
                        title: 'Apellido',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'usuario.sexo',
                        title: 'Genero',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'genderLabel'
                    },
                    {
                        name: 'email',
                        title: 'Email',
                        //sortField: 'email',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'created_at',
                        title: 'Fecha de registro',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatDate|DD-MM-YYYY'
                    },
                    {
                    name: '__slot:actions',   // <----
                    title: 'Actions',
                    titleClass: 'center aligned',
                    dataClass: 'center aligned'
                    }
                ]
            }
        },
        components: {
            Vuetable, VuetablePagination, VuetablePaginationInfo
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData))
            this.$events.$on('filter-reset', e => this.onFilterReset())
        },
        methods: {
            genderLabel (value) {
                return value == 'M'
                ? '<span class="label label-info"><i class="glyphicon glyphicon-star"></i> Masculino</span>'
                : '<span class="label label-success"><i class="glyphicon glyphicon-heart"></i> Femenino</span>'
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD hh:mm:ss').format(fmt)
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page)
            },
            onAction (action, data, index) {
                console.log('slot) action: ' + action, data.name, index)
            },
            onCellClicked (data, field, event) {
                console.log('cellClicked: ', field.name)
                this.$refs.vuetable.toggleDetailRow(data.id)
            },
            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams = {
                    'filter': filterText
                }
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            },
            onFilterReset () {
                this.moreParams = {}
                Vue.nextTick( () => this.$refs.vuetable.refresh())
            }
        }
    }
</script>
