<template>
<div>

    <span v-if="type == 'text'">
        <span  class="ml-1" >
        <abbr v-if="element.content.title.length > 0" :title="element.content.title.substring(0, 50)" class="initialism text-lowercase text-monospace">
            <b-badge pill variant="info">title</b-badge>
        </abbr>
        <b-badge v-else pill variant="light">empty</b-badge>
      </span>
        <span v-if="element.content.decoration"  class="ml-1">
        <abbr :title="element.content.decoration.join(', ')" class="initialism text-lowercase text-monospace">
            <b-badge pill variant="info">deco</b-badge>
        </abbr>       
      </span>
    </span>

    <span v-else-if="type == 'link'">
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
    <span v-else-if="type == 'table'">
        <span>
            <abbr :title="element.content.instrument" class="initialism text-lowercase text-monospace">
                <b-badge pill variant="info">instrument</b-badge>
            </abbr>
        </span>
        <span v-if="element.content.event">
            <abbr :title="element.content.event" class="initialism text-lowercase text-monospace">
                <b-badge pill variant="info">event_id</b-badge>
            </abbr>
        </span>         
        <span>
            <abbr :title="element.content.columns.join(', ')" class="initialism text-lowercase text-monospace">
                <b-badge pill variant="info">columns</b-badge>
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