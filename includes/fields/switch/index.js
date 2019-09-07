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

    created: function(){
        // Wordpress saves the Boolean value as a string :(
        if(typeof this.value === 'string'){
            this.$emit('input', JSON.parse(this.value))
        }
    },

    methods: {
        change(){                        
            this.$emit('input', !this.value)
        }
    }
});
