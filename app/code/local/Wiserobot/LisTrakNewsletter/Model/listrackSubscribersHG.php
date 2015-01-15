<?php
require_once './app/Mage.php';
Mage::app()->setCurrentStore(2);
Mage::setIsDeveloperMode(true);
ini_set("memory_limit", "1024M");
set_time_limit(0);
ob_start();
echo "Begin";

//$listrackSubscribers=Mage::getModel('listraknewsletter/api')->ReportSubscribedContacts(2);


/*
echo "<pre>";
print_r($listrackSubscribers->ReportSubscribedContactsResult->WSContactSubscriptionInfo);
echo "</pre>";
*/
$ltSubscribers=array();


/*
foreach ($listrackSubscribers->ReportSubscribedContactsResult->WSContactSubscriptionInfo as $ltSubscriber) {
	$ltSubscribers[]=trim(strtolower((string)$ltSubscriber->EmailAddress));
}
*/

$api=Mage::getModel('listraknewsletter/api');
$contactlist=$api->ReportSubscribedContacts(2);
$subscribers=array();
$page=2;
while(!empty($contactlist->ReportSubscribedContactsResult->WSContactSubscriptionInfo)){
    $subscribers[]=$contactlist->ReportSubscribedContactsResult->WSContactSubscriptionInfo;
    $contactlist=$api->ReportSubscribedContacts(2,$page);
    $page++;
}

foreach ($subscribers as $ltSubscriber) {
	$ltSubscribers[]=trim(strtolower((string)$ltSubscriber->EmailAddress));
}


$mageSubscribers=array();
$mageSubscriberCollections=Mage::getModel('newsletter/subscriber')->getCollection()->addStoreFilter(2);
foreach ($mageSubscriberCollections as $_mageSubscriber) {
	$mageSubscribers[]=trim(strtolower($_mageSubscriber->getEmail()));
}


/*
$mageSubscribers=array();
$mageSubscriberCollections=Mage::getModel('newsletter/subscriber')->getCollection()->addStoreFilter(2);
foreach ($mageSubscriberCollections as $_mageSubscriber) {

	$mageSubscribers[]=trim(strtolower($_mageSubscriber->getEmail()));
}
*/

echo "<br><b>Listrack subscribers</b>";
echo "<pre>";
echo "So luong la: ",count($ltSubscribers),"<br />";
print_r($ltSubscribers);
echo "</pre>";

echo "<br><b>Magento subscribers</b>";
echo "<pre>";
echo "So luong la: ",count($mageSubscribers),"<br />";
print_r($mageSubscribers);
echo "</pre>";


echo "<br><b>Subscribers in Magento but not in Listrack in Healthygoods</b>";
echo "<pre>";
$arr=array_diff($mageSubscribers, $ltSubscribers);
echo "So luong la: ",count($arr),"<br />";
print_r(array_values($arr));
echo "</pre>";


echo "End";
