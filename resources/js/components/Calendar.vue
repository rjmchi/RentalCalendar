<template>
  <div>
    <a v-show="showForm" href="/fulllist">Show all</a>
    <FullCalendar :options="calendarOptions"/>
    <AddEvent v-show="showForm" :event="event" :addingMode="addingMode" v-on:update="update"/>
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import DayGridPlugin from '@fullcalendar/daygrid'
import InteractionPlugin from '@fullcalendar/interaction'
import { formatDate } from '@fullcalendar/core'

import AddEvent from './AddEvent.vue'

export default {
    components: {FullCalendar, AddEvent},
    props: ['user'],
  data () {
    return {
      calendarOptions: {
        plugins: [
          DayGridPlugin,InteractionPlugin
        ],
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev next',
          center: 'title',
          right: 'today'
        },
        events:[],
        eventColor: '#ff5555',
      },
      event: {
        name: '',
        start_date:'',
        end_date:'',
      },
      indexToUpdate:'',
      url: 'api/calendar',
      addingMode:true,
      indexToUpdate: 0,
      showForm: false,
    }
  },
  computed: {
  },

  methods: {
    showEvent(arg){
      this.addingMode = false;
      const { id, title, start, end } = this.calendarOptions.events.find(
        event => event.id === +arg.event.id
      );
      console.log(id, title, start,end);
      this.indexToUpdate = id;
      this.event = {
        id: id,
        name: title,
        start_date: start,
        end_date: end
      };
    },
    handleSelect(selectInfo) {
      this.event.start_date = selectInfo.startStr;
      this.event.end_date = selectInfo.endStr;
    },
    fetchEvents: async function() {
      try {
        let resp = await fetch(this.url , { method: "GET" });
        console.log(resp.status);
        if (resp.ok) {
          resp = await resp.json();
          this.calendarOptions.events = resp.data;
          if (this.user == 'guest') {
            this.calendarOptions.events.forEach((e)=>e.title='Booked');
          }
        }
      } catch (err) {
        console.log(err);
      }
    },
    addEvent: async function() {
      try {
        let resp = await fetch(this.url , {
          method: "POST",
          body: JSON.stringify({
            title: this.event.name,
            start: this.event.start_date,
            end: this.event.end_date
          }),
          headers: {
            "Content-type": "application/json"
          }
        });
        if (resp.ok) {
          resp = await resp.json();
          console.log(resp);
          this.fetchEvents();
          }
        } catch (err) {
          console.log(err);
      }
    },
    update: function(){
      console.log('update');
      this.addingMode=true;
      this.fetchEvents();
    }
  },
  created() {
    if (this.user != 'guest'){
      this.calendarOptions.editable =true;
      this.calendarOptions.selectable = true;
      this.calendarOptions.select= this.handleSelect;
      this.calendarOptions.eventClick=this.showEvent;
      this.showForm=true;
      this.calendarOptions.eventColor = '#3a1872';
    }
    this.fetchEvents();
  },
}
</script>

<style lang="scss" scoped>
div {
  max-width:600px;
  margin:0 auto;
}
</style>