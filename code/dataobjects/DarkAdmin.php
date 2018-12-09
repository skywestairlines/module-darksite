<?php

use SilverStripe\View\Requirements;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Control\Director;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\GridField\GridFieldDetailForm_ItemRequest;

class DarkAdmin extends ModelAdmin {
	public function init() {
		parent::init();
		Requirements::javascript('darksite/javascript/darkAdmin.js');
	}

	private static $managed_models = array(
		'DarkSite',// => array('record_controller' => 'DarkAdmin_RecordController'),
		'Partners'				// ! un-comment to edit partners list
		//'DarkSite_Password'		// ! this line should only be un-commented out when you need to set or change the password!!!!!!
	);

	private static $url_segment = 'darkAdmin';
	private static $menu_title = 'Dark Site';
	private static $menu_icon = '';
	private static $set_page_length = 100;

	var $showImportForm = false;

	function getEditForm($id = null, $fields = null){
		 $form = parent::getEditForm($id , $fields);
		 $listfield = $form->Fields()->fieldByName($this->modelClass);
		 if($gridField = $listfield->getConfig()->getComponentByType(GridFieldDetailForm::class)) {
            $gridField->setItemRequestClass('DarkAdminPublishFieldDetailForm_ItemRequest');
        }
        return $form;


	}
}
