<?php

use SilverStripe\Security\Member;
use SilverStripe\Control\Director;
use App\Pagetypes\HomePage;
use SilverStripe\Control\Controller;
use SilverStripe\Security\Permission;
use SilverStripe\Dev\Debug;
use SilverStripe\Control\Session;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\RSS\RSSFeed;
use SilverStripe\View\Requirements;
use SilverStripe\ORM\FieldType\DBDate;
use SilverStripe\ORM\ArrayList;

class DarkSiteHoldingPageController extends PageController {

 private static $allowed_actions = array(

 );

 public function init() {
   RSSFeed::linkToFeed($this->Link() . 'rss');
   parent::init();
   Requirements::css('darksite/css/darkStyle.css');

 }

 public function index() {
   //$url = $_REQUEST['url'];
   $params = $this->getURLParams();
   //Debug::show($params['ID']);
   if(is_numeric($params['ID']) && $f = DarkSite::get()->filter('FltNum' ,$params['ID'])->limit(1)) {
     //Debug::show('found');
     return $this->customise($f)->renderWith('IncidentPage', 'DarkSiteHoldingPage');
     //return $this->latestIncidentID($params['ID']);
   } else {
     //Debug::show('not found!');
     //return self::httpError(404, 'Sorry that flight number could not be found.');
     if($f = DarkSite::get()->filter('Active','1')->limit(1)) {
       //debug::show($f);
       return $this->Customise($f)->renderWith('DarkSiteHoldingPage', 'Page');
     } else {
       return self::httpError(404, 'Sorry that flight number could not be found.');
     }
   }
 }



 public function FltNum($fltNum = '') {
   if($fltNum) {
     if($f = DarkSite::get()->filter('FltNum',$fltNum)->limit(1)) {
       // return flt incident stuff
       Debug::show('in FltNum');
       return $this->customise($f)->renderWith('DarkSiteHoldingPage', 'Page');
     } else {
       // return 404 page
       //return self::httpError(404, 'Sorry that flight number could not be found.');
     }
   } else {
     // return 404 page
     //return $this->customise($f)->httpError(404, 'No flight number was given.');
   }
 }
 function rss() {
   $rss = new RSSFeed($this->getDarkReleases(), $this->Link(), 'Press Releases', 'ExpressJet Airlines Press Releases', 'Title', 'Excerpt', DBDate::class, DBDate::class);

   //RSSFeed($entries, $link, $title, $Desc, $titleField, $DescriptionField, $authorField, $lastModified, $eTag);
   $rss->outputToBrowser();
   //$rss1->outputToBrowser();
 }

 public function getDarkReleases() {
   $this->currentDay		= date('Y-m-d');
   $this->currentYear	 	= date('Y-m-d', mktime(0, 0, 0, 1, 1, date('Y')));
   $this->currentMonth 	= date('Y-m-d', mktime(0, 0, 0, 1, date('m'), date('Y')));
   $this->lastYear 		= date('Y-m-d', strtotime($this->currentYear . ' -1 year'));		//date('Y-m-d', strtotime($this->firstDay . ' -1 year'));
   $this->twoYearsAgo 		= date('Y-m-d', strtotime($this->currentYear . ' -2 years'));		//date('Y-m-d', strtotime($this->firstDay . ' -2 years'));
   return DarkSite_Release::get()->where("Date >= '". $this->lastYear ."' AND HideInRSS = '0'")->sort(DBDate::class, 'DESC');	//  Hiding PRs from the RSS
 }

 /**
  * latestIncidentID function.	returns the ID of the most recent incident - need to overload this if user puts in flt number in url
  *
  * @access private
  * @return void
  */
 public function latestIncidentID($fltNum = '') {
   $params = $this->getURLParams();
   //Debug::show($params['ID']);
   if($fltNum) {
     //Debug::show('flt num given');
     $f = "`FltNum` = '$fltNum'";
   } elseif(is_numeric($params['ID'])) {
     //Debug::show('flt num in params');
     $f = "`FltNum` = '". $params['ID'] ."'";
   } else {
     //Debug::show('flt num NOT given');
     $f = '';
   }
   if($d = DarkSite::get()->where($f)->limit(1)) {
     //Debug::show(DataObject::get_one('DarkSite', $f));
     return $d['ID']->ID;
   }
 }

 public function MainStatement() {
   if($main = DarkSite::get()->byID($this->latestIncidentID())) {
     //Debug::show($this->latestIncidentID());
     return $main;
   }
   return false;
 }

 public function DarkReleases() {
   if($pr = DarkSite_Release::get()->where("`ParentID` = '". $this->latestIncidentID() ."'")->sort(DBDate::class, 'ASC')->limit(3)) {
     return $pr;
   }
   return false;
 }

 public function DarkResources() {
   if($resources = DarkSite_Resources::get()->where("`ParentID` = '". $this->latestIncidentID() ."'")->limit(3)) {
     return $resources;
   }
   return false;
 }

 public function DarkPartner() {
   if($partner = Partners::get()->where("`DarkSite_Partnerss`.`DarkSiteID` = '". $this->latestIncidentID() ."'")->sort('Title', 'ASC')) {
     return $partner;
   }
   return false;
 }
 public function GetTweets(){
   require_once('_auth.php');
   $decode = json_decode($json, true);
   $arr = array();
   $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
   $reg_exHash = "/#([a-z_0-9]+)/i";
   $reg_exUser = "/@([a-z_0-9]+)/i";
   foreach($decode as $tweet){
     $tweet_text = $tweet["text"]; //get the tweet
         if($tweet['in_reply_to_user_id'] == null){
         // make links link to URL
         if(preg_match_all($reg_exUrl, $tweet_text, $url) > 0) {
           //Debug::show($url);
         foreach ($url[0] as $srtUrl) {
               // Check each tag to see if there are letters or an underscore in there somewhere

                 $tweet_text = str_replace($srtUrl, '<a href="'.substr($srtUrl, 0).'" target="_blank">'.$srtUrl.'</a>', $tweet_text);

             }
           // make the urls hyper links
           //$tweet_text = preg_replace($reg_exUrl, "<a target='_blank' href='{$url[0]}'>{$url[0]}</a> ", $tweet_text);

         }



         if(preg_match($reg_exUser, $tweet_text, $user)) {

             $tweet_text = preg_replace("/@([a-z_0-9]+)/i", "<a target='_blank' href='http://twitter.com/$1'>$0</a>", $tweet_text);

         }

         if(preg_match_all($reg_exHash, $tweet_text, $hash) > 0) {
           //Debug::show($hash);
           foreach ($hash[0] as $strHashtag) {
               // Check each tag to see if there are letters or an underscore in there somewhere
             if (preg_match('/#\d*[a-z_]+/i', $strHashtag)) {
                 $tweet_text = str_replace($strHashtag, '<a href="https://twitter.com/search?q=%23'.substr($strHashtag, 1).'" target="_blank">'.$strHashtag.'</a>', $tweet_text);
               }
             }


           // make the hash tags hyper links    https://twitter.com/search?q=%23truth
           //$tweet_text = preg_replace($reg_exHash, "<a href='https://twitter.com/search?q={$hash[0][0]}' target='_blank'>{$hash[0][0]}</a> ", $tweet_text);
           //Debug::show($tweet_text);
           // swap out the # in the URL to make %23
           $tweet_text = str_replace("/search?q=#", "/search?q=%23", $tweet_text );

         }
         $arr[] = array('text' => $tweet_text);
       }else{

       }

     }
   //print_r($arr);
   return new ArrayList($arr);
 }
}
