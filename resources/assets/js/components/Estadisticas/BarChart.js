import {Bar} from 'vue-chartjs'

export default {
  extends: Bar,
  data () {
    return {
      datacollection: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
          'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [
          {
            label: 'Reservas mensuales',
            backgroundColor: '#00a65a',
            data: [40, 20]
          }
        ]
      }
    }
  },
  mounted () {
    this.renderChart(this.datacollection, {responsive: true, maintainAspectRatio: false})
  }
}