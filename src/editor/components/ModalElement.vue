<template>
  <div>
  <b-modal centered scrollable  id="modal-element" :title="modalStrings.title">

      <div class="text-center mt-3">

        <b-form-group>
        <b-form-radio-group
            id="btn-radios-2"
            v-model="selected"
            :options="options"
            :aria-describedby="ariaDescribedby"
            button-variant="outline-secondary"
            size="lg"
            name="radio-btn-outline"
            buttons>
        </b-form-radio-group>
        </b-form-group>

      </div>

      <div style="min-height:300px;" class="p-4">
        <div v-if="selected=='text'">
            <b-form-group
                label="Title"
                label-cols-lg="4"
                content-cols-lg="8">
                <b-form-input v-model="content.text.title"></b-form-input>
                </b-form-group>

            <b-form-group
                label="Description"
                label-cols-lg="4"
                content-cols-lg="8">
                <b-form-textarea       
                rows="5"
                max-rows="5" 
                v-model="content.text.description">
                </b-form-textarea>

            </b-form-group>
        </div>

        <div v-if="selected=='link'">
            <b-form-group
                label="Title"
                label-cols-lg="4"
                content-cols-lg="8">
                <b-form-input v-model="content.link.title"></b-form-input>
                </b-form-group>

            <b-form-group
                label="URL"
                label-cols-lg="4"
                content-cols-lg="8">
                <b-form-input       
                v-model="content.link.url">
                </b-form-input>
            </b-form-group>
        </div>        
      </div>

        <div v-if="selected=='data'">
            
        </div>

    <template #modal-footer="{ ok, cancel }">
      <b-button variant="secondary" @click="cancel()">
        Cancel
      </b-button>
      <b-button variant="success" @click="ok()">
        {{ modalStrings.ok }}
      </b-button>
    </template>

  </b-modal>
  </div>
</template>

<script>
export default {
    props:[
        'element',
        'selection'
    ],
    data() {
      return {
        selected: 'text',
        options: [
          { html: '<i class="fas fa-align-left"></i> Text', value: 'text' },
          { html: '<i class="fas fa-link"></i> Link', value: 'link' },
          { html: '<i class="fas fa-database"></i> Data', value: 'data' },
          { html: '<i class="fas fa-table"></i> Table', value: 'table' }
        ],
        content: {
            text: {
                title: "",
                description:""
            },
            link: {
                title: "",
                url: ""
            },
            data: [
                {
                    title: "",
                    field: ""    
                }
            ],
            table: {
                instrument: "",
                amount: 5,
                flag: ""
            }
        }
      }
    },    
    computed: {
        modalCase() {
            if(this.selection.e_id == null) {
                return 'case-new'
            } else {
                return 'case-edit'
            }
        },
        modalStrings() {
            if(this.modalCase == 'case-new') {
               return {
                        "title": "Create Dashboard Element",
                        "ok": "Create"
                   }
            } else {
                return {
                        "title": "Edit Dashboard Element",
                        "ok": "Save"
                   }
            }
        }
    }

}
</script>

<style>
.btn-check {
	position: absolute;
	clip: rect(0,0,0,0);
	pointer-events: none;
}
</style>