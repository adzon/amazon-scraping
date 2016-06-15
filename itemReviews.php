<?php

set_time_limit(300);
error_reporting(E_ALL & ~E_NOTICE);

require_once('functions.php');

if (isset($argv[1]) && isset($argv[2]) && isset($argv[3])) {

	$asin=$argv[1];
	$minSleep=$argv[2];
	$maxSleep=$argv[3];
	// generate sleep time
	//$sleepTime=rand($minSleep,$maxSleep);
}
else {
	echo "Enter in correct format";
	die();	
	
}

//require the PARSING library
require('vendor/dom/simple_html_dom.php');

//create an object 

$html1= new simple_html_dom;
$html= new simple_html_dom;


$asin=$asin;

// Parsing the item and brand 
parse_item_brand($asin,$sleepTime); 






//load the url

$html1->load_file('http://www.amazon.com/product-reviews/'.$asin);
$message="Getting Page : http://www.amazon.com/product-reviews/ ".$asin;
l_info($message);

/* get all the  review text
$all_customer_review_text = $html->find('span[class=a-size-base review-text]');

foreach ($all_customer_review_text as $single_review_text) {
	
	echo $single_review_text->plaintext;
	echo "BREAK <br>";
		
}
*/
///////////////////////////////////
//////Get the total Page Number from the page
////////////////////////////////////////////

$total_page_number=$html1->find('li[class=page-button] a');
$length=sizeof($total_page_number);
$total_pages=$total_page_number[$length-1]->plaintext;

$total_pages=str_replace(',','',$total_pages);
//echo "TOTAL PAGES: ".$total_pages;

