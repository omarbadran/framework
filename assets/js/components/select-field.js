/**
 * Select field
 */
Vue.component('select-field', {
    inheritAttrs: false,
    
    props: {
        value: String,
        options: Array,
        select2Args: Object,
    },

    template: `
        <select><slot></slot></select>
    `,

    mounted: function () {
        var vm = this;
                
        jQuery(this.$el)
			.select2({ 
                data: this.options,
                escapeMarkup: markup => markup,
                ...vm.select2Args
            })
			.val(this.value)
			.trigger('change')
			.on('change', function () {
				vm.$emit('input', this.value)
			})
    },

    watch: {
		value: function (value) {
			// update value
			jQuery(this.$el)
				.val(value)
				.trigger('change')
		},
		options: function (options) {
			// update options
			jQuery(this.$el).empty().select2({ data: options })
		}
    },

    destroyed: function () {
        jQuery(this.$el).off().select2('destroy')
    }
});
