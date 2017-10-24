<template>
    <div class="default-content">
            <div class="row">
                <div class="col-lg-12">
    	            <div class="box box-primary">
    	                <!-- /.box-header -->
                       <h2>Horarios</h2>
                       <table class="table" style="width='50%'">
                         <thead>
                           <tr>
                             <th>#</th>
                             <th>Domingo</th>
                             <th>Lunes</th>
                             <th>Martes</th>
                             <th>Miercoles</th>
                             <th>Jueves</th>
                             <th>Viernes</th>
                             <th>Sabado</th>
                           </tr>
                         </thead>
                         <tbody v-if="loadH">
                        
                           <tr v-for="hora in 24" v-html="handle(hora)" @click="editHorario($event)"  >
                           </tr>
                         </tbody>
                       </table>
    	            </div>
    	        </div>
    	    </div>
            <!-- Modal Modificar-->
         <div v-if="showModificarHorario" id="modificar" class="modal" role="dialog" :style="{ display : showModificarHorario  ? 'block' : 'none' }">
              <div class="modal-dialog">
                
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" @click="closeModal()">&times;</button>
                          <h4 class="modal-title">Modificar Horario</h4>
                      </div>
                     <edit-horario :horarioId="horarioId" :publicacionId="publicacionId"> </edit-horario>
                  </div>
               </div>
         </div>
	</div>
</template>

<script>
    import Style from './../../Layouts/Style-css.js';
    import EditHorario from './Edit';
    import moment from 'moment';

    export default	{
        props:{ 
             publicacionId: {
                required: true
            }
        },
    	data(){
            return {
                horario: [],
                horarioId: 0,
                loadH: false,
                endHandle: false,
                showModificarHorario: false,
                dias: [
                        {nombre:'domingo'},
                        {nombre:'lunes'},
                        {nombre:'martes'},
                        {nombre:'miercoles'},
                        {nombre:'jueves'},
                        {nombre:'viernes'},
                        {nombre:'sabado'}]
            }
    	},
        components: {
             EditHorario
        },
        beforeMount: function(){
            this.getHorarios();
        },
        mounted() {
            this.$events.on('reloadIndexHorario', () => this.getHorarios());
            this.$events.on('close', () => this.closeModal());
        },
        methods: {
            getHorarios: function(){
               this.endHandle=false;
                this.$http.get('api/horarios/'+this.publicacionId)
                .then(response =>{
                        this.horario = response.data.horarios
                        this.loadH =true;
                    }, response => {
                        if(response.status === 404){
                            this.$toast.error({
                                title:'¡Error!',
                                message:'No se han cargado sus horarios. :('
                            });
                        }
                    });
            },
            handle(hora) {
               
               var mostrar = false
               var masUnaHora = hora + 1;
               
                  this.diff= '<td style="text-align: center;">'+hora +':00hs</td>';
               
                  for (var i = 0; i < this.dias.length; i++) { 
                     mostrar= false
                     var red = Math.floor(Math.random() * (200 - 50))
                     var green = Math.floor(Math.random() * (200 - 50))
                     var blue = Math.floor(Math.random() * (200 - 50))
                     for ( var j = 0;(j < this.horario.length); j++) {
                        if(this.horario[j].dia == this.dias[i].nombre){


                           if ((
                              moment(hora+':00', 'HH:mm').
                              isSameOrAfter(moment(this.horario[j].hora_inicio, 'HH:mm')) 
                              &&
                              moment(masUnaHora+':00', 'HH:mm').
                              isBefore(moment(this.horario[j].hora_fin, 'HH:mm'))
                           ) || (
                              moment(hora+':00', 'HH:mm').
                              isAfter(moment(this.horario[j].hora_inicio, 'HH:mm')) 
                              &&
                              moment(hora+':00', 'HH:mm').
                              isBefore(moment(this.horario[j].hora_fin, 'HH:mm')) 
                           ) ||
                              moment(this.horario[j].hora_fin, 'HH:mm')
                              .isBetween(moment(hora+':00', 'HH:mm'), moment(masUnaHora+':00', 'HH:mm')
                           )&&(!this.endHandle)){
                                 
                              if (this.horario[j].diff == null || this.horario[j].diff =='undefined' || this.horario[j].diff == 0){
                                 var ms = moment(this.horario[j].hora_fin, 'HH:mm').diff(moment(this.horario[j].hora_inicio, 'HH:mm'))
                                 var d = moment.duration(ms);
                                 var s = moment(d, 'HH:mm');
                                 this.horario[j].diff= s._i._data.hours;

                                 if (this.horario[j].diff > 0){
                                 
                                       this.diff= this.diff +'<td id="'+this.horario[j].id+'" style="text-align: center;vertical-align: middle;background-color:rgba('+red+','+ green+', '+blue+', 0.30);cursor: pointer;" rowspan="'+this.horario[j].diff+'">hora inicio '+this.horario[j].hora_inicio+' precio '+this.horario[j].precio+'</td>'
                                       mostrar = true
                                 }
                              }else{
                                    if ((this.horario[j].diff != null || this.horario[j].diff !='undefined')  &&  (this.horario[j].diff > 0)){
                                       
                  console.log('entree handdle')
                                       this.horario[j].diff= (this.horario[j].diff - 1)
                                       if (this.horario[j].diff == 0){
                                          this.horario[j].diff= null
                                       }
                                      mostrar = true
                                    }
                              
                              }
                              
                           }
                        }
                       
                    }

                    if (!mostrar){
                        this.diff=  this.diff+'<td id="'+0+'" style="text-align: center;"> </td>'
                        }
                  }
                  if (hora==24){
                     this.endHandle=true
                  }
                  return this.diff  
            },
            editHorario(event){
               if (event.path[0].id != 0){
                 
                  this.horarioId= event.path[0].id
                  this.showModificarHorario= true
               }
            },
            closeModal(){
               this.showModificarHorario=false
            }
        },
    }

</script>