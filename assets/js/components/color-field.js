/**
 * Color field
 */
Vue.component('color-field', {
    inheritAttrs: false,
    
    props: {
        value: String,
    },

    template: `
        <div>
            <input ref="color" type="text">
        </div>    
        `,

    mounted: function () {
        var vm = this;
                
        jQuery(this.$refs.color).val(this.value).wpColorPicker({
            defaultColor: this.value,

            change: function(event, ui) {
                vm.$emit('input', ui.color.toString());
            }
        });
    },

    watch: {
        value: function (value) {
            jQuery(this.$refs.color).wpColorPicker('color', value);
        }
    },

    beforeDestroy: function () {
        console.log(this.$refs.color);
        
        jQuery(this.$refs.color).remove();
    }
});
