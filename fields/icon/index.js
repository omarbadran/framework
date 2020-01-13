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
        <select-field v-if="icons" @input="$emit('input', $event)" :options="icons" :value="value"/>
    `,

    created: function () {
        var vm = this;
        const JSONFile = CoraFrameworkData.url + '/fields/icon/icons.json';

        if( window.materialIconsList ){

            vm.icons = window.materialIconsList;

        } else {

            jQuery.getJSON( JSONFile , data => {                
                window.materialIconsList = data;
                vm.icons = data;
            })

        }
    }
});
