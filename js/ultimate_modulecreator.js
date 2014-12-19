/**
 * Ultimate_ModuleCreator extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE_UMC.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ultimate
 * @package    Ultimate_ModuleCreator
 * @copyright  Copyright (c) 2014
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * js for module creation UI
 * @category    Ultimate
 * @package     Ultimate_ModuleCreator
 * @author      Marius Strajeru <ultimate.module.creator@gmail.com>
 */
if(typeof UMC=='undefined') {
    var UMC = {};
}
/**
 * override the varienForm to show fieldsets with errors
 * @type UMC.Form
 */
UMC.Form = Class.create(varienForm);
UMC.Form.prototype.submit = function(url){
    if (typeof varienGlobalEvents != undefined) {
        varienGlobalEvents.fireEvent('formSubmit', this.formId);
    }
    this.errorSections = $H({});
    this.canShowError = true;
    this.submitUrl = url;
    if(this.validator && this.validator.validate()){
        if(this.validationUrl){
            this._validate();
        }
        else{
            this._submit();
        }
        return true;
    }
    else {
        var errors = $$('.validation-advice');
        for (var i =0; i < errors.length; i++){
            var field = $(errors[i]);
            while ($(field) && $(field).up('.fieldset') != 'undefined') {
                field = $(field).up('.fieldset');
                if ($(field)) {
                    $(field).show();
                }
            }
        }
    }
    return false;
}
/**
 * module class
 * @type UMC.Module
 */
