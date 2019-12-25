/**
 * Select field
 */
Vue.component('select-field', {
    inheritAttrs: false,
    
    props: {
        value: [String, Array],
        options: Array,
        multiple:{
            type: Boolean,
            default: false
        }
    },

    template: `
        <select style="max-width:100%; width: 200px;"><slot></slot></select>
    `,

    mounted: function () {
        var vm = this;
                
        jQuery(this.$el)
			.select2({ 
                data: vm.options,
                escapeMarkup: markup => markup,
                width: 'element',
                multiple: vm.multiple
            })
			.val(vm.value)
			.trigger('change')
			.on('change', function () {
				vm.$emit('input', jQuery(this).val())
			})
    },

    watch: {
		options: function (options) {
			jQuery(this.$el).empty().select2({ data: options })
		}
    },

    destroyed: function () {
        jQuery(this.$el).off().select2('destroy')
    }
});
