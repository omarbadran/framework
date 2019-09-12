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
            default: () => {}
        },

        item_title: {
            type: String,
            default:  function () {
                if( this.fields[0] ){
                    return this.fields[0]['id'];
                }
            }
        },

        remove_condition: {
            default: false
        },

        remove_disabled_title: {
            default: 'disabled'
        },

        translation: Object
    },
    
    data: function() {
        return {
            values: this.value,
            activeItem: false
        }
    },

    template: `
        <div>
            <SlickList
                v-if="values.length"
                v-model="values"
                lockAxis="y"
                :useDragHandle="true"
                class="cf-repeater-items"
            >

                <SlickItem
                    v-for="(item, itemIndex) in values"
                    :index="itemIndex"
                    :key="itemIndex"
                    class="cf-repeater-item"
                >
                    
                    <div
                        class="cf-repeater-item-head"
                        @click="activeItem === item ? activeItem = false : activeItem = item"
                    >

                        <div class="cf-drag-handle" v-handle>
                            <svg viewBox="0 0 10 10">
                                <path d="M3,2 C2.44771525,2 2,1.55228475 2,1 C2,0.44771525 2.44771525,0 3,0 C3.55228475,0 4,0.44771525 4,1 C4,1.55228475 3.55228475,2 3,2 Z M3,6 C2.44771525,6 2,5.55228475 2,5 C2,4.44771525 2.44771525,4 3,4 C3.55228475,4 4,4.44771525 4,5 C4,5.55228475 3.55228475,6 3,6 Z M3,10 C2.44771525,10 2,9.55228475 2,9 C2,8.44771525 2.44771525,8 3,8 C3.55228475,8 4,8.44771525 4,9 C4,9.55228475 3.55228475,10 3,10 Z M7,2 C6.44771525,2 6,1.55228475 6,1 C6,0.44771525 6.44771525,0 7,0 C7.55228475,0 8,0.44771525 8,1 C8,1.55228475 7.55228475,2 7,2 Z M7,6 C6.44771525,6 6,5.55228475 6,5 C6,4.44771525 6.44771525,4 7,4 C7.55228475,4 8,4.44771525 8,5 C8,5.55228475 7.55228475,6 7,6 Z M7,10 C6.44771525,10 6,9.55228475 6,9 C6,8.44771525 6.44771525,8 7,8 C7.55228475,8 8,8.44771525 8,9 C8,9.55228475 7.55228475,10 7,10 Z"></path>
                            </svg>
                        </div>
                        
                        <span v-if="itemTitle(itemIndex)" v-html="itemTitle(itemIndex)"></span>
                        
                        <i class="material-icons cf-status-arrow" v-if="item === activeItem">arrow_drop_up</i>
                        <i class="material-icons cf-status-arrow" v-else>arrow_drop_down</i>
                    </div>

                    <div v-show="activeItem === item" class="cf-repeater-item-fields">
                        <div
                            v-for="field in fields"
                            v-if="showField(field, itemIndex)"
                            class="cf-repeater-item-field"
                        >
                        
                            <span class="cf-repeater-item-title"> {{ field.title }} </span>
                        
                            <div :class="'cf-' + field.type + '-field'" class="cf-repeater-item-field-controls">
                        
                                <component 
                                    :is="field.type + '-field'"
                                    :translation="translation"
                                    v-model="values[itemIndex][field.id]"
                                    v-bind="field">
                                </component>					
                            
                            </div>
                        </div>
                        
                        <button
                            class="cf-repeater-remove-item" 
                            :disabled="!showRemove(itemIndex)" 
                            :title="!showRemove(itemIndex) ? remove_disabled_title : false"
                            @click="removeItem(itemIndex)"
                        >
                            {{translation.remove}}
                        </button>
                    </div>

                </SlickItem>

            </SlickList>
            
            <div class="button" @click="add">{{translation.add_item}}</div>
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
            if( confirm(this.translation.confirm) ){
                Vue.delete(this.values, index);
            }
        },


        showField: function (field, itemIndex) {
            const vm = this;
            let res = true;

            if (field.condition) {
                compareValue = field.condition[2];
                target = vm.values[itemIndex][field.condition[0]];
                
                res = eval(`compareValue ${field.condition[1]} target`);
            }

            return res;
        },


        showRemove: function (itemIndex) {
            const vm = this;
            let condition = vm.remove_condition;
            
            let res = true;

            if (condition) {
                compareValue = condition[2];
                target = vm.values[itemIndex][condition[0]];
                
                res = eval(`compareValue ${condition[1]} target`);
            }

            return res;
        },

        add: function(){
            this.values.push({...this.new_item_default});
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
