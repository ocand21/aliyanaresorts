<template>
<div id="container-fluid" style="margin-top: 10px;">
    <div class="container-fluid">
        <div id="ui-view">
            <div class="">
                <div class="animated fadein">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-title">

                                </div>
                                <div class="card-body">


                                    <full-calendar defaultView="dayGridMonth" :plugins="calendarPlugins" :weekends="false"
                                    :resources="resources">
                                    </full-calendar>


                                </div>
                                <div class="card-footer">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'

export default {
    components: {
        FullCalendar
    },
    data() {
        return {
            calendarPlugins: [dayGridPlugin],
            resources:{
              url: '/api/admin/booking/calendar',
              method: 'GET',
            }

        }
    },
    methods: {
        loadBooking() {
            axios.get('/api/admin/booking/calendar').then(({
                data
            }) => (this.eventSources = data));
        }
    },
    created() {
        this.$Progress.start();
        // this.loadBooking();
        this.$Progress.finish();
    }
}
</script>
