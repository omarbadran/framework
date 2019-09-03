/**
 * Image field
 */
Vue.component('image-field', {
    inheritAttrs: false,

    props: {
        value: String,
    },
    
    template: `
        <div>
            <input type="text" @input="$emit('input', $event.target.value)" :value="value" placeholder="URL"/>
            <div class="button" @click="select">Select</div>
        </div>
    `,

    methods: {
        
        select: function() {
            const vm = this;
            let frame;

            frame = wp.media({
                title: 'Select Image',
                multiple: false
            });
          
            frame.on( 'select', function() {
                let attachment = frame.state().get('selection').first().toJSON();
                vm.$emit('input', attachment.url);
            });

            frame.open();          
        }

    },
});
