<template>
<div>
    <span v-if="type != 'list'">
        <span  class="ml-1" v-for="(value, key) in element.content" :key="key">
        <abbr :title="value.substring(0, 50)" class="initialism text-lowercase text-monospace">
            <b-badge pill variant="info">{{key}}</b-badge>
        </abbr>       
      </span>
    </span>
    <span v-else-if="type == 'list'">
        <span  class="ml-1" v-for="(element, key) in element.content" :key="key">
            <abbr :title="element.value" class="initialism text-lowercase text-monospace">
                <b-badge pill variant="info">{{ element.title }}</b-badge>
            </abbr>
        </span>
    </span>
</div>
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