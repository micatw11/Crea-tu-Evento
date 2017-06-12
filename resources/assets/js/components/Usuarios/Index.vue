<template>

    <div class="content-wrapper">
        <path-content :titleContent="titleContent"></path-content>
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
                                :fields="colums"
                                tableClass="table table-bordered"
                                :noDataTemplate="noDataTemplate"
                                :css="css"
                                :append-params="moreParams"
                                ref="vuetable"
                                api-url="/api/usuario"
                                pagination-path=""
                                @vuetable:pagination-data="onPaginationData"
                                detail-row-component="my-detail-row"
                                @vuetable:cell-clicked="onCellClicked">

                                    <template slot="actions" scope="props">
                                        <div class="custom-actions">
                                           
                                           <!-- <button class="btn-xs btn-default"
                                                @click="onActionEdit('edit-item', props.rowData, props.rowIndex)">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </button>-->
                                           
                                             <select v-model="props.rowData.roles_id" @change="changeItemRol($event, props.rowData, props.rowIndex)">
                                                <option v-for="option in options" v-bind:value="option.value" selected>
                                                    {{ option.text }}
                                                </option>
                                            </select>

                                            <button class="btn-xs btn-default"
                                                @click="onAction('view-item', props.rowData, props.rowIndex)">
                                                <i class="glyphicon glyphicon-search"></i>
                                            </button>
                                             <button class="btn-xs btn-default"
                                                @click="onActionDelete('delete-item', props.rowData, props.rowIndex)">
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
    import colums from './colums.js';
    import route from '../../routes.js';
    import PathContent from '../Layouts/Path';

    Vue.component('my-detail-row', DetailRow);
    Vue.component('filter-bar', FilterBar)
    //https://github.com/ratiw/vuetable-2-tutorial/wiki/lesson-13
    export default {

        data() {
            return {
                titleContent: 'Usuarios',
                options: [
                          { text: 'Administrador', value: '1' },
                          { text: 'Operador', value: '3' },
                          { text: 'Supervisor', value: '2' },
                          { text: 'Usuario', value: '5' }
                      ],
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} usuarios',
                noData:'No hay datos',
                moreParams: {},
                colums: colums
            }
        },
        components: {
            Vuetable, VuetablePagination, VuetablePaginationInfo, PathContent
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
                route.push("/user/"+data.id+"/perfil")
                console.log('slot) action: ' + action, data.name, index)
            },
            onActionDelete(action, data, index) {
                console.log('slot) action: ' + action, data.name, index),
                    this.$http.post('api/user/'+ data.id+'/block',
                    {
                        _method: 'PATCH',
                        logout: true,

                    })
                    .then(response => {
                        this.$toast.success({
                            title:'¡Acción realizada!',
                            message:'El usuario se a bloqueado. :('
                        });
                    }, response => {
                        this.$toast.error({
                            title:'¡Error!',
                            message:'No se han podido realizar los cambios. :('
                        });

                    })
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
            //cambiar rol
            changeItemRol(action, data, index) {
                this.selected = `${event.target.value}`
               this.$http.post('api/user/'+ data.id+'/rol',
                {
                    _method: 'PATCH',
                    roles_id: this.selected
                })
                .then(response => {
                    this.$toast.success({
                        title:'¡Cambios realizados!',
                        message:'Se ha cambiado correctamente el rol.'
                    });
                }, response => {
                    this.$toast.error({
                        title:'¡Error!',
                        message:'No se han podido guardar los cambios.'
                    });
                    if(response.status === 401)
                    {
                        //setea errores en validaciones de api
                        this.errorsApi = response.body;
                    }
                })
               
            }
        }
    }

</script>