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
            icons: false
        }
    },

    template: `
        <select-field v-if="icons" :options="icons" :value="value" :select2Args="{width: '225px'}"/>
    `,

    created: function () {
        vm = this;

        jQuery.getJSON( CoraFrameworkData.url + 'assets/vendor/material-icons/icons.json' ,
            function( data ) {                
                var icons = data.categories.map(category => { 
                    var newCategory = {
                        text: category.name,
                        children: category.icons.map( icon => {
                            return {
                                id: icon.ligature,
                                text: `
                                    <div class="cf-icon-field-item">
                                        ${icon.name}
                                        <i class="material-icons material-icons-${icon.ligature}"></i>
                                    </div>
                                `,
                            }
                        })
                    }

                    return newCategory;
                });
                
                vm.icons = icons;
            }
        )
    }
});
