<template>
  <div>

    <div v-if="element.type == 'text'">        
        <h2 v-if="element.content.title" class="lead mt-3" :class="textDecorations(element.content.decoration)">{{ element.content.title }}</h2>
        <div class="empty-text" v-else></div>
    </div>

    <div v-else-if="element.type=='link'">
      <div class="mt-1">
        <b-skeleton v-if="isRendering" type="button" height="37.5px" width="100%"></b-skeleton>
        <b-button 
            v-else-if="!isRendering && render.length > 0"
            :href="render"
            :target="element.content.target || '_self'"
            :variant="element.content.variant"
            class="text-center"
            block
            size="lg"
         >
         <i v-if="element.content.icon && element.content.icon.length > 0" :class="'fa fa-'+element.content.icon"></i>
        {{ element.content.title }}</b-button>
        <b-alert v-else show variant="warning"><b>Error:</b> Invalid URL</b-alert>
      </div>
 
    </div>

    <div v-else-if="element.type=='list'">
        <b-list-group class="mt-1">
            <b-list-group-item
                class="d-flex justify-content-between align-items-center"
                v-for="(li, idx) in element.content" :key="li.title" >

                <template v-if="isRendering">
                  <b-skeleton width="75px"></b-skeleton>
                  <b-skeleton width="50px"></b-skeleton>
                </template>
                
                <template v-else>
                 <small class="font-weight-bold">{{ li.title}}</small>
                 <span v-html="render[idx]"></span>
                </template>

        </b-list-group-item>
        </b-list-group>

    </div>

    <div v-else-if="element.type == 'table'">
        <template v-if="isRendering">
          <b-skeleton-table
            :rows="perPage"
            :columns="element.content.columns.length || 3"
            :table-props="{ striped: true }"
          ></b-skeleton-table>
        </template>
        <template v-else>
          <b-table
            id="my-table"
            :per-page="perPage"
            striped
            hover
            size="sm"
            :current-page="currentPage"
            :items="render">
            <template #cell()="{value}">
              <span v-html="value"></span>
            </template>
          </b-table>
        <b-pagination
          v-model="currentPage"
          :total-rows="render.length"
          variant="info"
          :per-page="perPage"
          aria-controls="my-table"
        ></b-pagination>                    
        </template>
    </div>

  </div>
  
</template>

<script>
export default {
    props: [
        'element'
    ],
     data() {
      return {
        
        isRendering: true,
        render: "",
        error: "",
        perPage: 5,
        currentPage: 1
      }
    },
    methods: {
      async renderElement() {
        
          this.axios({
            params: {
              action: 'render-element-content',
              type: this.element.type,
              content: JSON.stringify(this.element.content),
              params: stph_rhd_getBaseParametersFromBackend(),
              event: null
            }
          })
          .then( response => {
            let json = response.data
            this.render = response.data            
    
          })
          .catch(error => {
            console.log(error)
          })
          .finally(()=> {
            setTimeout(()=> {
              this.isRendering = false
            }, 750) // smooth loading
          })
      },
      textDecorations(content){
        let textDecorations = ""
        if(content) {
        content.forEach(decoration => {
          textDecorations += "decoration-" + decoration +" "
        });
        }
        
        return textDecorations

      },
      dynamicSlot(input){
        return "cell("+input+")"
      }
    },
    mounted() {

      if( ["link", "list", "table"].includes(this.element.type)) {
        this.renderElement()
      }      
      
    }
}
</script>

<style scoped>
  .alert.alert-warning {
    padding: .5rem 1rem;
  }
  .empty-text {
    height: 24px;
    width:100%;
    margin-top: 1rem;
    margin-bottom:
     0.5rem;
  }

  .decoration-bold {
    font-weight: bold;
  }

  .decoration-italic {
    font-style: italic;
  }

  .decoration-underline {
    text-decoration: underline;
  }

</style>