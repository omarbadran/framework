/**
 * Color field
 */
Vue.component('color-field', {
    inheritAttrs: false,
    
    props: {
        value: {
            type: String,
            default: '#000'
        },
        rgba: Boolean,
        clear: Boolean,
    },

    data: function () {
        return {
            pickr: false
        }
    },

    template: `<div>
        <div ref="color"></div>
    </div>`,

    mounted: function () {
        var vm = this;

        //Init color picker
        vm.pickr = Pickr.create({
            el: vm.$refs.color,
            theme: 'nano',
            default: vm.value,
            defaultRepresentation: 'RGBA',
            components: {
                preview: true,
                opacity: true,
                hue: true,
                interaction: {
                    rgba: vm.rgba,
                    input: true,
                    clear: vm.clear,
                    save: true
                }
            }
        });

        // listen to changes
        vm.pickr.on('change', color => {
            vm.$emit('input', color.toRGBA().toString());
        })
    },

    watch: {
        value: function (value) {
            this.pickr.setColor(value)
        }
    },

    beforeDestroy: function () {
        this.pickr.destroyAndRemove()
    }
});
