<template>
	<div>
		<section class="content">

			<filter-bar-articulos></filter-bar-articulos>

            <vuetable-articulos
                :fields="tableColumns"
                tableClass="table table-bordered"
                :noDataTemplate="noDataTemplate"
                :css="css"
                :append-params="moreParams"
                ref="vuetableArticulo"
                :api-url="url"
                pagination-path=""
                @vuetable:pagination-data="onPaginationData"
                @vuetable:cell-clicked="onCellClicked">
            </vuetable-articulos>

            <vuetable-pagination-info-articulos
                ref="paginationInfo"
                :info-template='info'
                :no-data-template='noData'>
            </vuetable-pagination-info-articulos>

            <vuetable-pagination-articulos
                ref="pagination"
                :css="css.pagination"
                @vuetable-pagination:change-page="onChangePage">
            </vuetable-pagination-articulos>

		</section>
	</div>
</template>
<script>
	import VuetableArticulos from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationArticulos from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoArticulos from 'vuetable-2/src/components/VuetablePaginationInfo';

    import Style from './../../Layouts/Style-css.js';
    import FilterBar from './FilterBarArticulos';
    import route from '../../../routes.js';
    import accounting from 'accounting-js';

    Vue.component('filter-bar-articulos', FilterBar);

	export default {
        data(){
            return {
                css: Style,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} articulos',
                noData:'No hay datos',
                moreParams: {},
                tableColumns:  [
                    {
                        name: 'nombre',
                        title: 'Nombre',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                        name: 'precio',
                        title: 'Precio',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'formatMoney'        
                    },
                    {
                        name: 'stock',
                        title: 'Stock',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                        name: 'rubro.nombre',
                        title: 'Rubro',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },

                ],
                url: '/api/articulo'
            }
        },
        components: {
            VuetableArticulos, VuetablePaginationArticulos, VuetablePaginationInfoArticulos
        },
        mounted() {
            this.$events.$on('filter-set', eventData => this.onFilterSet(eventData));
            this.$events.on('cerrar', () => this.closeModal());
            this.$events.on('reloadIndexArticulos', () => Vue.nextTick( () => this.$refs.vuetableC.refresh()) );

        },
        beforeDestroy() {
            this.$events.$off('reloadIndexArticulos');
            this.$events.$off('cerrar');
            this.$events.$off('filter');
        },
        methods: {
            formatMoney(value){
                return (value == null)
                    ? ''
                    : accounting.formatMoney(value, "$", 2, ".", ",");
            },
            onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetableArticulo.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetableArticulo.toggleDetailRow(data.id)
            },

            //filtros de busqueda
            onFilterSet (filterText) {
                this.moreParams.filter = filterText

                this.$nextTick( () => this.$refs.vuetableArticulo.refresh() )
            }
        }

    }
</script>