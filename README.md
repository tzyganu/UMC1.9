Ultimate Module Creator 1.9-dev5
======

New Ultimate Module Creator for Magento 1.7 +
-------------

This is the new version of <a href="https://github.com/tzyganu/moduleCreator">Ultimate Module Creator</a>. It is still under development so **DON'T USE IN PRODUCTION.**.  


This should be the version 2.0 of the Magento extension but I'm keeping this version for the Module Creator for Magento 2.0.  

Backwards compatibility has been broken.

If someone wants to help with the testing please submit any bugs, improvements or feature requests <a href="https://github.com/tzyganu/UMC1.9/issues">here</a>

Also any good code is welcomed.

**Note**
For the rest of the document UMC = Ultimate Module Creator

**Release Notes 1.9.0-dev5 - 2014-02-06**

|Type|Label|Comment|
|----|-----|-------|
|Improvement|Comments grid include entity name|The admin grids for each entity comments include the entity name.|
|Feature|Added my comments for customers|There is a new section in the customer account menu that links to "my comments" for each entity.|
|Bug Fix|Fix url rewrite save|Flat entities with url rewrites and no stores could not be saved.|

**Release Notes 1.9.0-dev4 - 2014-02-04**

|Type|Label|Comment|
|----|-----|-------|
|Feature|Allowed more attribtues in mass action|Country and Dropdown attributes can be set by mass action from grid.|
|Bug fix|Fixed tree widget chooser|Tree widget chooser was giving fatal error.|
|Bug fix|Fixed relations between tree behaving entities|A "sibling" relation between and EAV Tree entity and a Flat Tree entity did not behave as expected. but this is no problem. No one will use this kind of relation.|
|Bug fix|Fixed RSS display|Now all attribute types displayed properly in the rss feed.|
|Bug fix|Fixed tree entities display|Tree entities rendered wrong when the view page was disabled.|
|Refactored|Widgets|Refactored widget view contents|

**Release Notes 1.9.0-dev3 - 2014-01-24**

|Type|Label|Comment|
|----|-----|-------|
|Feature|Drag & drop attributes|When creating an entity you can drag and drop attributes to sort them in the list|
|Feature|Select menu for entity link|Added ability to select the frontend menu where the link to your entity page should be placed|
|???|Temporarily disable API|The API does not yet work for EAV entities and comments. It is temporarily disabled until I get it working.|
|Bug fix|Empty page for non existing entities|The entity view page displayed as blank if looking at an entity that does not exist. Now redirects to 404 page.|
|Bug fix|Fixed display on category page|Entities related to categories were displayed on the category page at the top. Now they are after the product list.|
|Bug fix|Fixed entity relations |Entities can be related independent of their type|
|Bug fix|Fixed or generation for entities without url rewrites|Entities without url rewrites generated an empty url.|
|Bug fix|Fixed multiselect attributes in combination with url rewrite|For flat entities that have multiselect attributes and url rewrite the _beforeSave method was generated twice.|

**Release Notes 1.9.0-dev2**

|Type|Label|Comment|
|----|-----|-------|
|Refactor|Refactored some attribute codes|Variables that depend on attribute names now look like this: $someName instead of $some_name|
|Bug fix|Frontend layout file generation|Frontend layout file was always generated. Now is generated only if there is at least one entity that has frontend.|
|Bug fix|Fixed dropdown attributes for flat entities|Dropdown attribute for flat entities were not configured correctly in the admin grid and admin add/edid form.|
|Bug fix|Entities not displaying in product page.|Entities were not displaying in product page unless they had a separate view page.|
|Bug fix|Fixed field/attribute codes validation.|Field/Attribute codes use the same validation as product attributes|
|Bug fix|Replaced 'addFilter' with 'addFieldToFilter'|<a href="https://github.com/tzyganu/UMC1.9/issues/1">https://github.com/tzyganu/UMC1.9/issues/1</a>|
|Bug fix|Inconsistent registry naming |<a href="https://github.com/tzyganu/UMC1.9/issues/3">https://github.com/tzyganu/UMC1.9/issues/3</a>|


**Release Notes 1.9.0-dev1**

|Type|Label|Comment|
|----|-----|-------|
|Feature|Added EAV entities|Now you can create EAV entities that you can manage just like products and categories.|
|Feature|Added many to many link to categories|Now you can link you entities "many to many" with the catalog categories|
|Feature|Added comment feature|You can choose to allow customers to write comments on your entities.|
|Feature|Made extension "extensible"|Yeah...like someone is going to extend it|
|Feature|Many to many relations between tree entities|Now the tree entities can be related in "Many to many"|
|Feature|Module menu can be placed anywhere|Until now the menu could be placed in admin only on top level. Now it can be placed anywhere in the menu tree.|
|Feature|Added new attribute types|dropdown, multiselect. Can have different sources - still experimental.|
|Feature|Custom entities can be added as product, category and customer attributes.| |
|Feature|You can set the version of your generated module.|No more hard coded 0.0.1 version.|
|Refactor|Source file refactoring|Because some IDEs show errors when parsing incomplete PHP files I've removed the extension of the source files (etc/source)|
|Refactor|Refactored JS|The UI of the module creator now uses prototype classes instead of simple JS functions.|
|Refactor|UI refactor|'Pimped up' the UI of the module creator||
|Refactor|Removed frontend package and theme fields|base/default is used for all modules.|
|Refactor|Changed indentation from TAB to 4x space||
|Improvement|Improved speed of the module creator|No more AJAX calls to add an entity/attribute. The module save is done in the same step as validation.|
|Improvement|Module creator generate sql uninstall script|Along with the list of generated files now an sql uninstall script is generated for the created module.|
|Bug fix|Fixed bug that appears when there is an uppercase letter in the module name.|In previous version 'ModuleName' was not working on UNIX servers. Only 'Modulename'. This is fixed in this version.|  


**Known Issues**

* ~~The SOAP API does not work for EAV entities and is missing for the comments~~ (Disabled SOAP API option completely. This needs to be refactored)
* ~~The Language file is missing for UMC~~
* ~~Relations between EAV and Flat entities don't work correctly~~.
* ~~Dropdown attributes don't work correctly for Flat entities~~.
* ~~Admin comments grid for generated entities do not include the entity title.~~
* ~~EAV Tree entities can be visible in frontend even if the parent is disabled.~~
* ~~For EAV entities there the URL rewrite key does not have a unique constraint~~.

