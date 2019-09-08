/**
 * Demo field
 */
Vue.component('demo-field', {
    inheritAttrs: false,

    props: {
        options: Array,
    },

    template: `
        <div>
            <div v-for="option in options" class="cf-demo">
                <img :src="option.img" :title="option.title"/>
                <div class="cf-demo-bottom">
                    <span>{{option.title}}</span>
                    <div class="button button-primary" @click="importDemo(option.data)">Import</div>
                </div>
            </div>
        </div>
    `,

    methods: {
        importDemo: function (data){            
            if (confirm('Are You Sure?')) {
                let newData = jQuery.extend(true, CoraFramework.values, data);
                CoraFramework.values = newData;
                CoraFramework.save();
            }
        }
    }
});