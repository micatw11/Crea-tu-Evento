<template>
	<div class="col-sm-12">
		<vue-event-calendar 
			:events="dataEvents">				
	      <template scope="props">
	        <div v-for="(event, index) in props.showEvents" class="event-item">
	          <!-- In here do whatever you want, make you owner event template -->
	          <h3 class="title">{{event.title}}</h3>
	          <p>{{event.date}}</p>
	          <p>Horario: {{ event.hora_inicio }}hs - {{ event.hasta_fin}}hs</p>
	        </div>
	      </template>
		</vue-event-calendar>
	</div>
</template>
<script>
	export default {
		data(){
		    return {
				dataEvents: [{
					date: '2017/10/15',
					title: 'Foo',
					desc: 'longlonglong description',
					hora_inicio: '16:00',
					hasta_fin: '21:30'
				},{
					date: '2017/10/12',
					title: 'Bar',
					desc: 'longlonglong description',
					hora_inicio: '16:00',
					hasta_fin: '21:30'
				}]
		    }
		}
	}
</script>