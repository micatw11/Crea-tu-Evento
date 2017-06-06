<template>
    <div>
        <div class="table-responsive">
            <vuetable
                api-url="/api/usuario"
                :fields="columns">
            </vuetable>
        </div>

    </div>
</template>

<script>
    import Vuetable from 'vuetable-2/src/components/Vuetable';
//    import VuetablePagination from 'vuetable-2/src/components/VuetablePagination';
//    import VuetablePaginationInfo from 'vuetable-2/src/components/VuetablePaginationInfo';

    export default {

        data() {
            return {
                columns: [
                    {
                        name: 'usuario.nombre',
                        title: 'Nombre',
                        titleClass: 'text-center',
                        dataClass: 'text-center'           
                    },
                    {
                        name: 'usuario.apellido',
                        title: 'Apellido',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'usuario.sexo',
                        title: 'Genero',
                        titleClass: 'text-center',
                        dataClass: 'text-center',
                        callback: 'genderLabel'
                    },
                    {
                        name: 'email',
                        title: 'Email',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    },
                    {
                        name: 'created_at',
                        title: 'Fecha de registro',
                        titleClass: 'text-center',
                        dataClass: 'text-center'
                    }
                ]
            }
        },
        components: {
            Vuetable
        },
        methods: {
            genderLabel (value) {
                return value == 'M'
                ? '<span class="label label-info"><i class="glyphicon glyphicon-star"></i> Masculino</span>'
                : '<span class="label label-success"><i class="glyphicon glyphicon-heart"></i> Femenino</span>'
            }
        }
    }
</script>
