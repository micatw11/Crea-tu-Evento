  <template>
    <div @click="onClick">
      <p class="inline-block">
        <label>Nombre: </label>
        <span>{{rowData.nombre}}</span>
      </p>
      <p class="inline-block">
        <label>Email: </label>
        <span>{{rowData.email}}</span>
      </p>
      <p class="inline-block">
        <label>Genero: </label>
        <span>{{rowData.user.usuario.sexo == 'F' ? 'Femenino' : 'Masculino'}}</span>
      </p>
    </div>
  </template>

  <script>
  export default {
    props: {
      rowData: {
        type: Object,
        required: true
      },
      rowIndex: {
        type: Number
      }
    },
    methods: {
      onClick (event) {
        console.log('my-detail-row: on-click', event.target)
      }
    },
  }
  </script>