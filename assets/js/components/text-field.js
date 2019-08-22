/**
 * Color
 */
Vue.component('cf-text-field', {
    props: ['value'],
    
    template: `<input 
            class="cf-text-input"
            type="text"
            v-bind:value="value"
            v-on:input="$emit('input', $event.target.value)"
    >`,
});
