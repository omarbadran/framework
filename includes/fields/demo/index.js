/**
 * Demo field
 */
Vue.component('demo-field', {
    inheritAttrs: false,

    props: {
        options: Array,
        translation: Object
    },

    template: `
        <div>
            <div v-for="option in options" class="cf-demo">
                <img :src="option.img" :title="option.title"/>
                <div class="cf-demo-bottom">
                    <span v-html="option.title"></span>
                    <div class="button button-primary" @click="importDemo(option.data)">{{translation.import}}</div>
                </div>
            </div>
        </div>
    `,

    methods: {
        importDemo: function (data){            
            if (confirm(this.translation.confirm)) {
                let original = JSON.parse(JSON.stringify(CoraFramework.values));
                let coming = JSON.parse(JSON.stringify(data));

                let newData = deepmerge(original, coming);
                                
                CoraFramework.save({
                    data: newData,
                    success: function () {
                        location.reload()
                    }
                });
            }
        }
    }
});