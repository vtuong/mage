<html>
<title>Print Demo vtuong</title>
<?php
	require_once './app/Mage.php';
	Mage::app()->setCurrentStore(1);
	Mage::setIsDeveloperMode(true);
	ini_set("memory_limit", "1024M");
	set_time_limit(0);
	ob_start();
	echo "Begin</br>";

	$today = date("Y-m-d");
	$startDate=date("Y-m-d", strtotime("2010-10-10"));
	// echo $startDate . '|'.$today;


	$api=Mage::getModel('listraknewsletter/api');
	$contactlist=$api->reportRangeListContactRemoval(2,$startDate,$today,1);
	
	$unsubscribers=array();
	$page=2;
	while(!empty($contactlist->ReportRangeListContactRemovalResult->WSContactRemovalInfo)){
	    $unsubscribers=array_merge($unsubscribers,$contactlist->ReportRangeListContactRemovalResult->WSContactRemovalInfo);
	    $contactlist=$api->reportRangeListContactRemoval(1,$startDate,$today,$page);
	    $page++;
	}

	$ltUnsubscribers=array();
	foreach ($unsubscribers as $unsubscriber) {
		$ltUnsubscribers[]=trim(strtolower((string)$unsubscriber->EmailAddress));
	}

	echo "<br><b>Unsubscribers in Listrack</b>";
	echo "<pre>";
	echo "So luong la: ",count($ltUnsubscribers),"<br />";
	print_r(array_values($ltUnsubscribers));
	echo "</pre>";

	echo "End----------------------------------------------------</br>";
	echo "</br>";
	// echo "Begin subscribeFormPassThrough ----------------------------------------------------</br>";

	// $contactlist2=$api->subscribeFormPassThrough(1,"click",true,false);

	// echo "kieu cua contact la :",gettype($contactlist2);


?>
</html>