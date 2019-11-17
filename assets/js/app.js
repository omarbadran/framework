var CoraFramework = {

    /**
     * DOM element to mount on
     * 
     */
    el: '#cf',

    /**
     * App Data
     * 
     */
    data: {
        nonce: CoraFrameworkData.nonce,
        config: CoraFrameworkData.config,
        sections: CoraFrameworkData.sections,
        fields: CoraFrameworkData.fields,
        values: CoraFrameworkData.values,
        translation: CoraFrameworkData.translation,
        activeSection: CoraFrameworkData.sections[0]['id'],
        initialized: false,
        loading: false,
        alert: false,
    },

    /**
     * App Methods
     * 
     */
    methods: {

        /**
         * Decide to show or hide a field
         * 
         * @since 1.0.0
         */
        showField: function (field) {
            const vm = this;
            let res = true;

            // Current section?
            if( field.section  !== vm.activeSection ){
                return false;
            }

            // Check conditions
            if (field.condition) {

                // Multiple conditions
                if ( Array.isArray(field.condition[0]) ) {
                    
                    for (let index = 0; index < field.condition.length; index++) {
                        let compareValues = field.condition[index][2];
                        let targets = vm.values[field.section][field.condition[index][0]];
                        
                        let subres = eval(`compareValues ${field.condition[index][1]} targets`);
                        res = subres;
                        
                        if (subres == false) {
                            break;
                        }
                        
                    }

                }

                // Single conditions
                else {
                    
                    let compareValue = field.condition[2];
                    let target = vm.values[field.section][field.condition[0]];
                    
                    res = eval(`compareValue ${field.condition[1]} target`);
                        
                }

            }
            
            return res;
        },

        /**
         * Show Alert.
         * 
         * @since 1.0.0
         */
        showAlert: function (style, message) {
            const vm = this;

            vm.alert = {};
            vm.alert.style = style;
            vm.alert.message = message;
            
            setTimeout(() => {
                vm.alert = false;
            }, 3000);
        },

        /**
         * Save Data.
         * 
         * @since 1.0.0
         */
        save: function (args) {
            const vm = this;
            
            let data;

            vm.loading = true;

            if ( args.data ) {
                data = args.data;
            } else {
                data = vm.values;
            }

            var postData = {
                action: 'cora_save',
                security: vm.nonce,
                data: data
            }

            // Ajax save
            jQuery.ajax({
                type: "POST",
                data: postData,
                dataType:"json",
                url: ajaxurl,
                // Success
                success: function () {
                    vm.loading = false;
                    vm.showAlert('success', vm.translation.data_saved);

                    if (typeof args.success === 'function') {
                        args.success();
                    }
                },
                // Error
                error: function () {
                    vm.loading = false;
                    vm.showAlert('warning', vm.translation.error);
                    
                    if (typeof args.error === 'function') {
                        args.error();
                    }    
                },
            }); 

        }

    },
    
    /**
     * Computed Properties
     * 
     */
    computed: {
        
        /**
         * Active section title
         * 
         * @since 1.0.0
         */
        activeSectionTitle: function () {
            let activeSection = this.activeSection;

            let section = this.sections.find(function(element) {
                return element.id == activeSection;
            });

            return section.title;
        }

    },

    /**
     * Mounted
     * 
     */
    mounted: function() {
        this.initialized = true;
    }
};

jQuery(document).ready(function($) {
    CoraFramework = new Vue(CoraFramework);
});
