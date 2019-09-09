/**
 * Editor field
 */
Vue.component('editor-field', {
    inheritAttrs: false,

    props: {
        value: String,
        readonly: { 
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            id: 'x' + Math.random().toString(36).substr(2, 9),
            content : this.value,
            editor : null,
        }
    },
    
    template: `
        <div>
            <textarea :id="id">{{ content }}</textarea>
        </div>
    `,

    mounted: function () {
        this.content = this.value;
        this.init();  
    },
    
    beforeDestroy () {
        this.editor.destroy();
    },

    methods: {
        init(){
            let options = {
                selector: '#' + this.id,
                menubar: false,
                branding: false,
                init_instance_callback : this.initEditor
            };

            tinymce.init(options);
        },

        initEditor(editor) {
            this.editor = editor;
            editor.on('KeyUp Change Undo Redo paste', (e) => {
                this.$emit('input', this.editor.getContent());
            });

            editor.on('init', (e) => {
                editor.setContent(this.content);
                this.$emit('input', this.content);
            });
        },
    },
});
