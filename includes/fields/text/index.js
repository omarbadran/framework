/**
 * Text field
 */
Vue.component('text-field', {
    inheritAttrs: false,

    props: {
        value: String,
        placeholder: String
    },
    
    template: `
        <input
            type="text"
            :placeholder="placeholder"
            :value="value"
            @input="$emit('input', $event.target.value)">
    `,
});