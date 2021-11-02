<template>
  <div>
  <b-modal 
    @ok="handleOk()"
    centered scrollable  
    id="modal-element" 
    :title="modalStrings.title">

      <div class="text-center mt-3">

        <b-form-group>
        <b-form-radio-group
            id="btn-radios-2"
            v-model="selected.type"
            :options="options.type"
            button-variant="outline-secondary"
            size="lg"
            name="radio-btn-outline"
            buttons>
        </b-form-radio-group>
        </b-form-group>

      </div>

      <div style="min-height:300px;" class="p-4">
        <div v-if="selected.type=='text'">
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

        <div v-if="selected.type=='link'">
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

        <div v-if="selected.type=='list'">
            <b-input-group>

                <b-input-group-prepend>
                    <b-input-group-text>
                        <i  class="fa fa-grip-vertical"></i>
                    </b-input-group-text>                                        
                </b-input-group-prepend>
                
                 <b-form-input 
                    placeholder="title">
                 
                 </b-form-input>
                 <b-form-select v-model="selected.instrument" placeholder="" :options="options.instruments">
                    <template #first>
                        <b-form-select-option :value="null" disabled>Instrument</b-form-select-option>
                    </template>                     
                 </b-form-select>

                <b-form-select v-model="selected.field" :options="options.fields" :disabled="selected.instrument == null">
                    <template #first>
                        <b-form-select-option :value="null" disabled>Field</b-form-select-option>
                    </template>                      
                </b-form-select>

               <b-input-group-append>
                    <b-button variant="outline-danger">
                        <i class="fa fa-trash-alt"></i>
                    </b-button>
                </b-input-group-append>
                
            </b-input-group>            
        </div>
      </div>

        <div v-if="selected.type=='table'">
            Hello Success
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
        'selection',
        
    ],
    data() {
      return {
        selected: {
            type: "text",
            instrument: null,
            field: null,
            repeating: null
        },
        options: {
            type: [
                    { html: '<i class="fas fa-align-left"></i> Text', value: 'text' },
                    { html: '<i class="fas fa-link"></i> Link', value: 'link' },
                    { html: '<i class="fas fa-th-list"></i> List', value: 'list' },
                    { html: '<i class="fas fa-table"></i> Table', value: 'table' }
                ],
            instruments: [
                { value: "base", text: 'base' },
                { value: "weekly", text: 'weekly' },
                { value: "monthly", text: 'monthly' },
                { value: "other", text: 'other' },
            ],
            fields: [
                "field_1",
                "field_2",
                "field_3"
            ]
        },
        content: {
            text: {
                title: "",
                description:""
            },
            link: {
                title: "",
                url: ""
            },
            list: [
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
        },
        modalElementType() {
            if(this.modalCase == 'case-edit') {
                this.selected.type = "table"
            }
        }
        
    },
    methods: {
        handleOk() {

            console.log(this.selected.type)
            console.log(this.content[this.selected.type])
            let el = {
                "type": this.selected.type,
                "content": this.content[this.selected.type]
            }
            if(this.selection.e_id == null) {

                this.$emit('add-element', {
                    "c_id": this.selection.c_id,
                    "r_id": this.selection.r_id,
                    "el": el
                })

            } else {
                this.$emit('edit-element', {
                    "c_id": this.selection.c_id,
                    "r_id": this.selection.r_id,
                    "e_id": this.selection.e_id,
                    "el": el
                })
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