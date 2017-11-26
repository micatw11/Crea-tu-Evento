import { Line } from 'vue-chartjs'

export default {
  extends: Line,
  data () {
    return {
      datacollection: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 
					'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        datasets: [
          {
            label: 'Nuevas Publicaoiones',
            backgroundColor: '#f39c12',
            data: [40, 20, 45, 12, 34, 56, 76, 12 , 65, 89, 109, 123]
          }
        ]
      }
    }
  },
  mounted () {
    this.renderChart(this.datacollection, {responsive: true, maintainAspectRatio: false})
  }
}