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


class DarkSite_Release extends DataObject {
	// pdfs for the dark site
	private static $db = array(
		'Title' 	=> 'Varchar(80)',
		'Excerpt' 	=> 'Text',
		'Date' 		=> 'Date',
		'HideInRSS' => 'Boolean',
	);

	private static $has_one = array(
		'Parent' => 'DarkSite',
		'DarkRelease' => File::class,
	);

	private static $summary_fields = array(
		'Title'				=> 'Title',
		'Date' 				=> 'Date',
		'DarkRelease.Title' => 'Press Release PDF'
	);

	private static $default_sort = 'Date ASC';

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
		$uploadify = new UploadField("DarkRelease", "Press Release PDF");
		$uploadify->setFolderName('Uploads/DarkSite/PressReleases');
		$uploadify->setAllowedExtensions($a);
		if(!Permission::check('ADMIN')) {
			//$uploadify->removeFolderSelection();
		}
		$datefield = new DateField(DBDate::class, 'Press Release Date');
		$datefield->setConfig('showcalendar', true);
		$datefield->setConfig('showdropdown', true);
		$datefield->setConfig('dateformat', 'MM/dd/YYYY');

		$f = new FieldList(
			$datefield,
			TextField::create('Title'),
			new TextareaField('Excerpt', 'Excerpt'),
			$uploadify,
			CheckboxField::create('HideInRSS')->setTitle('Hide Press Release from RSS')
		);
		return $f;
	}
}