UMC.Module = Class.create();
UMC.Module.prototype =  {
    /**
     * initialize module
     * @param config
     */
    initialize: function (config){
        this.config = Object.extend({
            addEntityTrigger    : ".add-entity",
            entityTab           : 'modulecreator_info_tabs_entities',
            entitiesContainer   : 'entities_container',
            relationTabId       : 'modulecreator_info_tabs_relations',
            relationHolder      : 'modulecreator_relations_container',
            relations           : {},
            menuSelectors       : '#note_sort_order a, #note_menu_parent a',
            nameAttributes      : {},
            collapsed           : 0
        }, config);
        this.entities = [];
        this.entityCount = 0;
        this.entityTemplate = this.config.entityTemplate;
        this.attributeTemplate = this.config.attributeTemplate;
        this.templateSyntax = /(^|.|\r|\n)({{(\w+)}})/;
        var that = this;
        $$(this.config.addEntityTrigger).each(function(element){
            Element.observe(element, 'click', that.addEntity.bind(that));
        });
        $$(this.config.menuSelectors).each(function(el){
            Element.observe($(el), 'click', that.selectMenu.bind(that))
        });
        $(this.config.entitiesContainer).select('.modulecreator-entity').each(function(element){
            var entity = new UMC.Entity(element, {
                nameAttribute: that.config.nameAttributes[that.entityCount],
                collapsed: that.config.collapsed
            });
            that.registerEntity(entity);
        });
    },
    /**
     * add entity to module
     */
    addEntity: function(){
        var that = this;
        fireEvent($(that.config.entityTab), 'click');
        var template = new Template(that.entityTemplate, this.templateSyntax);
        var entityTemplate = template.evaluate({entity_id:that.entityCount});
        $(that.config.entitiesContainer).insert({bottom:entityTemplate});

        var entity = new UMC.Entity($('entity_' + that.entityCount), {collapsed: that.config.collapsed});
        Effect.Pulsate('entity_' + that.entityCount, { pulses: 1, duration: 1 });
        Effect.ScrollTo($('entity_' + that.entityCount), { duration:'1'});
        that.registerEntity(entity);
    },
    /**
     * remove entity from module
     * @param index
     * @returns UMC.Module
     */
    removeEntity: function(index){
        for (var i = 0; i<this.entities.length; i++) {
            if (this.entities[i].index == index) {
                this.entities.splice(i, 1);
                $$('#' + this.config.relationHolder + ' .relation_entity_' + index).each(function(el){
                    $(el).up().remove();
                });
                break;
            }
        }
        if (this.entities.length < 2){
            $(this.config.relationTabId).up().hide();
        }
        return this;
    },
    /**
     * register entity
     * @param entity
     */
    registerEntity: function(entity){
        entity.setModule(this);
        entity.setIndex(this.entityCount);
        this.entities.push(entity);
        this.entityCount++;
        this.rebuildRelations();
    },
    /**
     * rebuild relations tab
     */
    rebuildRelations: function(){
        var entities = this.entities;
        if (entities.length < 2){
            $(this.config.relationTabId).up().hide();
        }
        else{
            $(this.config.relationTabId).up().show();
        }
        var container = $(this.config.relationHolder);
        for (var i=0;i<entities.length - 1;i++){
            for (var j = i+1; j<entities.length;j++){
                var index1 = entities[i].index;
                var index2 = entities[j].index;
                var relName = index1 + '_' + index2;
                var _tmp = 'relation_' + relName;
                if (container.select('#' + _tmp).length == 0){
                    var template = '<div id="' + _tmp + '" data-id-1="' + index1 + '" data-id-2="' + index2 + '">';
                    template += '<div class="relation-label relation_entity_' + index1 + '">' + $(entities[i].element).select('.entry-edit-head h4')[0].innerHTML + '</div>';
                    template += this.config.relationSelect.replace(/#{e1}/g, index1).replace(/#{e2}/g, index2);
                    template += '<div class="relation-label relation_entity_' + index2 + '">' + $(entities[j].element).select('.entry-edit-head h4')[0].innerHTML + '</div>';
                    template += '<div class="clearfix">';
                    template += '</div>';
                    container.insert({bottom:template});
                    var relations = this.config.relations;
                    if (typeof relations[relName] != "undefined"){
                        $(_tmp).select('option[value="' + relations[relName] +'"]')[0].selected = "selected";
                    }

                }
            }
        }
    },
    /**
     * select menu item
     * @param e
     */
    selectMenu: function(e){
        var that = this;
        var menuSelector = $('umc-nav');
        Dialog.alert(menuSelector.up().innerHTML, {
            className:'magento',
            width:300,
            height: 500,
            okLabel: 'Close',
            buttonClass: 'scalable',
            title: ''
        });
        this.makeTree('umc-nav');
        Event.stop(e);
    },
    /**
     * transform menu into tree
     * @param treeId
     */
    makeTree: function(treeId){
        var that = this;
        var tree = $(treeId);
        if (tree.hasClassName('tree')){
            return ;
        }
        if(tree){
            tree.addClassName('tree');
            tree.select('ul').each(function(el){
                $(el).hide();
            })
            tree.select('li').each(function(item){
                if ($(item).hasClassName('umc-menu-selector')){
                    return;
                }
                var children = $(item).childElements().grep(new Selector('ul'));
                if (children.length > 0) {
                    var togglers = $(item).childElements().grep(new Selector('.toggler'));
                    try{
                        if (togglers.length > 0){
                            Element.observe($(togglers[0]), 'click', function(e){
                                if ($(this).hasClassName('collapsed')){
                                    $(this).addClassName('expanded');
                                    $(this).removeClassName('collapsed');
                                    $(item).childElements().grep(new Selector('ul')).each(function(ul){
                                        $(ul).show();
                                    });
                                }
                                else{
                                    $(this).removeClassName('expanded');
                                    $(this).addClassName('collapsed');
                                    $(item).childElements().grep(new Selector('ul')).each(function(ul){
                                        $(ul).hide();
                                    });
                                }
                            });
                        }
                    }
                    catch(e){
                        console.log(e);
                    }
                }
            });
            var selector = 'li.umc-menu-selector a.insert-menu';
            tree.select(selector).each(function(el){
                Element.observe(el, 'click', function(e){
                    Event.stop(e);

                    var values = $(this).readAttribute('menu-data').evalJSON(true);
                    $('settings_menu_parent').setAttribute('value', values.parent);
                    if (values.parent == ''){
                        $('settings_menu_parent').setAttribute('placeholder', '[top-level]');
                    }
                    $('settings_sort_order').setAttribute('value', values.sort_order);
                    Dialog.okCallback();
                });
            })
        };
    }
}
/**
 * Entity class
 * @type UMC.Entity
 */
UMC.Entity = Class.create();
UMC.Entity.prototype = {
    /**
     * initialize entity
     * @param element
     * @param config
     */
    initialize: function(element, config){
        var that = this;
        this.element = element;
        this.attributeCount = 0;
        this.attributes = [];
        this.cacheElements = [];
        this.config = Object.extend(config, {
            addAttributeTrigger             : '.add-attribute',
            index                           : -1
        });
        this.index = this.config.index;
        $(this.element).select('.delete-entity').each(function(el){
            Event.observe(el, 'click', function(){
                if (confirm(Translator.translate('Are you sure?'))){
                    that.remove();
                }
            });
        });
        $(this.element).select(this.config.addAttributeTrigger).each(function(element) {
            Element.observe(element, 'click', that.addAttribute.bind(that));
        });
        var index = 0;
        $(this.element).select('.entry-edit-head').each(function(el){
            if ($(el).select('.modulecreator-toggle').length == 0){
                $(el).insert({top:'<div class="collapseable"><a href="#" class="left modulecreator-toggle open">&nbsp;</a></div>'});
                Event.observe($(el).select('a.modulecreator-toggle')[0], 'click', function(e){
                    $(this).up(1).next().toggle();
                    $(this).toggleClassName('open');
                    $(this).toggleClassName('closed');
                    Event.stop(e);
                    return false;
                });
                if (index > 0 && that.config.collapsed){
                    $(el).select('a.modulecreator-toggle')[0].click();
                }
                index++;
            }
        });
        Event.observe($(this.element).select('.label-singular')[0], 'change', function(){
            var label = $(this).value;
            $(that.element).select('h4')[0].update(label);
            $$('.relation_entity_' + that.index).each(function(el){
                $(el).update(label);
            })
        });
        var reloaders = this.getReloaders();
        for (var i = 0; i< reloaders.length; i++){
            Event.observe($(reloaders[i]), 'change', function(){
                that.reloadFrontend();
            })
        }
        $(this.element).select('table').each(function(elem){
            decorateTable(elem);
        });
        $(this.element).select('.modulecreator-attribute').each(function(elem){
            var attribute = new UMC.Attribute(elem, {})
            that.registerAttribute(attribute);
        });

        this.reloadFrontend();
    },
    /**
     * set module
     * @param module
     */
    setModule: function(module){
        this.module = module;
    },
    /**
     * set index
     * @param index
     */
    setIndex: function(index){
        this.index = index;
        this.initAttributeSort()
    },
    /**
     * remove entity
     */
    remove: function(){
        var that = this;
        var index = this.index;
        var module = this.module;
        if (module){
            module.removeEntity(index);
        }
        $(that.element).slideUp({
            afterFinish: function(){
                $(that.element).remove();
            }
        });
    },
    /**
     * add attribute to entity
     */
    addAttribute: function(){
        var that = this;
        var template = new Template(that.module.attributeTemplate, that.module.templateSyntax);
        var attributeTemplate = template.evaluate({
            entity_id:this.index,
            attribute_id : that.attributeCount
        });
        $('entity_' + that.index + '_attributes').show();
        $('entity_' + that.index + '_attributes_container').select('.modulecreator-toggle')[0].removeClassName('closed');
        $('entity_' + that.index + '_attributes_container').select('.modulecreator-toggle')[0].addClassName('open');
        $('entity_' + that.index + '_attributes').insert({bottom:attributeTemplate});
        $$('#attribute_' + that.index + '_' + that.attributeCount + ' table').each(function(elem){
            decorateTable(elem);
        });
        var attribute = new UMC.Attribute($('attribute_' + that.index + '_' + that.attributeCount), {});
        Effect.ScrollTo($('attribute_' + that.index + '_' + that.attributeCount), { duration:1});
        Effect.Pulsate('attribute_' + that.index + '_' + that.attributeCount, { pulses: 1, duration: 1 });
        that.registerAttribute(attribute);
        this.initAttributeSort();
    },
    /**
     * register attribute
     * @param attribute
     */
    registerAttribute: function(attribute){
        attribute.setEntity(this);
        attribute.setIndex(this.attributeCount);
        this.attributes.push(attribute);
        this.reloadFrontend();
        attribute.reloadSettings(false);
        this.attributeCount++;
    },
    /**
     * remove attribute
     * @param index
     */
    removeAttribute: function(index){
        for (var i = 0; i<this.attributes.length; i++) {
            if (this.attributes[i].index == index) {
                this.attributes.splice(i, 1);
                break;
            }
        }
    },
    /**
     * get elements that reload the settings
     * @returns {Array}
     */
    getReloaders: function(){
        var classes = ['type', 'is_tree', 'create-frontend', 'create-list', 'create-view', 'link-product', 'link-category', 'product-attribute', 'category-attribute', 'allow-comment', 'rss', 'widget', 'url-rewrite'];
        var reloaders = [];
        for (var i=0; i<classes.length; i++){
            reloaders.push(this.getElementByClass(classes[i]));
        }
        return reloaders;
    },
    /**
     * reload settings for fronend
     */
    reloadFrontend: function(){
        var that = this;
        $(this.element).select('input, textarea, select').each(function(el){
            that.evaluateElement(el);
        });
        for (var i = 0; i < this.attributes.length; i++){
            this.attributes[i].reloadSettings(false);
        }
    },
    /**
     * check if element should be disabled or not
     * @param item
     * @returns UMC.Entity
     */
    evaluateElement: function(item){
        if (this.canEnableElement(item)){
            this.enableItem(item);
        }
        else{
            this.disableItem(item);
        }
        return this;
    },
    /**
     * check if element can be disabled
     * @param item
     * @returns {boolean}
     */
    canEnableElement: function(item){
        item = $(item);
        var canEnable = true;
        if (item.hasClassName('use-frontend')){
            if (this.getValueByClass('create-frontend') == 0){
                canEnable = false;
            }
            else{
                if (item.hasClassName('use-create-list')){
                    canEnable = canEnable && (this.getValueByClass('create-list') == 1);
                }
                if (item.hasClassName('use-create-view')){
                    canEnable = canEnable && (this.getValueByClass('create-view') == 1);
                }
                if (item.hasClassName('use-rss')){
                    canEnable = canEnable && (this.getValueByClass('rss') == 1);
                }
                if (item.hasClassName('use-widget')){
                    canEnable = canEnable && (this.getValueByClass('widget') == 1);
                }
                if (item.hasClassName('use-allow-comment')){
                    canEnable = canEnable && (this.getValueByClass('allow-comment') == 1);
                }
                if (item.hasClassName('use-url-rewrite')){
                    canEnable = canEnable && (this.getValueByClass('url-rewrite') == 1);
                }
            }
        }
        if (item.hasClassName('use-link-product')){
            canEnable = canEnable && (this.getValueByClass('link-product') == 1);
        }
        if (item.hasClassName('use-link-category')){
            canEnable = canEnable && (this.getValueByClass('link-category') == 1);
        }
        if (item.hasClassName('use-product-attribute')){
            canEnable = canEnable && (this.getValueByClass('product-attribute') == 1);
        }
        if (item.hasClassName('use-category-attribute')){
            canEnable = canEnable && (this.getValueByClass('category-attribute') == 1);
        }
        if (item.hasClassName('attribute-scope')){
            canEnable = canEnable && (this.getValueByClass('type') == 'eav');
        }
        if (item.hasClassName('store-specific')){
            canEnable = canEnable && (this.getValueByClass('type') == 'flat');
        }
        if (item.hasClassName('use-not-tree')){
            canEnable = canEnable && (this.getValueByClass('is_tree') == 0);
        }
        return canEnable;
    },
    /**
     * get element by class name
     * @param className
     * @returns {*}
     */
    getElementByClass: function(className){
        if (!this.cacheElements[className]){
            this.cacheElements[className] = $(this.element).select('.' + className)[0];
        }
        return this.cacheElements[className];
    },
    /**
     * get value by class name
     * @param className
     * @returns {*}
     */
    getValueByClass: function(className){
        return this.getElementByClass(className).value;
    },
    /**
     * disable element
     * @param item
     * @returns {*}
     */
    disableItem: function(item){
        $(item).setAttribute('disabled', 'disabled');
        return this;
    },
    /**
     * enable element
     * @param item
     * @returns {*}
     */
    enableItem: function(item){
        $(item).removeAttribute('disabled');
        return this;
    },
    /**
     * initialize attribute sorting
     */
    initAttributeSort: function() {
        var that = this;
        Sortable.create('entity_' + this.index +'_attributes', {
            tag:'div',
            onUpdate: function(){
                that.reloadAttributePositions()
            }
        });
        this.reloadAttributePositions();
    },
    /**
     * set attribute positions
     */
    reloadAttributePositions: function() {
        var position = 10;
        $(this.element).select('input.position').each(function(element){
            $(element).value = position;
            position += 10;
        });
    }
}
/**
 * Attribute class
 * @type UMC.Attribute
 */
UMC.Attribute = Class.create();
UMC.Attribute.prototype = {
    /**
     * initialize attribute class
     * @param element
     * @param config
     */
    initialize: function(element, config){
        var that = this;
        this.element = element;
        this.config = config;
        $(this.element).select('.entry-edit-head').each(function(el){
            if ($(el).select('.modulecreator-toggle').length == 0){
                $(el).insert({top:'<div class="collapseable"><a href="#" class="left modulecreator-toggle open">&nbsp;</a></div>'});
                Event.observe($(el).select('a.modulecreator-toggle')[0], 'click', function(e){
                    $(this).up(1).next().toggle();
                    $(this).toggleClassName('open');
                    $(this).toggleClassName('closed');
                    Event.stop(e);
                    return false;
                });
            }
        });
        var removeAttributeTemplate = '<div class="right">';
        removeAttributeTemplate += '   <button type="button" class="delete delete-attribute">';
        removeAttributeTemplate += '    <span>';
        removeAttributeTemplate += '        <span>';
        removeAttributeTemplate += '            <span>';
        removeAttributeTemplate += '                ' + Translator.translate('Remove field / attribute');
        removeAttributeTemplate += '            </span>';
        removeAttributeTemplate += '        </span>';
        removeAttributeTemplate += '    </span>';
        removeAttributeTemplate += '    </button>';
        removeAttributeTemplate += '</div>';
        $(this.element).select('.entry-edit-head')[0].insert({bottom: removeAttributeTemplate});
        $(this.element).select('.delete-attribute').each(function(el){
            Event.observe(el, 'click', function(){
                if (confirm(Translator.translate('Are you sure?'))){
                    that.remove();
                }
            });
        });
        Event.observe(this.getTypeElement(), 'change', function(){
            that.reloadSettings(true);
        });
        Event.observe(this.getSourceTypeElement(), 'change', function(){
            that.reloadSettings(false);
        });
        Event.observe(this.element.select('.attribute-label')[0], 'change', function(){
            if ($(this).value.length > 0){
                $(that.element).select('h4')[0].update($(this).value);
            }
        });
        var labelElement = this.element.select('.attribute-label')[0]
        if (labelElement.value != '') {
            $(that.element).select('h4')[0].update(labelElement.value);
        }
        else {
            $(that.element).select('h4')[0].update('New Attribute');
        }
    },
    /**
     * attach to entity
     * @param entity
     */
    setEntity: function(entity){
        this.entity = entity;
        var nameElem = (this.element).select('input.is_name')[0];
        var name = nameElem.name;
        var parts = name.split('][');
        var newName = parts[0] + '][' + parts[1] + '][' + parts[3];
        nameElem.name = newName;

    },
    /**
     * set attribute index
     * @param index
     */
    setIndex: function(index){
        this.index = index;
        $(this.element).select('input.is_name')[0].value = index;
        if (index == this.entity.config.nameAttribute){
            $(this.element).select('input.is_name')[0].setAttribute('checked', 'checked');
        }
    },
    /**
     * remove attribute
     */
    remove: function(){
        var that = this;
        var index = this.index;
        var entity = this.entity;
        if (entity){
            entity.removeAttribute(index);
        }
        //$(that.element).remove();
        $(that.element).slideUp({
            afterFinish: function(){
                $(that.element).remove();
            }
        });
    },
    /**
     * reload attribute settings
     * @param withEntity
     */
    reloadSettings: function(withEntity){
        var that = this;
        if(withEntity){
            this.entity.reloadFrontend();
        }
        this.element.select('input, textarea, select').each(function(el){
            var shouldDisable = false;
            if ($(el).hasClassName('type-' + that.getType()) || $(el).hasClassName('type-all')){
                shouldDisable = !that.entity.canEnableElement(el);
                if ($(el).hasClassName('custom-source')){
                    shouldDisable = shouldDisable || (that.getSourceType() != 'custom');
                }
                if ($(el).hasClassName('not-custom-source')){
                    shouldDisable = shouldDisable || (that.getSourceType() == 'custom' || !that.getSourceType());
                }
                if ($(el).hasClassName('dropdown-custom-source')) {
                    shouldDisable = shouldDisable || ((that.getType() == 'dropdown' || that.getType() == 'multiselect') && that.getSourceType() != 'custom');
                }
            }
            else{
                shouldDisable = true;
            }
            if (!shouldDisable){
                that.entity.enableItem(el);
            }
            else{
                that.entity.disableItem(el);
            }
        });
    },
    /**
     * get attrbute type dom element
     * @returns {*}
     */
    getTypeElement: function(){
        return $(this.element).select('.attribute-type')[0];
    },
    /**
     * get attribute type
     * @returns {*}
     */
    getType: function(){
        return this.getTypeElement().value;
    },
    /**
     * get source type DOM element
     * @returns {*}
     */
    getSourceTypeElement: function(){
        return $(this.element).select('.is-source')[0];
    },
    /**
     * get source type
     * @returns {*}
     */
    getSourceType: function(){
        return this.getSourceTypeElement().value;
    }
}
/**
 * Help class
 * @type UMC.Help
 */
UMC.Help = Class.create();
UMC.Help.prototype = {
    /**
     * initialize help class
     * @param element
     */
    initialize: function(element){
        var that = this;
        this.element = element;
        $(this.element).select('.entry-edit-head').each(function(el){
            if ($(el).select('.modulecreator-toggle').length == 0){
                $(el).insert({top:'<div class="collapseable"><a href="#" class="left modulecreator-toggle open">&nbsp;</a></div>'});
                Event.observe($(el).select('a.modulecreator-toggle')[0], 'click', function(e){
                    $(this).up(1).next().toggle();
                    $(this).toggleClassName('open');
                    $(this).toggleClassName('closed');
                    Event.stop(e);
                    return false;
                });
                $(el).select('a.modulecreator-toggle')[0].click();
            }
        });
    }
}