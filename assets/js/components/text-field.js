/**
 * Text field
 */
Vue.component('text-field', {
    props: {
        value: String,
        placeholder: String
    },
    
    template: `
        <div>
            <input
                type="text"
                :placeholder="placeholder"
                :value="value"
                @input="$emit('input', $event.target.value)">
        </div>
    `,
});
