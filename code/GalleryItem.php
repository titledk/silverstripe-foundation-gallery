<?php

class GalleryItem extends DataObject {
	public static $db = array(
		"OverrideTitle" => 'Varchar',
		"SortOrder" => "Int",
	);
	public static $has_one = array(
		'Image' => 'Image',
		"Page" => "Page"
	);

	static $default_sort = 'SortOrder';	
	
	static $summary_fields = array(
		'Thumbnail' => 'Thumbnail',
		'getTitle' => 'Title'
	);
	
	function getThumbnail() {
		if (((int) $this->ImageID > 0) && (is_a($this->Image(),'Image')))  {
	   return $this->Image()->SetWidth(50); 
		} else {
			return _t('No thumbnail available');
		}
	}
	
	function getTitle(){
		$override = $this->OverrideTitle;
		$image = $this->Image();
		$title = '';
		if ($image->exists()) {
			$title = $image->Title;
		}
		if (strlen($override) > 0) {
			$title = $override;
		}
		return $title;
	}
	
	
	public function getCMSFields(){
		$fields = parent::getCMSFields();
		
		//remove unused fields
		$fields->removeByName('Image'); //this is added manually later
		$fields->removeByName('SortOrder');
		$fields->removeByName('PageID');
		
		
		$fields->removeByName('OverrideTitle');
		
		$fields->addFieldToTab('Root.Main', 
			TextField::create('OverrideTitle','Override Title')
			->setRightTitle('Add a title here to override the image title (editing the image\'s title is preferred)')
		);
		
		
		//adding upload field - if slide has already been saved
		if ($this->ID) {
			$UploadField = new UploadField('Image');
			$UploadField->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
			$UploadField->setConfig('allowedMaxFileNumber', 1);
			$dirName = $this->Page()->getGalleryDefaultURL();
			$UploadField->setFolderName($dirName);
			
			$fields->addFieldToTab('Root.Main',$UploadField);
		} else {
			$fields->addFieldToTab('Root.Main', new LiteralField('SaveFirst',"You will be able to add the image once you save for the first time"));
		}
		
		//allow extending this object with another 
		$this->extend('updateCMSFields', $fields);
		
		return $fields;
	}
}
