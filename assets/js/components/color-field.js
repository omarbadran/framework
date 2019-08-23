/**
 * Color field
 */
Vue.component('color-field', {
    inheritAttrs: false,
    
    props: {
        value: String,
    },

    template: `
        <input type="text">
    `,

    mounted: function () {
        var vm = this;
                
        jQuery(this.$el).val(this.value).wpColorPicker({
            defaultColor: this.value,

            change: function(event, ui) {
                vm.$emit('input', ui.color.toString());
            }
        });
    },

    watch: {
        value: function (value) {
            jQuery(this.$el).wpColorPicker('color', value);
        }
    },

    destroyed: function () {
        jQuery(this.$el).off().wpColorPicker('destroy');
    }
});
