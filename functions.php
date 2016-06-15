<?php
set_time_limit(300);
error_reporting(E_ALL & ~E_NOTICE);

require_once('config.php');

$db= dbconnect();

if (!$db) {
	
	die("Database conection failed".mysqli_error());
}

$version="p 1.0"; // Version changes here

// Record new item and update if it is already recorded previously
function record_item($asin,$url,$item_name,$image_link,$cat,$brand,$review_count,$rating,$upc_final){
	
	$db= dbconnect();
	$sqlRecord="INSERT INTO items(id,url,title,image,category,brand_name,review_count,rating,state,referer_type,referer_id,upc,run_timestamp,collection_timestamp) 
	VALUES('$asin','$url','$item_name','$image_link','$cat','$brand','$review_count','$rating','unused','item','$asin','$upc_final',NOW(),NOW())";

	$sqlUpdate="UPDATE items SET url='$url', title='$item_name',image='$image_link', category='$cat', brand_name='$brand', review_count='$review_count', rating='$rating',upc='$upc_final', collection_timestamp=NOW() WHERE id='$asin' ";

	$sql3 = "SELECT * FROM items WHERE id='$asin'";
	$message='Executing SQL query: '.$sql3;
	l_info($message);
     $result = mysqli_query($db,$sql3);

     if (mysqli_num_rows($result) != 0) {
            
            //$emailError = "The Email address already exists!";
           $message='Executing SQL query: '.$sqlUpdate;
			l_info($message);
		   $check= mysqli_query($db,$sqlUpdate);
           	if(!$check){
           		//error_log("Item $asin upate Error message\n".mysqli_error($db), 3, "error.log");
				$message="Item $asin upate Error message\n".mysqli_error($db);
				l_error($message);
           	}

     } else {
		 	$message="Item Updated";
			l_info($message); 
			$message='Executing SQL query: '.$sqlRecord;
			l_info($message);
           $check=mysqli_query($db, $sqlRecord);
           	if(!$check){
           		//error_log("Item $asin record Error message\n".mysqli_error($db), 3, "error.log");
				$message="Item $asin record Error message\n".mysqli_error($db);
				l_error($message);
           	}
			
     }



}


// record brand if not recorded previously
function record_brand($name,$url,$asin){
	$db=dbconnect();
	$sqlRecord="INSERT INTO brands(name,url,state,referer_type,referer_id,run_timestamp,collection_timestamp) 
	VALUES('$name','$url','unused','item','$asin',NOW(),NOW())";

	$sql3 = "SELECT * FROM brands WHERE referer_id='$asin'";
	$message='Executing SQL query: '.$sql3;
	l_info($message);
     $result = mysqli_query($db,$sql3);

     if (mysqli_num_rows($result) != 0) {
            
            return false;

     } else {
		 
		 	$message='Executing SQL query: '.$sqlRecord;
			l_info($message);

          $check= mysqli_query($db, $sqlRecord);
          if(!$check){
          	//error_log("Brand $name for $asin record Error message\n".mysqli_error($db), 3, "error.log");
			$message="Brand $name for $asin record Error message\n".mysqli_error($db);
			l_error($message);
          }
		  
     }

	

}


// Record and update reviewer/ user
function record_reviewer($id,$profile_link,$location,$name,$review_count,$ranking){
	$db= dbconnect();
	$sqlRecord="INSERT INTO users(id,url,location,name,review_count,ranking,state,referer_type,referer_id,run_timestamp,collection_timestamp) 
	VALUES('$id','$profile_link','$location','$name','$review_count','$ranking','unused','item','blank',NOW(),NOW())";

	$sqlUpdate="UPDATE users SET location='$location', name='$name', review_count='$review_count', ranking='$ranking', collection_timestamp=NOW() WHERE id='$id' ";

	$sql3 = "SELECT * FROM users WHERE id='$id'";
	$message='Executing SQL query: '.$sql3;
			l_info($message);
     $result = mysqli_query($db,$sql3);

     if (mysqli_num_rows($result) != 0) {
            
			$message='Executing SQL query: '.$sqlUpdate;
			l_info($message);
           $check=mysqli_query($db,$sqlUpdate);
           if(!$check){
           	//error_log("$name reviewer update Error message\n".mysqli_error($db), 3, "error.log");
			$message="$name reviewer update Error message\n".mysqli_error($db);
			l_error($message);
           }
		   else {
				$message="User Updated";
			l_info($message);   
		   }

     } else {
		 	
			$message='Executing SQL query: '.$sqlRecord;
			l_info($message); 
           $check=mysqli_query($db, $sqlRecord);
           if(!$check){
           	//error_log("$name reviewer record Error message\n".mysqli_error($db), 3, "error.log");
			$message="$name reviewer record Error message\n".mysqli_error($db);
			l_error($message);
           }
		  
		   
			   
     }

}


