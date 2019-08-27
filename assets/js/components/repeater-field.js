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
            <div v-for="(item, itemIndex) in values" class="cf-repeater-item">
                <div class="cf-repeater-item-head"  @click="activeItem === itemIndex ? activeItem = false : activeItem = itemIndex">
                    <span v-if="itemTitle(itemIndex)" v-html="itemTitle(itemIndex)"></span>
                    <i class="material-icons" v-if="itemIndex === activeItem">arrow_drop_up</i>
                    <i class="material-icons" v-else>arrow_drop_down</i>
                </div>
                <div v-if="activeItem === itemIndex" class="cf-repeater-item-fields">

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
            </div>
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
    
    }
});
