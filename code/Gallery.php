<?php

class Gallery extends DataExtension {
	public static $has_many = array('Items' => 'GalleryItem');

	
	public function getGalleryItems(){
		return $this->owner->Items();
	}
	
	public function getGalleryDefaultURL() {
		$owner = $this->owner;
		$urlSegment = $owner->ID . '-' . $owner->generateURLSegment($owner->Title);
		$dirName = 'galleries/' . $urlSegment;
		return $dirName;
	}
	
	public function updateCMSFields(FieldList $fields) {
		$config = GridFieldConfig::create();
		$config->addComponent(new GridFieldToolbarHeader());
		//$config->addComponent(new GridFieldAddNewButton('toolbar-header-right'));
		$config->addComponent(new GridFieldDataColumns());
		$config->addComponent(new GridFieldEditButton());
		$config->addComponent(new GridFieldDeleteAction());
		$config->addComponent(new GridFieldDetailForm());
		$config->addComponent(new GridFieldSortableHeader());
		$config->addComponent(new GridFieldSortableRows('SortOrder'));


		//Bulk image upload
    $config->addComponent(new GridFieldBulkEditingTools());
    
		$bulkUpload = new GridFieldBulkImageUpload();
		$owner = $this->owner;
		$dirName = $owner->getGalleryDefaultURL();
		$bulkUpload->setConfig('folderName', $dirName);
		$bulkUpload->setConfig('fieldsNameBlacklist', array('OverrideTitle'));
		
		$config->addComponent($bulkUpload);		
		
		$gridField = new GridField('Items','', $this->owner->Items(), $config);
		$fields->addFieldToTab('Root', new Tab('Gallery'));
		$fields->addFieldToTab('Root.Gallery', $gridField);
		return $fields;
	}
}