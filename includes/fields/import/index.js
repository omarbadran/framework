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
        }
    },
    
    data: function(){
        return {
            data: false
        }
    },

    template: `
        <div>
            <input @change="change" type="file" accept="application/json" />
            <div class="button" @click="importData">Import</div>
        </div>
    `,

    methods: {
        change: function (evt){
            const vm =this;
            try {
                let files = evt.target.files;
                if (!files.length) {
                    alert('No file selected!');
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
                alert('No file selected!');
                return;
            }

            if(confirm('Are You Sure?')){
                CoraFramework.values = vm.data;
                CoraFramework.save();
            }
        }
    }
});