var CoraFramework = new  Vue({

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
                compareValue = field.condition[2];
                target = vm.values[field.section][field.condition[0]];
                
                res = eval(`compareValue ${field.condition[1]} target`);
            }

            return res;
        },

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

    }
    
});