// Record reviews
function record_reviews($id,$review_url,$asin,$user_id,$rating,$review_date,$review_text,$title,$helpful_yes,$helpful_total,$verified_purchaser){
	$db=dbconnect();
	
	
	$sqlRecord="INSERT INTO reviews(id,url,item_id,user_id,rating,date,text,title,helpful_yes,helpful_total,verified_purchaser,image_count,video,state,referer_type,referer_id,run_timestamp,collection_timestamp) 
	VALUES('$id','$review_url','$asin','$user_id','$rating','$review_date','$review_text','$title','$helpful_yes','$helpful_total','$verified_purchaser','unused','unused','unused','item','$asin',NOW(),NOW())";

	
	$sql3 = "SELECT * FROM reviews WHERE referer_id='$asin' AND id='$id'";
	 $message='Executing SQL query: '.$sql3;
	l_info($message);
     $result = mysqli_query($db,$sql3);
	

     if (mysqli_num_rows($result) != 0) {
            
            return false;

     } else {
		 
		 	$message='Executing SQL query: '.$sqlRecord;
			l_info($message);

           $result=mysqli_query($db, $sqlRecord);
		   
           if (!$result) {
           	echo "error:". mysqli_error($db);
           	//error_log("$asin review record Error message\n".mysqli_error($db), 3, "error.log");
			$message="$asin review record Error message\n".mysqli_error($db);
			l_error($message);
           }
		   else {
				$message="Review Recorded";
			l_info($message); 
			echo "Review Recorded\n";  
		   }
		   
     }


}


// parsing reviewers with unique ID and populating in database
function parse_reviewer($reviewer_id,$sleepTime){


		include_once('vendor/dom/simple_html_dom.php');

		$reviewer_id=$reviewer_id;
		$profile_link='http://www.amazon.com/gp/pdp/profile/'.$reviewer_id;  // Profile link

		sleep($sleepTime);
		
		//$html = file_get_html($profile_link);
		if($html = file_get_html($profile_link)){
			$message="Getting the Profile HTML for Reviewer ID : ".$reviewer_id;
			l_info($message);
		} else {
			$message="Error Getting the Profile HTML for Reviewer ID : ".$reviewer_id;
			l_error($message);
		}
			
				
		$profile=str_get_html($html->find('div[class=profile-details-column]',0)->innertext);

		 //$reviewer_name= $profile->find('h1', 0)->innertext; // reviewer name
		if($reviewer_name= $profile->find('h1', 0)->innertext) {
			$message="Getting the Reviewer Name.";
			l_info($message);
		}
		else
		{
			$message="Error Getting the Reviewer Name.";
			l_error($message);	
		}
		//$country= $profile->find('div[class=a-row a-spacing-micro]', 0)->innertext;

		if($country= $profile->find('div[class=a-row a-spacing-micro]', 0)->innertext) {
			
			$message="Getting the Country Name.";
			l_info($message);
		}
		else
		{
			$message="Error Getting the Country Name.";
			l_error($message);	
		}
		//$location= strip_tags($country); // Country location

		if($location= strip_tags($country)) {
			$message="Getting the Location.";
			l_info($message);
		}
		else
		{
			$message="No Location Found.";
			l_error($message);	
		}
		
		if($rank=$profile->find('div[class=a-row a-spacing-small] span[class=a-size-small a-color-secondary]',0)->plaintext) {
			$reviewer_rank=explode("#",$rank);
			$reviewer_rank=$reviewer_rank[1];
			$reviewer_rank=str_replace(',', '',$reviewer_rank);
			//print_r($reviewer_rank);	
		} else if($rank= $profile->find('span[class=a-size-large a-text-bold]', 0)->outertext) {
			$reviewer_rank=explode("#",$rank);
			$reviewer_rank=$reviewer_rank[1];
			$reviewer_rank=str_replace(',', '',$reviewer_rank);
			//print_r($reviewer_rank);
			
		} else {
			$reviewer_rank=0;
			$message="No reviewer rank found.";
			l_error($message);
			
		}
		
		//echo "RANK: ".$rank;
		//$rank= $profile->find('span[class=a-size-large a-text-bold]', 0)->outertext;
		
		//$reviewer_rank= strip_tags($rank);
		//echo "RANK: ".$reviewer_rank;
		/*if (preg_match("/%/", $reviewer_rank)) {
			$reviewer_rank= "";
			$message="No reviewer rank found.";
			l_error($message);
		}*/


		$rev_total= $profile->find('div[class=reviews-link]', 0)->outertext;
		//$review_count= strip_tags($rev_total);
		if($review_count= strip_tags($rev_total)) {
			//echo "REVIEW COUNT: ".$review_count;
			$review_count=explode('(',$review_count);
			$review_count=explode(')',$review_count[1]);
			$review_count=$review_count[0];
			//echo "REVIEW COUNT".$review_count;
			$message="Getting Total Review Count";
			l_info($message);
		} else
		{
			$message="Error Getting Total Review Count";
			l_error($message);
		}

		//use mysql_real_escape_string() to avoid sql injection
		//$reviewer_id=mysql_real_escape_string($reviewer_id);
		//$profile_link=mysql_real_escape_string($profile_link);
		//$location=mysql_real_escape_string($location);
		//$reviewer_name=mysql_real_escape_string($reviewer_name);
		//$review_count=mysql_real_escape_string($review_count);
		//$reviewer_rank=mysql_real_escape_string($reviewer_rank);
		

		$check=record_reviewer($reviewer_id,$profile_link,$location,$reviewer_name,$review_count,$reviewer_rank);



		if ($check=true) {
			$message="User Recorded";
			l_info($message);
			echo "User Recorded\n";
		} else {
			$message="User Not Recorded";
			l_error($message);
		}


}


