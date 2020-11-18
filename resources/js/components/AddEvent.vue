<template>
    <div class="addForm" v-show='showForm'>
        <form @submit.prevent>
            <div class="input-group">
                <label for="title">Guest: </label>
                <input type="text" name="title" class="form-control" v-model="event.name">
            </div>
            <div class="row mt-2">
                <div class="input-group col-md-6">      
                    <label for="start">Start Date:</label>
                    <input type="date" name="start" class="form-control" v-model="event.start_date">
                </div>
                <div class="input-group col-md-6">      
                    <label for="end">End Date:</label>
                    <input type="date" name="end" class="form-control" v-model="event.end_date">
                </div>
            </div>
            <template v-if="addingMode">
                <button @click="addEvent" class="btn btn-primary" type="submit">Add</button>
            </template>
            <template v-else>
                <button class="btn btn-sm btn-success" @click="updateEvent">Update</button>
                <button class="btn btn-sm btn-danger" @click="deleteEvent">Delete</button>
                <button class="btn btn-sm btn-secondary" @click="clearFields">Cancel</button>                
            </template>
        </form>
    </div>
</template>

<script>

export default {
    props:
       [ 'event',
        'addingMode'],
    data () {
        return {
            url: 'api/calendar',
            showForm:true,
        }
    },
    methods: {
        deleteEvent: async function() {
            try {
                let resp = await fetch(this.url+'/'+this.event.id, {
                    method: "DELETE",
                    headers: {
                        "Content-type": "application/json"
                    }
                });
                if (resp.ok) {
                    resp = await resp.json();
                    console.log(resp);
                    this.clearFields();
                }
            } catch (err) {
                console.log(err);
            }            
        },
        updateEvent: async function() {
            try {
                let resp = await fetch(this.url+'/'+this.event.id, {
                    method: "PUT",
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
                    this.clearFields();
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
                    this.clearFields();
                }
            } catch (err) {
                console.log('error');
                console.log(err);
            }            
        },
        clearFields() {
            this.event.id = 0;
            this.event.name = '';
            this.event.start_date = '';
            this.event.end_date = '';
            this.$emit('update');                    
        }
    },
    created() {
    },
}
</script>

<style lang="scss" scoped>
    label {
        margin-right:10px;
        align-self: flex-end;
    }
    .addForm {
        background-color:#fff;
        padding:5px;
        border:2px solid #ccc;
        position: relative;;
        margin-bottom:10px;
    }
</style>
