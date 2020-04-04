<div id="cf" :class="{initialized: initialized}">
	<div class="cf-header">
        <h1>{{ config.page_title }}</h1>
        
		<span v-if="config.display_version">
            {{ config.display_version }}
        </span>
	</div>
	
	<div class="cf-body">
        <transition name="fade">
            <div class="cf-loading" v-if="loading"></div>
        </transition>

        <ul class="cf-nav">
			<li v-for="section in sections" @click="activeSection = section.id"  :class="{active : (activeSection == section.id)}">
				<i class="material-icons"> {{ section.icon }} </i> {{ section.title }}
            </li>
        </ul>
	
		<div class="cf-content">
			<h2 :section="activeSection"> {{ activeSectionTitle }} </h2>

			<div v-for="field in fields" v-show="showField(field)" class="cf-field" :class="{'cf-no-title': !field.title}" :section="field.section">

                <span class="cf-field-title" v-if="field.title">
                    {{field.title}} <p v-if="field.description" v-html="field.description"></p>
                </span>

				<div :class="'cf-' + field.type + '-field'" class="cf-item-field-controls">
                    <component
                        :is="field.type + '-field'"
                        :translation="translation"
						v-model="values[field.section][field.id]"
						v-bind="field">
                
                    </component>
                </div>
            </div>
        </div>
        
        <transition name="fade">
            <div v-if="alert" class="cf-message" :class="alert.style">
                <div>
                    <span>{{alert.message}}</span>
                    <i @click="alert = false" class="dashicons dashicons-dismiss"></i>
                </div>
            </div>
        </transition>

        <div class="cf-save button button-primary" @click="save">{{ translation.save_changes }}</div>
    </div>
    
    <div class="clear"></div>
</div>