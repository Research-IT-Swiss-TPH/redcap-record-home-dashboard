<template>
  <div>
       <b-overlay variant="light"
            blur="50px"
            spinner-variant="dark"
            spinner-small
            opacity="0.95"
            :show="isOverlayed" 
            rounded="sm">
            <template #overlay>
                <loader />
            </template>



       </b-overlay>
  </div>
</template>

<script>
import loader from '../../loader.vue'
export default {
    components: {
        loader
    }
}
</script>

<style>

</style>