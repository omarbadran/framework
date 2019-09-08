/**
 * Import field
 */
Vue.component('import-field', {
    inheritAttrs: false,

    props: {
        filename: String,
        append_date: {
            type: Boolean,
            default: true
        },
        translation: Object
    },
    
    data: function(){
        return {
            data: false
        }
    },

    template: `
        <div>
            <input @change="change" type="file" accept="application/json" />
            <div class="button" @click="importData">{{translation.import}}</div>
        </div>
    `,

    methods: {
        change: function (evt){
            const vm =this;
            try {
                let files = evt.target.files;
                if (!files.length) {
                    alert(CoraFramework.translation.no_file_selected);
                    vm.data = false;
                    return;
                }
                let file = files[0];
                let reader = new FileReader();
                reader.onload = (event) => {
                    vm.data = JSON.parse(event.target.result);
                };
                reader.readAsText(file);
            } catch (err) {
                console.error(err);
            }    
        },

        importData: function (){
            const vm = this;

            if(!vm.data){
                alert(CoraFramework.translation.no_file_selected);
                return;
            }

            if(confirm(CoraFramework.translation.confirm)){
                CoraFramework.values = vm.data;
                CoraFramework.save();
            }
        }
    }
});