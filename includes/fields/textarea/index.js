/**
 * Textarea field
 */
Vue.component('textarea-field', {
    inheritAttrs: false,

    props: {
        value: String,
        placeholder: String
    },
    
    template: `
        <textarea
            :value="value"
            @input="$emit('input', $event.target.value)"
            :placeholder="placeholder"
            rows="6" 
            cols="40"
        ></textarea>
    `,
});
