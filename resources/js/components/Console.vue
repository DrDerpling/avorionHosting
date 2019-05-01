<template>
    <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="card">
            <div class="card-header">
                <button v-if="!running" v-on:click="startServer">Start Server</button>
                <button v-if="running" v-on:click="stopServer">Stop Server</button>
            </div>

            <div class="card-body">
                <console-screen :running="running" :starting="starting">

                </console-screen>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            window.axios.get('server/status').then(response => {
                this.running = response.data.status;
            });
            this.startStatusWatch()
        },
        data() {
            return {
                running: false,
                starting: false,
                interval: '',
                input: ''
            }
        },
        methods: {
            startServer: function () {
                this.starting = true;
                window.axios.post('server/start').then(response => {
                    this.running = true;
                    this.starting = false;
                }).catch(error => {
                    this.running = false;
                    this.starting = false;
                })
            },

            stopServer: function () {
                this.running = false;
                clearInterval(this.interval);
                window.axios.delete('server/stop', {
                    command: this.input
                }).then(response => {
                    this.startStatusWatch()
                }).catch(error => {
                    this.startStatusWatch()
                })
            },

            startStatusWatch: function () {
                this.interval = setInterval(() => {
                    window.axios.get('server/status').then(response => {
                        this.running = response.data.status;
                    })
                }, 2000)
            },
        }
    }
</script>
