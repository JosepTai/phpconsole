<template>
    <div>
        <h3>Welcome</h3>
        <a class="waves-effect waves-light btn" v-on:click.prevent.stop="loadEditor()">button</a>
        <CodeComponent :hasCodes="hasCodes"></CodeComponent>
    </div>
</template>

<script>

    let CodeComponent = require('../pages/CodeComponent.vue');

    export default {
        components: {CodeComponent},
        name: "Contents",
        data() {
            return {
                hasCodes: false
            }
        },

        methods: {
            loadEditor() {
                console.log('Clicked');
            }
        }
    }
</script>

<style scoped>

</style>