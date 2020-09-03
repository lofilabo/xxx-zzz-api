<?php 

error_reporting(E_ALL & ~E_NOTICE);
include 'Y_CONFIG/database.php'; 
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Model as Eloquent;

/*
Pull in our HTML templates.  MVC, don't you know?
*/
$rowparsh = file_get_contents('partial-view-row.html');
$header = file_get_contents('partial-view-header.html');
$footer = file_get_contents('partial-view-footer.html');

$pagesize=100;

/*
1-line Model.  This whole application strictly observes MVC principles!!
*/
class Tbl extends Eloquent {
    protected $table = 'properties';
}

/*
First, delete an item if needed.
Which is say, if this controller is called with the delitem property set.
*/
if(isset($_GET["delitem"])){
	$delID=$_GET["delitem"];
	$res = Tbl::findOrFail($delID)->delete(); 
}

/*
Get a count of the records in our database.
Use it to get the number of pages we might display.
*/
$count = Tbl::count();
$pagecount = ceil($count / $pagesize);


/*
Second, a ton of boilerplate to see where we should be starting and finishing.....
*/

if(isset($_GET["prev"])){
	$prev=$_GET["prev"];
}else{
	$prev=0;
}

if(isset($_GET["pres"])){
	$pres=$_GET["pres"];
}else{
	$pres=0;
}

if(isset($_GET["next"])){
	$next=$_GET["next"];
}else{
	$next=0;
}

if($pres>0){
	$prev = $pres-1;
}else{
	$prev = 0;
}

if($next < $pagecount){
	$next = $pres+1;
}else{
	$next = $pagecount;
}

/*
Third, some basic maths to handle pagination...
*/
$startrecord = $pres * $pagesize;
$endrecord = ($pres * $pagesize) + $pagesize;
$headerstring = "Currently displaying: page " . $pres . " of " . $pagecount . "( records ". $startrecord ." to ". $endrecord ." of ". $count ." records).  "; 
$headerstring = $headerstring . "<a href='?pres=". $next ."''> NEXT </a>";
$headerstring = $headerstring . "<a href='?pres=". $prev ."''> PREV </a>";

/*
Forth, a pagination index.
*/
for ($i=1;$i<=$pagecount;$i++){
	$headerstring = $headerstring . "<a href='?pres=". $i ."''> | " . $i . " </a>";
}

$headerstring = $headerstring . "<br/>";
echo($headerstring);
	
	/*
	Fifth....write the actual listing!!

	Pick out of the database only the records we need.
	*/
	$companydata = Tbl::where('id', '>=', $startrecord)
    ->where('id', '<=', $endrecord)->get()
    ->toArray();
    /*
	ANCIENT OR MODERN PHP?
	The 'correct' way to handle this record would be to use the
	$obj->each(  function($in)use(){ });
	closure...but the Object returned by eloquent doesn't play nicely with this
	pattern.  So we still have to use the ->toArray() modifier to end up with a 
	far nastier data type; but at least we have the realtively-modern for-each
	construct....
    */
	echo($header);
	foreach( $companydata as $dataitem){
		/*
		replace the tokens in the HTML with real data.
		This allows separation of design and data.
		*/
		$row = $rowparsh;
		$delstring = "";
		$row = str_replace( "[[--delete--]]" , $dataitem['id'] , $row);
		$row = str_replace( "[[--pres--]]" , $pres , $row);
		$row = str_replace( "[[--county--]]" , $dataitem['county'] , $row);
		$row = str_replace( "[[--country--]]" , $dataitem['country'] , $row);
		$row = str_replace( "[[--town--]]" , $dataitem['town'] , $row);
		$row = str_replace( "[[--description--]]" , $dataitem['description'] , $row);
		$row = str_replace( "[[--displayableAddress--]]" , $dataitem['displayableAddress'] , $row);
		$row = str_replace( "[[--image--]]" , $dataitem['image'] , $row);
		$row = str_replace( "[[--thumbnail--]]" , $dataitem['thumbnail'] , $row);
		$row = str_replace( "[[--latitude--]]" , $dataitem['latitude'] , $row);
		$row = str_replace( "[[--longitude--]]" , $dataitem['longitude'] , $row);
		$row = str_replace( "[[--numberOfBedrooms--]]" , $dataitem['numberOfBedrooms'] , $row);
		$row = str_replace( "[[--numberOfBathrooms--]]" , $dataitem['numberOfBathrooms'] , $row);
		$row = str_replace( "[[--price--]]" , $dataitem['price'] , $row);
		$row = str_replace( "[[--propertyType--]]" , $dataitem['propertyType'] , $row);
		$row = str_replace( "[[--saleOrRent--]]" , $dataitem['saleOrRent'] , $row);
		/*
		Can you see how much nicer this would have been with a real list comprehension? 
		Another reason for using Laravel if you have it....
		*/
		echo($row);
	}
	echo($footer);
	error_reporting(0);

