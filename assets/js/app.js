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
        },
        
        /**
         * Current fields in view
         * 
         * @since 1.0.0
         */
        currentFields: function () {
            const vm = this;

            let fields = vm.fields.filter(function(field) {
                if( field.section  !== vm.activeSection ){
                    return false;
                }
                return true;
            });

            return fields;
        },
        
    }
    
});
