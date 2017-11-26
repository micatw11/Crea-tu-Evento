<template>
	<div class="default-content">
		<section class="content">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Estadisticas de la plataforma</h3>
							<!-- /.box-tools -->
						</div>
						<!-- /.box-header -->
						<div class="box-body no-padding">
							<div class="col-sm-12">
								<br>
								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<div class="small-box bg-yellow">
										<div class="inner">
											<h3>6</h3>
											<p>Usuarios registrados</p>
										</div>
										<div class="icon">
											<i class="ion ion-person-add"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<div class="small-box bg-red">
										<div class="inner">
											<h3>2</h3>
											<p>Proveedores registrados</p>
										</div>
										<div class="icon">
											<i class="ion ion-pie-graph"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<div class="small-box bg-green">
										<div class="inner">
											<h3>3<sup style="font-size: 20px">%</sup></h3>
											<p>Usuarios registrados Mensuales</p>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
									</div>
								</div>

								<div class="col-lg-3 col-xs-6">
									<!-- small box -->
									<div class="small-box bg-green">
										<div class="inner">
											<h3>5<sup style="font-size: 20px">%</sup></h3>
											<p>Nuevos proveedores mensaules</p>
										</div>
										<div class="icon">
											<i class="ion ion-stats-bars"></i>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12">
								<line-chart></line-chart>
							</div>
							<div class="col-sm-12">
								<bar-chart></bar-chart>
							</div>
							<div class="col-sm-12">
								<pie-chart></pie-chart>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</template>
<script>
	import auth from './../../auth';
	import route from './../../routes';
	import Role from './../../config';
	import moment from 'moment';
	import BarChart from './BarChart';
	import LineChart from './LineChart';
	import PieChart from './PieChart';
    export default {
        data(){
        	return {

            }
        },
        beforeMount(){
        	this.$events.fire('changePath', [{route: '/', name: 'Inicio'},{route: '/estadisticas', name: 'Estadisticas'}], 'Estadisticas' );
        },
        components: { LineChart, BarChart, PieChart },
        methods: {
        }
    }
</script>