<template>
  <div>
    <AddEvent :event="event" :addingMode="addingMode" v-on:update="update"/>
    <FullCalendar :options="calendarOptions"/>
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
  data () {
    return {
      calendarOptions: {
        plugins: [
          DayGridPlugin,
          InteractionPlugin // needed for dateClick
        ],
        initialView: 'dayGridMonth',
        headerToolbar: {
          left: 'prev next',
          center: 'title',
          right: 'today'
        },
        editable: true,
        selectable: true, 
        select: this.handleSelect,  
        events:[],
        eventClick:this.showEvent,
      },
      event: {
        name: '',
        start_date:'',
        end_date:'',
      },
      indexToUpdate:'',
      url: 'https://rentals802.heroku.com/api/calendar',
      addingMode:true,
      indexToUpdate: 0,
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
    this.fetchEvents();
  },
}
</script>

<style lang="scss" scoped>
.fc-event {
  background-color: #3a1872;
  border-color: #2e135a;
  color: #fff;
}
.fc-button {
  color:#fff;
  background-color:#3a1872;
}
.fc-button:hover {
  background-color:#693bb3;
}
.fc-title {
  color: #fff;
}

.fc-title:hover {
  cursor: pointer;
}
</style>