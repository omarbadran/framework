/**
 * Export field
 */
Vue.component('export-field', {
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
            data: false,
        }
    },

    template: `
        <div>
            <div class="button" @click="download">{{translation.download_data}}</div>
        </div>
    `,

    methods: {
        download: function (){
            
            let dt = new Date();
            let date = '_' + dt.getFullYear() + "_" + (dt.getMonth() + 1) + "_" + dt.getDate();

            let fileName = CoraFramework.config["id"] + '_options_backup';

            if(this.filename){
                fileName = this.filename;
            }

            if(this.append_date){
                fileName = fileName + date;
            }

            // Create a blob of the data
            var fileToSave = new Blob([JSON.stringify(CoraFramework.values)], {
                type: 'application/json',
                name: fileName + '.json'
            });
            
            // Save the file
            saveAs(fileToSave, fileName);            
        }
    }
});