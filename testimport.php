<?php
error_reporting(E_ALL & ~E_NOTICE);

include 'Y_CONFIG/database.php'; 
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Capsule\Manager as Capsule; 

	class Tbl extends Eloquent {
	    protected $table = 'properties';
	}

/*
Assemble the components of the complete API call, as per the docs...
The API Key is stored in the config file of course.

We need to make an initial call with the maximum-allowed page size (100)
From this response, we can expect to learn:
1. The total number of records
2. The total number of pages.

*/
$pagenumber = 1;
$objBody = getOnePage( $apikey, $pagenumber);
//var_dump($objBody);
$pageCount = $objBody->last_page;
$totalRecordCount = $objBody->total;


/*
Get an array of all of the GUID's in the database.
*/
$res = Tbl::select('uuid')->get()->toArray();

$alreadyStoredGUIDS = array();
foreach($res as $result){
	array_push($alreadyStoredGUIDS, $result['uuid']);
}

/*
We now have to take a chance.
We would hope that all of the records can be parsed
before our HTTP server times out.  This method works fine 
for the few thousand records we're looking at here, but would
not scale reliably over tens of thousands of records.

This is a very naive approach to the problem and we'll discuss
how to make it better in the after-project docs.

In any case, we now loop over the whole API record set,
100 records at a time, one page at a time.
*/

for (;;){
	//echo($pagenumber.'<br/>');

	getOnePage( $apikey, $pagenumber, 1 ,$pageCount,$alreadyStoredGUIDS);

	$pagenumber++;
	if($pagenumber > $pageCount){
		break;
	}
}

function getOnePage( $apikey, $pagenumber, $updateDBYN=0, $pageCount=1, $alreadyStoredGUIDS=null){
	$apiurl = "https://trialapi.PERSONSNAME.SERVERNAME.com/api/";
	$apiuri = "properties";
	$apiargs = "?api_key=". $apikey ."&page[number]=". $pagenumber ."&page[size]=100";

	$allcall = $apiurl . $apiuri . $apiargs;
	/*
	We will use the Guzzle HTTP library.
	It's not clear in this case how this is a great advantage over 
	a standard httpcall, but Guzzle seems to be a fairly modern/standard
	way of executing API calls....
	*/
	$client = new \GuzzleHttp\Client();
	$response = $client->get($allcall);

	$objBody = json_decode($response->getBody());




	if( $updateDBYN == 1 ){
			$jjj=0;
			foreach($objBody->data as $dataItem){
				if( false==in_array($dataItem->uuid, $alreadyStoredGUIDS) ){
					$entry = new Tbl();
					$entry->uuid = $dataItem->uuid;
					$entry->county = $dataItem->county;
					$entry->country = $dataItem->country;
					$entry->town = $dataItem->town;
					$entry->description = $dataItem->description;
					$entry->displayableAddress = $dataItem->address;
					$entry->image = $dataItem->image_full;
					$entry->thumbnail = $dataItem->image_thumbnail;
					$entry->latitude = $dataItem->latitude;
					$entry->longitude = $dataItem->longitude;
					$entry->numberOfBedrooms = $dataItem->num_bedrooms;
					$entry->numberOfBathrooms = $dataItem->num_bathrooms;
					$entry->price = $dataItem->price;
					$entry->propertyType = $dataItem->property_type->description;
					$entry->saleOrRent = $dataItem->type;
					$jjj++;

					$retval = $entry->save();
				}
			}
			echo("Succesfully imported block " . $pagenumber . " of " . $pageCount . "; ". $jjj ." records.<br/>");
			//die;
	}

	//var_dump($objBody->last_page);
	//var_dump($objBody->current_page);
	//die;
	return $objBody;
}

?>