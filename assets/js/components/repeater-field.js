/**
 * Repeater field
 */
Vue.component('repeater-field', {
    inheritAttrs: false,

    props: {
        value: {
            type: Array,
            default: () => []
        },
        fields: {
            type: Array,
            default: () => []
        },
        new_item_default: {
            type: Object,
            default: () => []
        },
        item_title: {
            type: String,
            default:  function () {
                if( this.fields[0] ){
                    return this.fields[0]['id'];
                }
            }
        }
    },
    
    data: function() {
        return {
            values: this.value,
            activeItem: false
        }
    },

    template: `
        <div>
            <SlickList v-if="values.length" v-model="values" :useDragHandle="true" class="cf-repeater-items">
                <SlickItem v-for="(item, itemIndex) in values" :index="itemIndex" :key="itemIndex" class="cf-repeater-item">
                    
                    <div class="cf-repeater-item-head"  @click="activeItem === item ? activeItem = false : activeItem = item">
                        <i class="material-icons cf-drag-handle" v-handle>menu</i>

                        <span v-if="itemTitle(itemIndex)" v-html="itemTitle(itemIndex)"></span>
                        
                        <i class="material-icons cf-status-arrow" v-if="item === activeItem">arrow_drop_up</i>
                        <i class="material-icons cf-status-arrow" v-else>arrow_drop_down</i>
                    </div>

                    <div v-show="activeItem === item" class="cf-repeater-item-fields">
                        <div v-for="field in fields" class="cf-repeater-item-field">
                            <span class="cf-repeater-item-title"> {{ field.title }} </span>
                            <div :class="'cf-' + field.type + '-field'">
                                <component 
                                    :is="field.type + '-field'"
                                    v-model="values[itemIndex][field.id]"
                                    v-bind="field">
                                </component>					
                            </div>
                        </div>
                        <div class="cf-repeater-remove-item" @click="removeItem(itemIndex)">Remove</div>
                    </div>

                </SlickItem>
            </SlickList>
            
            <div class="button" @click="values.push({...new_item_default})">Add Item</div>
        </div>
    `,

    methods: {

        itemTitle: function (itemIndex) {
            var title = this.values[itemIndex][this.item_title];
            
            if (title && title.length > 0) {
                return title;
            }

            return false;
        },
        
        removeItem: function (index) {
            if( confirm('Are You Sure?') ){
                Vue.delete(this.values, index);
            }
        }
    },

    watch: {
        values : function () {
            this.$emit('input', this.values);
        },
    },

    components: {
        SlickItem: VueSlicksort.SlickItem,
        SlickList: VueSlicksort.SlickList    
    },

    directives: {
        handle: VueSlicksort.HandleDirective
    },

});
