<template>
    <div v-if="horario.length>0 && showFormH" class="default-content">
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
                             <th>Sábado</th>
                           </tr>
                         </thead>
                         <tbody v-if="loadH">
                        
                           <tr v-for="hora in 49" v-html="handle(hora)" @click.stop ="editHorario($event)"  >
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
                     <edit-horario :horarioId="horarioId" :publicacionId="publicacionId" :horariosId= "horariosId"> </edit-horario>
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
                type: Number
            },
            horariosId:{
              type: Array
            }
        },
    	data(){
            return {
                horariosSelect: [],
                horario: [],
                hora:0,
                horarioId: 0,
                loadH: false,
                showFormH : false,
                endHandle: false,
                red: Math.floor(Math.random() * (200 - 50)),
                green: Math.floor(Math.random() * (200 - 50)),
                blue: Math.floor(Math.random() * (200 - 50)),
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
            this.$events.on('showFormH', () => this.showFormH = true);
        },
        methods: {
            getHorarios: function(){
               this.endHandle=false;
               this.horario=[];
                 if (this.publicacionId != null){
                    this.$http.get('api/publicacion/'+this.publicacionId+'/horarios')
                    .then(response =>{
                          this.horario = response.data.horarios
                          this.loadH =true;
                           this.showFormH = true;
                      }, response => {
                          if(response.status === 404){
                              this.$toast.error({
                                  title:'¡Error!',
                                  message:'No se han cargado sus horarios. :('
                              });
                          }
                      });
                    } else{   
                        if ((this.publicacionId == null)&&(this.horariosId!=[])){
                          for (var i = 0; i < this.horariosId.length; i++) {
                          this.$http.get('api/horarios/'+this.horariosId[i])
                            .then(response =>{
                                  this.horario.push(response.data)
                                  
                                  this.showFormH = true;
                              }, response => {
                                  if(response.status === 404){
                                      this.$toast.error({
                                          title:'¡Error!',
                                          message:'No se han cargado sus horarios. :('
                                      });
                                  }
                              });
                          }
                        }
                      this.loadH =true;
                  
                }

            },
            handle(hora) {
               var mostrar = false
                var media = false
            if (hora>1){
                this.diff= ''
               if (hora%2 != 0){
               media= true
                  hora= parseInt(hora/2)
               }else{
                media=false
                hora= hora/2
                 this.diff= this.diff +'<td style="text-align: center;" rowspan="2">'+hora +':00hs</td>';
              }
               var masUnaHora = hora +1;
               var diferencia_hora = 0;
                for (var i = 0; i < this.dias.length; i++) 
                { 
                  mostrar = false
                  for ( var j = 0;(j < this.horario.length); j++) 
                  {
                     if(this.horario[j].dia == this.dias[i].nombre)
                     {
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
                        ) ||  ((moment(hora+':30', 'HH:mm').isSame(moment(this.horario[j].hora_inicio, 'HH:mm'))) && media) )
                        { 
                          
                          
                           if (this.horario[j].diff == null || this.horario[j].diff =='undefined' || this.horario[j].diff == 0)
                           {
                              var ms = moment(this.horario[j].hora_fin, 'HH:mm').diff(moment(this.horario[j].hora_inicio, 'HH:mm'))
                              var d = moment.duration(ms);
                              var s = moment(d, 'HH:mm');
                              diferencia_hora = s._i._data.hours
                              var hora_final= hora+diferencia_hora
                              this.horario[j].diff= diferencia_hora*2;
                              if (moment(hora+':30', 'HH:mm').isSame(moment(this.horario[j].hora_inicio, 'HH:mm')) && (moment(hora_final+':30', 'HH:mm').isSame(moment(this.horario[j].hora_fin, 'HH:mm')))){
                              }else{
                                    if (moment(hora+':30', 'HH:mm').isSame(moment(this.horario[j].hora_inicio, 'HH:mm')) && media)
                                    {
                                      this.horario[j].diff=this.horario[j].diff+1
                                    }
                                    if (moment(hora_final+':30', 'HH:mm').isSame(moment(this.horario[j].hora_fin, 'HH:mm')) && this.horario[j].diff != 0 ) {
                                      this.horario[j].diff=this.horario[j].diff+1
                                      hora_final = 0
                                    }
                              }
                              if (this.horario[j].diff != null){
                                    this.diff= this.diff +'<td id="'+this.horario[j].id+'" style="text-align: center;vertical-align: middle;background-color:rgba('+this.red+','+ this.green+', '+this.blue+', 0.30);cursor: pointer; width: 120px; border-color: #FFFFFF; border-width:1px; border-style: solid" rowspan="'+this.horario[j].diff +'">Inicio '+this.horario[j].hora_inicio+'hs. - Fin  '+this.horario[j].hora_fin+'hs. Precio: $'+this.horario[j].precio+'</td>'
                                    mostrar = true
                                  }
                          }else{
                                 if ((this.horario[j].diff != null || this.horario[j].diff !='undefined')  &&  (this.horario[j].diff > 0))
                                 {
                                    this.horario[j].diff= (this.horario[j].diff - 1)
                                    if (this.horario[j].diff == 0){
                                       this.horario[j].diff= null
                                    }else{
                                      mostrar = true
                                    }
                                 }
                          }
                        }
                      }
               
                  }
                  if (!mostrar){
                     this.diff=  this.diff+'<td id="'+0+'" style="text-align: center;width: 120px;"> </td>'
                   }
              }  
              return this.diff
            }
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
         watch:
            {
                'this.horariosSelect'(){
                    this.horario= this.horariosSelect
                }
            }
    }

</script>