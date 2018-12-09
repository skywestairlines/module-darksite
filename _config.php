<?php

use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\ORM\DataObject;

//Extend the site config
// DataObject::add_extension(SiteConfig::class, DarkSiteConfigDecorator::class);

//Extend the controller
// DataObject::add_extension(PageController::class, DarkSiteControllerExtension::class);

// making the has_many dataObjects sortable
//SortableDataObject::add_sortable_classes(array(
	//'DarkSite_Release',
	//'DarkSite_Resources',
	//'Partner'
//));

// add rule for displaying fltnum in the url - i.e. www.skywest.com/1138
/*Director::addRules(2, array(
	'$ID!' => 'DarkSiteHoldingPage_Controller'
));*/
