/**
 * Icon field
 */

Vue.component('icon-field', {
    inheritAttrs: false,

    props: {
        value: String,
    },
    
    data: function () {
        return {
            icons: materialIconsList
        }
    },

    template: `
        <select-field v-if="icons" @input="$emit('input', $event)" :options="icons" :value="value"/>
    `,
});
