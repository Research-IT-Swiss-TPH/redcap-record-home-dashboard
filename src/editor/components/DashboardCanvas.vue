<template>
    <div class="editor-wrapper mt-3" :class="rows.length == 0 && 'empty'">

        <div v-if="rows.length == 0" id="content-empty" @click="rowAdd()">
            <div class="text-center " id="add-row-text">
                <p class="text-muted lead">Dashboard is empty</p>
                <p class="h2"><i class="fas fa-plus-square"></i> Add row</p>
            </div>
        </div>

        <div v-if="rows.length != 0">

            <dashboard-row 
                v-for="(row, index) in rows" 
                :key="index" 
                :r_id="index" 
                @delete-row="rowRemove( $event )" 
                @add-column="columnAdd( $event )">

                <dashboard-column 
                    :columns="row.columns"
                    :r_id="index"
                    @add-element="elementAdd( $event )"
                    @delete-column="columnRemove( $event )"
                    v-slot="{elements, c_id}">

                    <dashboard-element
                        :r_id="index"
                        :c_id="c_id"
                        :elements="elements"
                        @delete-element="elementRemove( $event )"
                        @edit-element="elementEdit( $event )"
                    >
                    </dashboard-element>

                </dashboard-column>

            </dashboard-row>

            <div  @click="rowAdd()" class="add-row-area">
                <div class="text-center">
                    <span class="lead"><i class="fas fa-plus-square"></i> Add row</span>
                </div>
            </div>

        </div>

    </div>
</template>

<script>
import DashboardRow from './DashboardRow.vue';
import DashboardRowClass from "../../classes/DashboardRowClass";
import DashboardColumn from './DashboardColumn.vue'
import DashboardElement from './DashboardElement.vue'

export default {
    components: {
        DashboardRow,
        DashboardColumn,
        DashboardElement
    },
    data() {
        return {
            rows: [],
            msg: "Hello World from ",
            page: getPage()
        }
    },
    methods: {
        rowAdd() {
            let row = new DashboardRowClass()
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
        elementAdd({r_id, c_id}) {
            let column = this.rows[r_id].columns[c_id]
            let el =  {
                        "type": "text",
                        "content": "Hello World"
                    }
            column.elements.push(el)

        },
        elementRemove({r_id, c_id, e_id}) {
            let elements = this.rows[r_id].columns[c_id].elements
            elements.splice(e_id,1)

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
            background: var(--oruga-variant-light);
        }

    .editor-wrapper.empty {
        background: none;
    }

    .editor-wrapper.empty:hover{
        cursor: pointer;
        background: var(--oruga-variant-light)
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
        color: var(--oruga-variant-gray);
        font-size: 18px;
        letter-spacing: 1px;
        transition: ease-in-out all 0.3s;

    }
    .editor-wrapper.empty:hover #add-row-text {
        color: var(--oruga-variant-gray-dark)
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
        border-color: var(--oruga-variant-gray);
        color: var(--oruga-variant-gray);
    }

</style>