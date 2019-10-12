/**
 * HTML field
 */
Vue.component('html-field', {
    inheritAttrs: false,

    props: {
        content: String,
    },
    
    template: `
        <div v-html="content"></div>
    `,
});