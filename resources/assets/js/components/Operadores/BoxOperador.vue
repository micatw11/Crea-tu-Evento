<template>
	<div>
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Proveedores Registrados</h3>
				</div>
		        <div class="box-body table-responsive no-padding">
		            <vuetable-pr
		                :fields="tableColumns"
		                tableClass="table table-bordered"
		                :noDataTemplate="noDataTemplate"
		                :css="css"
		                :append-params="moreParams"
		                ref="vuetablePR"
		                :api-url="url"
		                pagination-path=""
		                @vuetable:pagination-data="onPaginationData"
	                    detail-row-component="detail-row-proveedor-pr"
	                    @vuetable:cell-clicked="onCellClicked">
		            </vuetable-pr>
		        </div>

		        <div class="box-footer clearfix">

		            <vuetable-pagination-info-pr
		                ref="paginationInfo"
		                :info-template='info'
		                :no-data-template='noData'>
		            </vuetable-pagination-info-pr>

		            <vuetable-pagination-pr 
		                ref="pagination"
		                :css="css.pagination"
		                @vuetable-pagination:change-page="onChangePage">
		            </vuetable-pagination-pr>
		            
		        </div>
			</div>
		</div>
		<div class="col-md-12" v-if="loadedProveedores">
			<div class="box box-primary">
				<div class="box-body no-padding">
					<line-chart style="height: 200px;" :data="dataLineCharts"></line-chart>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
    import VuetablePr from 'vuetable-2/src/components/Vuetable';
    import VuetablePaginationPr from 'vuetable-2/src/components/VuetablePagination';
    import VuetablePaginationInfoPr from 'vuetable-2/src/components/VuetablePaginationInfo';
    import Style from './../Layouts/Style-css.js';
    import DetailRowProveedor from './DetailRowProveedor';
    Vue.component('detail-row-proveedor-pr', DetailRowProveedor);
	import LineChart from './LineChart';
	import moment from 'moment';

	export default {
		data(){
			return {
				url: '/api/operador/'+this.$route.params.userId +'/actividad/proveedores',
				css: Style,
				proveedores: [],
				loadedProveedores: false,
                noDataTemplate: 'No hay datos para visualizar',
                info: 'Mirando de {from} a {to} de {total} proveedores registrados',
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
				        name: 'email',
				        title: 'Email',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'cuit',
				        title: 'N° Cuit',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'domicilio.localidad.nombre',
				        title: 'Ubicacion',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'created_at',
				        title: 'Fecha',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    },
				    {
				        name: 'estado',
				        title: 'Estado',
				        titleClass: 'text-center',
				        dataClass: 'text-center'           
				    }
                ],
            }
        },
        beforeMount(){
        	this.getProveedores();
        },
        components: {VuetablePr, VuetablePaginationPr, VuetablePaginationInfoPr, LineChart},
        methods: {
        	getProveedores(){
        		this.$http.get('/api/operador/'+this.$route.params.userId +'/actividad/proveedores').then(response =>{
        			this.proveedores = response.data;
        			this.loadedProveedores = true;
        		});
        	},
        	onPaginationData (paginationData) {
                this.$refs.pagination.setPaginationData(paginationData)
                this.$refs.paginationInfo.setPaginationData(paginationData)
            },
            onChangePage (page) {
                this.$refs.vuetablePR.changePage(page)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetablePR.toggleDetailRow(data.id)
            },
            onCellClicked (data, field, event) {
                this.$refs.vuetablePR.toggleDetailRow(data.id)
            },
        },
        computed: {
	        dataLineCharts(){
	        	var label = 'Proveedores Registrados';
	        	var backgroundColor = '#00a65a';
	        	var hoy = moment({});
				var labels = [];
				var data = [];
				var meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
						'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

	        	var mesesCount = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];
					for (var proveedor of this.proveedores) {
						if( moment(proveedor.created_at, 'YYYY-MM-DD').year() == hoy.year()){
							switch(moment(proveedor.created_at, 'YYYY-MM-DD').month()) {
							    case 1:
							        mesesCount[0] = mesesCount[0] + 1;
							        break;
							    case 2:
							        mesesCount[1] = mesesCount[1] + 1;
							        break;
							    case 3:
							        mesesCount[2] = mesesCount[2] + 1;
							        break;
							    case 4:
							        mesesCount[3] = mesesCount[3] + 1;
							        break;
							    case 5:
							        mesesCount[4] = mesesCount[4] + 1;
							        break;
							    case 6:
							        mesesCount[5] = mesesCount[5] + 1;
							        break;
							    case 7:
							        mesesCount[6] = mesesCount[6] + 1;
							        break;
							    case 8:
							        mesesCount[7] = mesesCount[7] + 1;
							        break;
							    case 9:
							        mesesCount[8] = mesesCount[8] + 1;
							        break;
							    case 10:
							        mesesCount[9] = mesesCount[9] + 1;
							        break;
							    case 11:
							        mesesCount[10] = mesesCount[10] + 1;
							        break;
							    case 12:
							        mesesCount[11] = mesesCount[11] + 1;
							        break;
							}
						}
					}
					for (var i = 0; i <= hoy.month(); i++) {
						labels.push(meses[i]);
						data.push(mesesCount[i]);
					}
					return  { labels: labels, datasets: [{label:label, backgroundColor:backgroundColor, data:data}] }
	        }
	    }
    }
</script>