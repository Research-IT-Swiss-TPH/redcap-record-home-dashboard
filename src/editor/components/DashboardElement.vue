<template>
  <div>
      <div class="card" v-for="(element, index) in elements" :key="index" >
        <div class="card-header">
            <div class="float-left">
                    <small class="text-muted">Element - # {{ index + 1}} </small>
                </div>
                <div class="editor-row-menu float-right">

                    <b-button  @click="handleEmit('edit-element', index)" size="xs">
                        <i class="fa fa-edit"></i>
                    </b-button>                    

                    <b-button  @click="handleEmit('delete-element', index)" size="xs">
                        <i class="fa fa-trash-alt"></i>
                    </b-button>

                </div>

        </div>
          <div class="card-body">
              {{element.content.title}}
          </div>
      </div>
  </div>
</template>

<script>
export default {
    props: [
        'elements',
        'c_id',
        'r_id'
    ],
    methods: {
        handleEmit(event, e_id) {            
            this.$emit(event, {
                "c_id": this.c_id,
                "r_id": this.r_id,
                "e_id": e_id
            })
        }
    }
}
</script>

<style scoped>
    .card {
        margin-bottom: 15px;
    }
</style>>

</style>