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
                let res = true;

                // Current section?
                if( field.section  !== vm.activeSection ){
                    return false;
                }

                // Conditions
                if (field.condition) {
                    compareValue = field.condition[2];
                    target = vm.values[field.section][field.condition[0]];

                    switch (field.condition[1]) {
                        case '==':
                            res = compareValue == target;
                            break;
                        
                        case '===':
                            res = compareValue === target;
                            break;

                        case '!=':
                            res = compareValue != target;
                            break;

                        case '>':
                            res = compareValue > target;
                            break;
                        
                        case '<':
                            res = compareValue < target;
                            break;

                        case '>=':
                            res = compareValue >= target;
                            break;
                        
                        case '<=':
                            res = compareValue <= target;
                            break;

                        default:
                            res = false;
                            break;
                    }
                    console.log(target, field.condition[1], compareValue, res);
                    
                }


                return res;
            });

            return fields;
        },
        
    }
    
});
