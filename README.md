Ultimate Module Creator 1.9-dev1
======

New Ultimate Module Creator for Magento 1.7 +
-------------

This is the new version of <a href="https://github.com/tzyganu/moduleCreator">Ultimate Module Creator</a>. It is still under development so **DON'T USE IN PRODUCTION.**.  


This should be the version 2.0 of the Magento extension but I'm keeping this version for the Module Creator for Magento 2.0.  

Backwards compatibility has beed broken.

If someone wants to help with the testing please submit any bugs, improvements or feature requests <a href="https://github.com/tzyganu/UMC1.9/issues">here</a>

Also any good code is welcomed.

**Note**
For the rest of the document UMC = Ultimate Module Creator

**Release Notes 1.9.0-dev1**

|Type|Label|Comment|
|----|-----|-------|
|Feature|Added EAV entities|Now you can create EAV entities that you can manage just like products and categories.|
|Feature|Added many to many link to categories|	Now you can link you entities "many to many" with the catalog categories|
|Feature|Added comment feature|You can choose to allow customers to write comments on your entities.|
|Feature|Made extension "extensible"|Yeah...like someone is going to extend it|
|Feature|Many to many relations between tree entities|Now the tree entities can be related in "Many to many"|
|Feature|Module menu can be placed anywhere|Until now the menu could be placed in admin only on top level. Now it can be placed anywhere in the menu tree.|
|Feature|Added new attribute types|dorpdown, multiselect. Can have different sources - still experimental.|
|Feature|Custom entities can be added as product, category and customer attributes.| |
|Feature|You can set the version of your generated module.|No more hard coded 0.0.1 version.|
|Refactor|Source file refactoring|Because some IDEs show errors when parsing incomplete PHP files I've removed the extension of the source files (etc/source)|
|Refactor|Refactored JS|The UI of the module creator now uses prototype classes instead of simple JS functions.|
|Refactor|UI refactor|'Pimped up' the UI of the module creator||
|Refactor|Removed frontend package and theme fields|base/default is used for all modules.|
|Refactor|Changed identation from TAB to 4x space||
|Improvement|Improved speed of the module creator|No more AJAX calls to add an entity/attribute. The module save is done in the same step as validation.|
|Improvement|Module creator generate sql uninstall script|Along with the list of generated files now an sql uninstall script is generated for the created module.|
|Bug fix|Fixed bug that appears when there is an uppercase letter in the module name.|In previous version 'ModuleName' was not working on UNIX servers. Only 'Modulename'. This is fixed in this version.|  


**Known Issues - 1.9.0-dev1**

* The SOAP API does not work for EAV entities and is missign for the commets
* The Language file is missing for UMC
* Relations between EAV and Flat entities don't work correctly.
* Dropdown attributes don't work correctly for Flat entities.
* Admin comments grid for generated entities do not include the entity title.
* EAV Tree entities can be visible in frontend even if the parent is disabled.
* For EAV entities there the URL rewrite key does not have a unique constraing.

