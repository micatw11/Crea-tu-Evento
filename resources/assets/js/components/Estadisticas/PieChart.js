import { Pie } from 'vue-chartjs'

export default {
  extends: Pie,
  data () {
    return {
      datacollection: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
					'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [
          {
            label: 'Reservas realizadas',
            backgroundColor: ['#f39c12', '#dd4b39', '#00a65a', '#3c8dbc'],
            data: [40, 20, 45, 12]
          }
        ]
      }
    }
  },
  mounted () {
    this.renderChart(this.datacollection, {responsive: true, maintainAspectRatio: false})
  }
}