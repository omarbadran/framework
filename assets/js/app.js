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
        
    }

});
