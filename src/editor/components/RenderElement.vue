<template>
    <span>
        <span class="ml-1" v-for="(value, key) in element.content" :key="key">
        <abbr :title="value.substring(0, 20)" class="initialism text-lowercase text-monospace">
            <b-badge pill variant="info">{{key}}</b-badge>
        </abbr>       
      </span>
    </span>
</template>

<script>
export default {
    props: [
        'element'
    ],
    computed: {
        type() {
            return this.element.type
        }
    }
}
</script>

<style>

</style>