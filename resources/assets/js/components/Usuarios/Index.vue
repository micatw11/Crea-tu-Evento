<template>
    <div class="default-content">
        <div class="content"> 
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
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
                                detail-row-component="detail-row-usuario"
                                @vuetable:cell-clicked="onCellClicked">

                                    <template slot="actions" scope="props">
                                        <div class="custom-actions">

                                            <select 
                                                v-model="props.rowData.roles_id" 
                                                @change="changeItemRol($event, props.rowData, props.rowIndex)">
                                                <option 
                                                    v-for="option in options" 
                                                    v-bind:value="option.value" 
                                                    selected>
                                                    {{ option.text }}
                                                </option>
                                            </select>

                                            <button class="btn-xs btn-default"
                                                @click="onActionShow('view-item', props.rowData, props.rowIndex)">
                                                <i class="glyphicon glyphicon-search"></i> Ver
                                            </button>

                                             <button v-if="props.rowData.estado == 2" class="btn-xs btn-default"
                                                @click="onActionBlock(1, props.rowData, props.rowIndex)">
                                                <i class="fa fa-unlock"></i> Desbloquear
                                            </button>

                                             <button v-else class="btn-xs btn-default"
                                                @click="onActionBlock(2, props.rowData, props.rowIndex)">
                                                <i class="ion-locked"></i> Bloquear
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

    Vue.component('detail-row-usuario', DetailRow);
    Vue.component('filter-bar', FilterBar);
  
    export default {

        data() {
            return {
                titlePath: 'Usuarios',
                options: [
                          { text: 'Administrador', value: '1' },
                          { text: 'Supervisor', value: '2' },
                          { text: 'Operador', value: '3' },
                          { text: 'Usuario', value: '5' }
                      ],
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} usuarios',
                noData:'No hay datos',
                moreParams: {},
                colums: colums,
                listPath : [{route: '/', name: 'Home'}, {route: '/usuario', name: 'Usuarios'}]
            }
        },
        components: {
            Vuetable, VuetablePagination, VuetablePaginationInfo,
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData))
            this.$events.$on('filter-reset', e => this.onFilterReset())
            this.$events.fire('changePath', this.listPath, this.titlePath);
        },
        methods: {
            genderLabel (value) {
                return value == 'M'
                ? '<span class="label label-info"><i class="ion-male"></i> Masculino</span>'
                : '<span class="label label-success"><i class="ion-female"></i> Femenino</span>'
            },
            formatDate (value, fmt = 'D MMM YYYY') {
                return (value == null)
                    ? ''
                    : moment(value, 'YYYY-MM-DD hh:mm:ss').format(fmt)
            },
            formatEstado (value){
                if(value === 0) return 'Inactivo';
                else if(value === 1) return 'Activo';
                else return 'Bloqueado';
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetable.changePage(page)
            },
            onActionShow(action, data, index) {
                this.listPath.push({route: '/usuario/'+data.id+'/perfil', name: 'Perfil'})
                this.$events.fire('changePath', this.listPath, 'Perfil');
                
                route.push("/usuario/"+data.id+"/perfil")
            },
            onActionBlock(action, data, index) {
                    this.$http.post('api/user/'+ data.id+'/block',
                    {
                        _method: 'PATCH',
                        action: action

                    })
                    .then(response => {
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