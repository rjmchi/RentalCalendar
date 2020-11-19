<template>
    <div>
        <h1>Full List</h1>
        <a href="/">Calendar View</a>
        <p v-for="(event,index) in events" v-bind:key="index"> {{event.start}} {{event.end}} {{event.title}}</p>
    </div>
</template>

<script>

export default {
    data () {
        return {
            events: [],
            url:'/api/calendar',
        }
    },
    methods: {
        fetchEvents: async function() {
            try {
                let resp = await fetch(this.url , { method: "GET" });
                console.log(resp.status);
                if (resp.ok) {
                    resp = await resp.json();
                    this.events = resp.data;
                }
            } catch (err) {
                console.log(err);
            }
        },
    },
    created() {
        this.fetchEvents();
    },
}
</script>

<style lang="scss" scoped>

</style>