<template>
    <div
        class="editor-wrapper mt-3" 
        :class="rows.length == 0 && 'empty'">

        <div v-if="rows.length == 0" id="content-empty" @click="rowAdd()">
            <div class="text-center " id="add-row-text">
                <p class="text-muted lead">Dashboard is empty</p>
                <p class="h2 text-secondary"><i class="fas fa-plus-square"></i> Add row</p>
            </div>
        </div>

        <div v-if="rows.length != 0">

            <draggable 
                ghost-class="ghost"
                v-model="rows"
                handle=".row-handle"
                :group="{name: 'rows'}"
                @start="drag=true" 
                @end="drag=false"
            >
                <dashboard-row 
                    v-for="(row, index) in rows" 
                    :key="index" 
                    :r_id="index"                 
                    @delete-row="rowRemove( $event )" 
                    @add-column="columnAdd( $event )"
                    >

                <dashboard-column 
                        :columns="row.columns"
                        :r_id="index"
                        @add-element="elementAdd( $event )"
                        @delete-column="columnRemove( $event )"
                        @open-modal-create-element="openModalCreateElement( $event )"
                        v-slot="{elements, c_id}">

                        <dashboard-element
                            :r_id="index"
                            :c_id="c_id"
                            :elements="elements"
                            @delete-element="elementRemove( $event )"
                            @open-modal-edit-element="openModalEditElement( $event )"
                        >
                        </dashboard-element>
                    </dashboard-column>
                </dashboard-row>
            </draggable>

            <div  @click="rowAdd()" class="add-row-area">
                <div class="text-center">
                    <span class="lead"><i class="fas fa-plus-square"></i> Add row</span>
                </div>
            </div>

        </div>

        <modal-element
            :element="element"
            :selection="selection"
            @add-element="elementAdd($event)"
        >
            
        </modal-element>

    </div>
</template>

<script>
import DashboardRow from './DashboardRow.vue';
import DashboardColumn from './DashboardColumn.vue'
import DashboardElement from './DashboardElement.vue'
import ModalElement from './ModalElement.vue'
import draggable from 'vuedraggable'

export default {
    components: {
        DashboardRow,
        DashboardColumn,
        DashboardElement,
        ModalElement,
        draggable
    },
    data() {
        return {
            rows: [
                {
                    columns: [
                        {
                            elements: [
                                {
                                    type: "text",
                                    content: {
                                        title: "This is a Headline",
                                        description: "Foo bar."
                                    }
                                },
                                {
                                    type: "link",
                                    content: {
                                        title: "Click me",
                                        url: "https://google.com"
                                    }
                                }
                            ]
                        }
                    ]
                }
            ],
            msg: "Hello World from ",
            page: getPage(),
            selection: [],
            element: {}
        }
    },
    methods: {
        rowAdd() {
            let row = {
                "columns": []
            }
            this.rows.push(row)
        },
        rowRemove(r_id) {
            this.rows.splice(r_id,1)
        },
        columnAdd(r_id) {
            let columns = this.rows[r_id].columns
            //let col = new DashboardColClass()
            let col = {
                "elements": []
            }
            columns.push(col)
        },
        columnRemove(params) {
            let r_id = params.r_id
            let c_id = params.c_id
            let columns = this.rows[r_id].columns
            columns.splice(c_id, 1)
        },
        elementAdd({r_id, c_id, e_id, el}) {
            let column = this.rows[r_id].columns[c_id]
            column.elements.push(el)

        },
        elementRemove({r_id, c_id, e_id}) {
            let elements = this.rows[r_id].columns[c_id].elements
            elements.splice(e_id,1)

        },
        setSelection(r_id, c_id, e_id) {
            this.selection = {
                r_id: r_id,
                c_id: c_id,
                e_id: e_id
            }
        },
        openModalCreateElement({r_id, c_id}) {
            this.setSelection(r_id, c_id, null)
            this.$bvModal.show('modal-element')
        },
        openModalEditElement({r_id, c_id, e_id}) {
            this.setSelection(r_id, c_id, e_id)
            this.element = this.rows[r_id].columns[c_id].elements[e_id]
            this.$bvModal.show('modal-element')
        }

    },
    computed: {
    }
}
</script>

<style>
    .editor-wrapper {
            min-height: 500px;
            width:100%;
            border:1px solid #dbdbdb;
            border-radius: 4px;
            border-style: dashed;
            position: relative;
            transition: all ease-in-out 250ms;
            background: #f8f9fa;
    }

    .editor-wrapper.empty {
        background: none;
    }

    .editor-wrapper.empty:hover{
        cursor: pointer;
        background: #f8f9fa;
    }

    #content-empty {
        position: absolute;
        height: 100%;
        width:100%;
    }    

    #add-row-text {
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 18px;
        letter-spacing: 1px;
        transition: ease-in-out all 0.3s;

    }
    .editor-wrapper.empty:hover #add-row-text {
        color: #343a40;
    }

    .add-row-area {
        border: 1px dashed #dbdbdb;
        border-radius: 4px;
        color: #dbdbdb;
        margin: 25px;
        padding: 45px;
        cursor: pointer;
        transition: ease-in-out all 0.2s;
    }

    .add-row-area:hover {
        border-color: #343a40;
        color: #343a40;
    }

    .ghost {
        opacity: 0.5;
        background: #c8ebfb;
    }

</style>