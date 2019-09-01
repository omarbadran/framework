/**
 * Repeater field
 */
Vue.component('repeater-field', {
    inheritAttrs: false,

    props: ['value', 'fields', 'new_item_default', 'item_title'],
    
    data: function() {
        return {
            values: this.value,
            activeItem: false
        }
    },

    template: `
        <div>
            <SlickList lockAxis="y" v-model="values" :useDragHandle="true">
                <SlickItem v-for="(item, itemIndex) in values" :index="itemIndex" :key="itemIndex" class="cf-repeater-item">
                    
                    <div class="cf-repeater-item-head"  @click="activeItem === item ? activeItem = false : activeItem = item">
                        <i class="material-icons cf-drag-handle" v-handle>drag_handle</i>

                        <span v-if="itemTitle(itemIndex)" v-html="itemTitle(itemIndex)"></span>
                        
                        <i class="material-icons cf-status-arrow" v-if="item === activeItem">arrow_drop_up</i>
                        <i class="material-icons cf-status-arrow" v-else>arrow_drop_down</i>
                    </div>

                    <div v-if="activeItem === item" class="cf-repeater-item-fields">
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
                    </div>

                </SlickItem>
            </SlickList>
            <div class="cf-repeater-add-item" @click="values.push({...new_item_default})">Add Item</div>
        </div>
    `,

    methods: {

        itemTitle: function (itemIndex) {
            var title = this.values[itemIndex][this.item_title];
            
            if (title.length > 0) {
                return title;
            }

            return false;
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
