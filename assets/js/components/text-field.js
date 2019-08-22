/**
 * Text field
 */
Vue.component('text-field', {
    props: {
        value: String,
        placeholder: String
    },
    
    template: `<input 
            class="cf-text-field"
            type="text"
            :placeholder="placeholder"
            :value="value"
            @input="$emit('input', $event.target.value)"
    >`,
});
