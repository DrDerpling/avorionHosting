<template>
    <div>
        <div ref="console-screen" class="console">
            <ol v-if="running">
                <li v-for="line in lines">
                    {{ line }}
                </li>
            </ol>
            <h1 v-else>Server is offline</h1>

        </div>
        <input v-if="running" class="console-input" v-model="input" type="text" v-on:keyup.enter="sendCommand">
    </div>

</template>

<script>
    export default {
        props: ['running', 'starting'],

        mounted() {
            if (this.running) {
                this.startConsole();
            }
        },

        data() {
            return {
                lines: [],
                interVal: '',
                input: '',
            }
        },

        methods: {
            startGetConsoleInterval: function () {
                this.interval = setInterval(() => {
                    this.sendGetConsoleRequest()
                }, 2000)
            },

            sendGetConsoleRequest() {
                window.axios.get('console/get/100').then(response => {
                    this.lines = response.data.lines;
                    this.$nextTick(() => {
                        this.updateScroll()
                    })
                })
            },

            sendCommand() {
                window.axios.post('server/command', {
                    command: this.input
                }).then(response => {
                    this.input = '';
                    this.sendGetConsoleRequest();
                    console.log(response)
                })
            },

            startConsole()
            {
                this.startGetConsoleInterval();
                this.sendGetConsoleRequest();
            },

            stopConsole()
            {
                clearInterval(this.interval);
            },

            updateScroll()
            {
                const element = this.$refs['console-screen'];
                element.scrollTop = element.scrollHeight
            }
        },

        watch: {
            running: function (value) {
                if (value) {
                    this.startConsole();
                } else {
                    this.stopConsole();
                }

                return value
            }
        }
    }
</script>

<style>
    .console {
        background-color: #1b1e21;
        color: #bababa;
        height: 240px;
        font-size: 12px;
        overflow-y: scroll;
    }

    .console ol {
        list-style: none;
        padding: 5px;
        margin: 0;
    }

    .console h1 {
        text-align: center;
        height: 100%;
        line-height: 240px;
    }

    .console-input {
        width: 100%;
        background-color: #1b1e21;
        color: #bababa;
        border: 0;
        border-top: white 2px solid;
        outline: none;
        padding: 5px;
    }
</style>
