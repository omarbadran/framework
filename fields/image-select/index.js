/**
 * image select field
 */
Vue.component('image-select-field', {
    inheritAttrs: false,

    props: {
        value: String,
        options: {
            type: Array,
            default: () => []
        }
    },
    
    template: `
        <div>
            <div v-for="option in options" class="cf-image-select-item" :class="{cf_selected : option.id === value}" @click="$emit('input', option.id)">
                <img :src="option.url" :title="option.text"/>
                <small> {{option.text}} </small>
            </div>
        </div>
    `,
});
