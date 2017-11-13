<template>
<div>
    <form role="form">
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('tipo')&&validarCategoria}">
                <div class="col-sm-12">
                    <label  class="control-label">Indique a el tipo de categoria.</label><br>
                    <select       
                        name="tipo"                  
                        v-model="categoria.tipo_proveedor"
                        class="form-control" 
                        v-validate="'required'">
                        <option value="" disabled>Seleccione</option>
                        <option v-for="option in opcionesTipo" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                    <!-- validacion vee-validation -->
                    <span v-show="errors.has('tipo')&&validarCategoria" class="help-block">El campo es obligatorio.</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.tipo_proveedor">
                        <div v-for="msj in errorsApi.tipo_proveedor">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div :class="{'form-group has-feedback': true, 'form-group has-error': errors.has('nombre')&&validarCategoria}">
                <div class="col-sm-12">
                    <label for="inputCategoria" class="control-label">Nombre</label><br>
                       <input name="nombre" v-validate="'required|min:4|max:22'" type="text" v-model="categoria.nombre" class="form-control" placeholder="Nombre Categoria">
                            <!-- validacion vee-validation -->
                    <span v-show="errors.has('nombre')&&validarCategoria" class="help-block">{{ errors.first('nombre') }}</span>
                    <!-- validacion api-->
                    <div class="text-red" v-if="errorsApi.nombre">
                        <div v-for="msj in errorsApi.nombre">
                            <p>{{ msj }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form> 
</div>
</template>



<script>

export default {
    props: {
            categoria: {
                type: Object,
                required: true
            },
            nuevo: {
                type: Boolean,
                required: true
            },
            errorsApi: {
                type: Object,
                required: true
            }
    },
    data() {
        return {
            localidades: [],
            validarCategoria: false,
            opcionesTipo: [
                  { text: 'Producto', value: 'producto' },
                  { text: 'Salón', value: 'salon' },
                  { text: 'Servicio', value: 'servicio' }
                ]
        }
    },
    mounted() {
        this.$events.$on('validarFormCategoria', () =>this.validateBeforeSubmit());
    },
    methods: {
        //form validation
        validateBeforeSubmit: function() {
            this.$validator.validateAll().then((result) => {
                if (result) {
                    this.validarCategoria = false; 
                    if (this.nuevo){
                        this.$emit('validadoNewCategoria')
                    }else{
                        this.$emit('validadoEditCategoria')
                    }
                } else {
                    this.validarCategoria = true;
                }
                return;
            }).catch(() => {

            });
        }
    }
}
</script>