if ($total_pages<=1 || $total_pages=false) {
		
		
		// generate sleep time
		$sleepTime=rand($minSleep,$maxSleep);
		//sleeps for each iteration
		sleep($sleepTime);
		

		$url='http://www.amazon.com/product-reviews/'.$asin;
		$html->load_file($url);
		$message="Getting the Html For $asin";
		l_info($message);
		echo "Getting Page : ".$url;




		$all_review_div = $html->find('div[id=cm_cr-review_list] div[class=a-section review]');

			foreach ($all_review_div as $single_review_div) {
				
				//echo $single_review_div->outertext;
				//echo "<br> <br>";
				
				$id=$single_review_div->id;
				//echo "ID: ".$id;
				//echo "<br><br>";
				$url="http://www.amazon.com/gp/customer-reviews/".$id;
				
				//echo "URL :".$url;
				
				//echo "<br>";
				
				///////////////////
				//item ID Should be ASIN Number
				///////////
				
				//$user_url_anchor_tag = $single_review_div->find('a[class=a-size-base a-link-normal author]');
				
				//echo $user_url_anchor_tag[0]->outertext;
				
				//$user_url=$user_url_anchor_tag[0]->href;
				
				//$user_url_array= explode("/",$user_url);
				
				//$user_id=$user_url_array[4];
				
				if($user_url_anchor_tag = $single_review_div->find('a[class=a-size-base a-link-normal author]')) {
					$message="Getting The User ID";
					l_info($message);
					
					$user_url=$user_url_anchor_tag[0]->href;
				
					$user_url_array= explode("/",$user_url);
				
					$user_id=$user_url_array[4];
					
				} else {
					$message="Error In Getting User ID";
					l_error($message);
				}
				
				//echo "USER ID:".$user_id;
				
				//echo "<br>";
				
				/////have to check this VERIFIED PART script for other URLS// 
				if($verified_user=$single_review_div->find('span[class=a-declarative] span[class=a-size-mini a-color-state a-text-bold]')) {
				
				//$verified=$verified_user[0]->plaintext;
				
				$verified=true;
				
				//echo "Verified USER :".$verified;
				//echo "<br><br>";
				}
				else {
					
					$verified=0;
					//echo "Verified USER :".$verified;
					//echo "<br><br>";
						
				}
				////////////////////////////////////////////////////////////
				
				//$review_rating_icon = $single_review_div->find('a[class=a-link-normal] i');
				//REVIEW RATING
				//$rating = $review_rating_icon[0]->plaintext;
				
				if($review_rating_icon = $single_review_div->find('a[class=a-link-normal] i')) {
					$message="Getting Review Rating";
					l_info($message);
					$rating = $review_rating_icon[0]->plaintext;
					$rating=explode(" ",$rating);
					$rating=$rating[0];
					
				} else {
					$message="Error Getting Review Rating";
					l_error($message);
					
				}
				//echo "REVIEW RATING : ".$rating;
				//echo "<br><br>";
				
				//REVIEW DATE
				
				//$review_date_span=$single_review_div->find('span[class=a-size-base a-color-secondary review-date]');
				//$date=$review_date_span->plaintext;
				//echo "REVIEW DATE: ".$date;
				//print_r($review_date_span);
				/////$date=$review_date_span[0]->plaintext;
				/////$date=format_date($date);
				//echo "DATE :".$date;
				//print_r($date);
				//echo "<br><br>";
				
				if($review_date_span=$single_review_div->find('span[class=a-size-base a-color-secondary review-date]')) {
					$message="Getting Review Date";
					l_info($message);
					$date=$review_date_span[0]->plaintext;
					$date=format_date($date);
					
				} else {
					$message="Error Getting Review Date";
					l_error($message);
					
				}
				//text of the review
				//$review_text=$single_review_div->find('span[class=a-size-base review-text]');
				
				//$text=$review_text[0]->plaintext;
				//remove the br p and li tag with newline for mysql
				//$patterns=array();
				
				
				// $text = preg_replace($patterns,"\n",$review_text[0]);
				//$text = preg_replace($patterns,"\n",$review_text[0]);
				//$text=strip_tags($text);
				
				
				
				if($review_text=$single_review_div->find('span[class=a-size-base review-text]')) {
					$message="Getting The Review Text";
					l_info($message);
					
					$patterns=array();
					$patterns[0]='#<br\s*/?>#i';
					$patterns[1]='#<p\s*/?>#i';
					$patterns[2]='#<li\s*/?>#i';
					// $text = preg_replace($patterns,"\n",$review_text[0]);
					$text = preg_replace($patterns,"\n",$review_text[0]);
					$text=strip_tags($text);
					
				} else {
					$message="Error Getting The Review Text";
					l_error($message);
					
				}
				
				
				//TITLE OF THE REVIEW
				//$review_title=$single_review_div->find('a[class=a-size-base a-link-normal review-title a-color-base a-text-bold]');
				
				//$title=$review_title[0]->plaintext;
				
				if($review_title=$single_review_div->find('a[class=a-size-base a-link-normal review-title a-color-base a-text-bold]')) {
					$message="Getting Review Title";
					l_info($message);
					$title=$review_title[0]->plaintext;
					
				} else {
					$message="Error Getting Review Title";
					l_error($message);
				}
				
				
				//REVIEW VOTES
				if($review_vote=$single_review_div->find('span[class=a-size-small a-color-secondary review-votes]')) {
					$message="Getting Review Votes";
					l_info($message);
					$votes=$review_vote[0]->plaintext;
					$votes=format_votes($votes);
					$helpful_yes=$votes[0];
					$helpful_all=$votes[2];	
				}
				else {
					$helpful_yes=0;
					$helpful_all=0;
				}
				//$review_vote=$single_review_div->find('span[class=a-size-small a-color-secondary review-votes]');
				
				//$votes=$review_vote[0]->plaintext;
				//$votes=format_votes($votes);
				//$helpful_yes=$votes[0];
				//$helpful_all=$votes[2];
				//echo "HELPFUL YES : ".$helpful_yes;
				//echo "<br>";
				//echo "HELPFUL ALL : ".$helpful_all;
				//echo "VOTES :".$votes;
				//echo "<br><br>";
				
				//echo "<hr/>";

				parse_reviewer($user_id,$sleepTime); // Parsing reviewers
				// use mysql_real_escape_string() to prevent from sql injections
				//$id=mysql_real_escape_string($id);
				//$url=mysql_real_escape_string($url);
				//$asin=mysql_real_escape_string($asin);
				//$user_id=mysql_real_escape_string($user_id);
				//$rating=mysql_real_escape_string($rating);
				//$date=mysql_real_escape_string($date);
				$text=mysqli_real_escape_string($db,$text);
				$title=mysqli_real_escape_string($db,$title);			
				record_reviews($id,$url,$asin,$user_id,$rating,$date,$text,$title,$helpful_yes,$helpful_all,$verified);


				 
				
			}
	


}elseif ($total_pages=true) {
		for($i=1;$i<=$total_pages;$i++) {
		
		// generate sleep time
		$sleepTime=rand($minSleep,$maxSleep);
		//sleeps for each iteration
		sleep($sleepTime);
		$url='http://www.amazon.com/product-reviews/'.$asin.'/?sortBy=recent&pageNumber='.$i;
		$html->load_file($url);
		$message="Getting Page : ".$url;
		l_info($message);
		echo "Getting Page : ".$url;
		$all_review_div = $html->find('div[id=cm_cr-review_list] div[class=a-section review]');

			foreach ($all_review_div as $single_review_div) {
				
				//echo $single_review_div->outertext;
				//echo "<br> <br>";
				
				$id=$single_review_div->id;
				//echo "ID: ".$id;
				//echo "<br><br>";
				$url="http://www.amazon.com/gp/customer-reviews/".$id;
				
				//echo "URL :".$url;
				
				//echo "<br>";
				
				///////////////////
				//item ID Should be ASIN Number
				///////////
				
				//$user_url_anchor_tag = $single_review_div->find('a[class=a-size-base a-link-normal author]');
				
				//echo $user_url_anchor_tag[0]->outertext;
				
				//$user_url=$user_url_anchor_tag[0]->href;
				
				//$user_url_array= explode("/",$user_url);
				
				//$user_id=$user_url_array[4];
				
				if($user_url_anchor_tag = $single_review_div->find('a[class=a-size-base a-link-normal author]')) {
					$message="Getting The User ID";
					l_info($message);
					
					$user_url=$user_url_anchor_tag[0]->href;
				
					$user_url_array= explode("/",$user_url);
				
					$user_id=$user_url_array[4];
					
				} else {
					$message="Error In Getting User ID";
					l_error($message);
				}
				
				//echo "USER ID:".$user_id;
				
				//echo "<br>";
				
				/////have to check this VERIFIED PART script for other URLS// 
				if($verified_user=$single_review_div->find('span[class=a-declarative] span[class=a-size-mini a-color-state a-text-bold]')) {
				
				//$verified=$verified_user[0]->plaintext;
				
				$verified=true;
				
				//echo "Verified USER :".$verified;
				//echo "<br><br>";
				}
				else {
					
					$verified=0;
					//echo "Verified USER :".$verified;
					//echo "<br><br>";
						
				}
				////////////////////////////////////////////////////////////
				
				//$review_rating_icon = $single_review_div->find('a[class=a-link-normal] i');
				//REVIEW RATING
				//$rating = $review_rating_icon[0]->plaintext;
				
				if($review_rating_icon = $single_review_div->find('a[class=a-link-normal] i')) {
					$message="Getting Review Rating";
					l_info($message);
					$rating = $review_rating_icon[0]->plaintext;
					$rating=explode(" ",$rating);
					$rating=$rating[0];
					
				} else {
					$message="Error Getting Review Rating";
					l_error($message);
					
				}
				//echo "REVIEW RATING : ".$rating;
				//echo "<br><br>";
				
				//REVIEW DATE
				
				//$review_date_span=$single_review_div->find('span[class=a-size-base a-color-secondary review-date]');
				//$date=$review_date_span->plaintext;
				//echo "REVIEW DATE: ".$date;
				//print_r($review_date_span);
				/////$date=$review_date_span[0]->plaintext;
				/////$date=format_date($date);
				//echo "DATE :".$date;
				//print_r($date);
				//echo "<br><br>";
				
				if($review_date_span=$single_review_div->find('span[class=a-size-base a-color-secondary review-date]')) {
					$message="Getting Review Date";
					l_info($message);
					$date=$review_date_span[0]->plaintext;
					$date=format_date($date);
					
				} else {
					$message="Error Getting Review Date";
					l_error($message);
					
				}
				//text of the review
				//$review_text=$single_review_div->find('span[class=a-size-base review-text]');
				
				//$text=$review_text[0]->plaintext;
				//remove the br p and li tag with newline for mysql
				//$patterns=array();
				
				
				// $text = preg_replace($patterns,"\n",$review_text[0]);
				//$text = preg_replace($patterns,"\n",$review_text[0]);
				//$text=strip_tags($text);
				
				
				
				if($review_text=$single_review_div->find('span[class=a-size-base review-text]')) {
					$message="Getting The Review Text";
					l_info($message);
					
					$patterns=array();
					$patterns[0]='#<br\s*/?>#i';
					$patterns[1]='#<p\s*/?>#i';
					$patterns[2]='#<li\s*/?>#i';
					// $text = preg_replace($patterns,"\n",$review_text[0]);
					$text = preg_replace($patterns,"\n",$review_text[0]);
					$text=strip_tags($text);
					
				} else {
					$message="Error Getting The Review Text";
					l_error($message);
					
				}
				
				
				//TITLE OF THE REVIEW
				//$review_title=$single_review_div->find('a[class=a-size-base a-link-normal review-title a-color-base a-text-bold]');
				
				//$title=$review_title[0]->plaintext;
				
				if($review_title=$single_review_div->find('a[class=a-size-base a-link-normal review-title a-color-base a-text-bold]')) {
					$message="Getting Review Title";
					l_info($message);
					$title=$review_title[0]->plaintext;
					
				} else {
					$message="Error Getting Review Title";
					l_error($message);
				}
				
				
				//REVIEW VOTES
				if($review_vote=$single_review_div->find('span[class=a-size-small a-color-secondary review-votes]')) {
					$message="Getting Review Votes";
					l_info($message);
					$votes=$review_vote[0]->plaintext;
					$votes=format_votes($votes);
					$helpful_yes=$votes[0];
					$helpful_all=$votes[2];	
				}
				else {
					$helpful_yes=0;
					$helpful_all=0;
				}
				//$review_vote=$single_review_div->find('span[class=a-size-small a-color-secondary review-votes]');
				
				//$votes=$review_vote[0]->plaintext;
				//$votes=format_votes($votes);
				//$helpful_yes=$votes[0];
				//$helpful_all=$votes[2];
				//echo "HELPFUL YES : ".$helpful_yes;
				//echo "<br>";
				//echo "HELPFUL ALL : ".$helpful_all;
				//echo "VOTES :".$votes;
				//echo "<br><br>";
				
				//echo "<hr/>";

				parse_reviewer($user_id,$sleepTime); // Parsing reviewers
				// use mysql_real_escape_string() to prevent from sql injections
				//$id=mysql_real_escape_string($id);
				//$url=mysql_real_escape_string($url);
				//$asin=mysql_real_escape_string($asin);
				//$user_id=mysql_real_escape_string($user_id);
				//$rating=mysql_real_escape_string($rating);
				//$date=mysql_real_escape_string($date);
				$text=mysqli_real_escape_string($db,$text);
				$title=mysqli_real_escape_string($db,$title);			
				record_reviews($id,$url,$asin,$user_id,$rating,$date,$text,$title,$helpful_yes,$helpful_all,$verified);


				 
				
			}
	}
}





function format_date($date) {
	
	$date=strtolower($date);
	$date=explode(" ",$date);
	//explode the comma from the day
	$day_array=explode(",",$date[2]);
	$day=$day_array[0];
	$year=$date[3];
	//$month=$date[1];
	switch($date[1]) {
		case "january" :
			$month=1;
			break;
		case "february" :
			$month=2;
			break;
		case "march" :
			$month=3;
			break;
		case "april" :
			$month=4;
			break;
		case "may" :
			$month=5;
			break;
		case "june" :
			$month=6;
			break;
		case "july" :
			$month=7;
			break;
		case "august" :
			$month=8;
			break;
		case "september" :
			$month=9;
			break;
		case "october" :
			$month=10;
			break;
		case "november" :
			$month=11;
			break;
		case "december" :
			$month=12;
			break;			
	}
	$final_date=$year."-".$month."-".$day." "."00:00:00";
	return $final_date;
}
function format_votes($vote) {
	
	$vote_array=explode(" ",$vote);
	return $vote_array;
}

?>