function parse_item_brand($asin,$sleepTime){

		include_once('vendor/dom/simple_html_dom.php');


		$asin=$asin;
		$url='http://www.amazon.com/dp/'.$asin;
		sleep($sleepTime);
		//$html = file_get_html($url);
		if($html = file_get_html($url)) {
			$message="Getting the HTML for Brand";
			l_info($message);
		} else
		{
			$message="Error Getting the HTML for Brand";
			l_error($message);
		}

		//$cat=$html->find('option[selected=selected]',0)->innertext; // Category name

		if($cat=$html->find('option[selected=selected]',0)->innertext)
		{
			$message="Getting the Category";
			l_info($message);
		} else
		{
			$message="Error Getting the Category";
			l_error($message);
		}
		//$name= $html->find('h1', 0)->innertext;
		
		if($name= $html->find('h1', 0)->innertext) {
			$message="Getting the Item Name";
			l_info($message);
		} else {
			$message="Error Getting the Item Name";
			l_error($message);
		}
		
			 $item_name= strip_tags($name); // Item name

		//$brand=$html->find('a[id=brand]',0)->innertext;  // Brand Name
		if($brand=$html->find('a[id=brand]',0)->innertext) {
			$message="Getting the Brand Name";
			l_info($message);
		} else {
			$message="Error Getting the Brand Name";
			l_error($message);
		}
		//$brand_link=$html->find('a[id=brand]',0)->href;
		if($brand_link=$html->find('a[id=brand]',0)->href) {
			$message="Getting the Brand Link";
			l_info($message);
		} else {
			$message="Error Getting the Brand Link";
			l_error($message);
		}
		$brand_url_link='http://www.amazon.com'.$brand_link; // Brand link

		//$reviews=$html->find('span[id=acrCustomerReviewText]',0)->innertext; //total reviews
		if($reviews=$html->find('span[id=acrCustomerReviewText]',0)->innertext) {
			//echo "REVIEW: ".$reviews;
			//Review Count for Item
			$review_count=explode(" ",$reviews);
			$review_count=$review_count[0];
			//echo $review_count;
			$message="Getting the Total Reviews";
			l_info($message);
		} else {
			$message="Error Getting the Total Reviews";
			l_error($message);
		}

		 //$img=$html->find('div[id=imgTagWrapperId]',0)->innertext;
		 if($img=$html->find('div[id=imgTagWrapperId]',0)->innertext) {
			$message="Getting the Html For Image";
			l_info($message);
			$main= str_get_html($img);
		 	$image_link= $main->find('img',0)->src; // items's main image link
		} else {
			$message="Error Getting the Html for Image";
			l_error($message);
		}
		 //$main= str_get_html($img);
		 //$image_link= $main->find('img',0)->src; // items's main image link


		//$review_rating_icon = $html->find('a[class=a-popover-trigger a-declarative] span');
		if($review_rating_icon = $html->find('a[class=a-popover-trigger a-declarative] span')) {
			$message="Getting the Html For Item Review";
			l_info($message);
			$rating = $review_rating_icon[0]->plaintext; // Items average rating
			$rating=explode(" ",$rating);
			$rating=$rating[0];
			//echo "RATING : ".$rating;
			
		} else {
			$message="Error Getting the Html for Item Review";
			l_error($message);
		}

		////////////////////////////
		//get the upc//////////////
		/////////////////////////////
		
		
		
		
		$product_details=$html->find('div[id=detail-bullets] table');
		$listed_ul=$product_details[0]->find('div[class=content] ul');
		//print_r($product_details[0]->innertext);
		$listed_li=$listed_ul[0]->find('li');
		foreach($listed_li as $li)
		{
			//echo $li->innertext;
			//$b_text=$li->find('b');
			//foreach($b_text as $single_b) {
		
			//echo $single_b->innertext;	
			//}
		
			$all=$li->find('b');
			$upc_field=$all[0]->innertext;
			//print_r($all[0]->outertext);
			//echo $upc_field;
			$upc_field=strtolower($upc_field);
		
			if($upc_field=='upc:') {
				$upc=$li->plaintext;
				//echo "UPC FOUND";
				
				$upc=explode(" ",$upc);
			 	$upc=array_splice($upc,1,sizeof($upc));
				//print_r($upc);

			 	$upc_final=implode($upc,',');
				//echo "UPC: ".$upc_final;
			 
			
			} 

			
		

			//echo "<hr>";	
		}
		if($upc_final=='') {
			$message="UPC not Found";
			l_error($message);
		}
		else
		{
			$message="Getting the UPC";
			l_info($message);
		}
		/////////////////////////////////


		 
			
			
		 //$rating = $review_rating_icon[0]->plaintext; // Items average rating




		// recording items
		//use mysql_real_escape_string() to avoid sql injection
		//$asin=mysql_real_escape_string($asin);
		//$url=mysql_real_escape_string($url);
		//$item_name=mysql_real_escape_string($item_name);
		//$image_link=mysql_real_escape_string($image_link);
		//$cat=mysql_real_escape_string($cat);
		//$brand=mysql_real_escape_string($brand);
		//$reviews=mysql_real_escape_string($reviews);
		//$rating=mysql_real_escape_string($rating);
		 $fillup = record_item($asin,$url,$item_name,$image_link,$cat,$brand,$review_count,$rating,$upc_final);
		 if ($fillup=true) {
		 	$message="Item Recorded";
			l_info($message);
			echo "Item Recorded\n";	
		 } else
		 {
			$message="Item Not Recorded";
			l_error($message);
			echo "Item Not Recorded\n";
		 }



		// Recording brands
		//$brand_url_link=mysql_real_escape_string($brand_url_link);
		  $fillup2=record_brand($brand,$brand_url_link,$asin);

		  if ($fillup2=true) {
		  	 $message="Brand Recorded";
			l_info($message);
			echo "Brand Recorded\n";
		  } else {
			$message="Brand Not Recorded";
			l_error($message);
			echo "Brand Not Recorded\n";  
		  }
		  
	
}








//function for log

function logEntry($message,$prepend = ""){
    $cli = (php_sapi_name() == 'cli'); // you may want to ignore this
    $date = date("Y-m-d H:i:s");
    file_put_contents('logs/log.txt', $date . ' ' . $prepend . $message . PHP_EOL, FILE_APPEND);
}

function l_info($message){
    logEntry($message, '[-] ');
}

function l_warning($message)
{
    logEntry($message, '[?] ');
}

function l_error($message)
{
    logEntry($message, '[!] ');
}
?>



