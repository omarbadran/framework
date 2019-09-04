/**
 * Switch
 */
Vue.component('switch-field' , {
    inheritAttrs: false,

    props: {
        value: {
            type: [String, Boolean],
            default: false
        },
    },

    template: `
        <div @click="change" :class="['cf-switch-inner', value.toString()]">
            <span></span>
        </div>
    `,

    methods: {
        change(){                        
            this.$emit('input', !JSON.parse(this.value))
        }
    }
});
