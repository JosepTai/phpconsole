<template>
    <div>
        <h4>Code</h4>
        <a href="" class="btn purple darken-2" v-on:click.prevent.stop="demoTest()">Test</a>
    </div>
</template>

<script>
    export default {
        name: "CodeComponent",
        props: ['hasCodes'],
        methods: {
            demoTest() {
                axios.post('/check', {contents: "echo '"})
                    .then(response => {
                        let result = response.data.message;
                        if (result === 'undefined') {
                            result = response.data;
                        }
                        console.log(result);
                    })
                    .catch(error => {
                        console.log(error.data);
                    })
            }
        }
    }
</script>

<style scoped>

</style>