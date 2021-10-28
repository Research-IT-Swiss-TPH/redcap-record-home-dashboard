<template>
    <div class="editor-wrapper mt-3">
        <div v-if="!selection" id="content-add-new" class="editor-content" @click="openCreateDashboardModal()">
        
        </div>
        <div v-if="selection">
            Selection: {{selection.name}}
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            msg: "Hello World from ",
            page: getPage()
        }
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
</style>