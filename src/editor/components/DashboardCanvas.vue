<template>
    <div>{{ msg }} {{ page }}</div>
</template>

<script>
export default {
    data() {
        return {
            msg: "Hello World from ",
            page: getPage()
        }
    }
}
</script>

<style>

</style>