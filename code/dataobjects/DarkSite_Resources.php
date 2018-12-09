<?php

use SilverStripe\Security\Member;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\ORM\FieldType\DBDate;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\PasswordField;
use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Control\Session;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\File;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Security\Permission;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\FieldList;

class DarkSite_Resources extends DataObject {
	// pages that can be accessible during the dark site - must be refered from dark site otherwise will be redirected back to dark site
	private static $db = array(
		'Title' => 'Varchar(80)'
	);

	private static $has_one = array(
		'DarkResource' => File::class,
		'Parent' => 'DarkSite'
		/*	not linking to pages anymore
		'PageLink' => 'SiteTree'*/
	);

	private static $summary_fields =array(
		'Title' => 'Title',
		'DarkResource.Name' => 'FileName'
		/*'PageLink.Title' => 'Title',
		'PageLink.URLSegment' => 'Link'*/
	);
	public function canView($member = null, $context = []){

		return Member::currentUser()->inGroups(array('3','2'));
	}
	public function canCreate($member = null, $context = []){

		return Member::currentUser()->inGroups(array('3','2'));
	}
	public function canEdit($member = null, $context = []){

		return Member::currentUser()->inGroups(array('3','2'));
	}

	public function getCMSFields() {
		$a = array('pdf');
		$upload = new UploadField('DarkResource', 'Resource PDF File');
		$upload->setFolderName('Uploads/DarkSite/Resources');
		$upload->setAllowedExtensions($a);
		$f = new FieldList(
			$title = TextField::create('Title'),
			$upload
			//$dropdown = new SimpleTreeDropdownField('PageLinkID', 'Page Link', 'SiteTree')
		);
		//$dropdown->setEmptyString('Select One...');
		return $f;
	}
}
