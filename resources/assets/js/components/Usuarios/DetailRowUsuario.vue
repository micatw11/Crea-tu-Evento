  <template>
    <div class="col-sm-12">
        <div class="col-sm-3">
            <img :src="'/storage/avatars/'+ rowData.usuario.avatar" class="img-responsive" width="150" height="150">
        </div>
        <div class="col-sm-9">
            <p class="inline-block">
                <label>Nombre: </label>
                <span>{{rowData.usuario.apellido}}, {{rowData.usuario.nombre}}</span>
            </p>
            <p class="inline-block">
                <label>Email: </label>
                <span>{{rowData.email}}</span>
            </p>
            <p class="inline-block">
                <label>Nombre de Usuario: </label>
                <span>{{rowData.name}}</span>
            </p>
            <p class="inline-block">
                <label>Cumpleaños </label>
                <span>{{rowData.usuario.fecha_nac}}</span>
            </p>
            <p class="inline-block">
                <label>Genero: </label>
                <span>{{rowData.usuario.sexo == 'F' ? 'Femenino' : 'Masculino'}}</span>
            </p>
        </div>

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