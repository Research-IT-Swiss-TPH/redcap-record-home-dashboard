<template>
  <ul class="list-group">
      <li class="list-group-item" v-for="(value, key) in element.content" :key="key">
        <span class="badge badge-dark text-monospace">{{key}}</span>
        <span class="text-muted">{{ value.substring(0, 20) }}</span>
      </li>
  </ul>
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