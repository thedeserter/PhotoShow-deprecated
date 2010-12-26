<?php 
/*
*  Created by Thibaud Rohmer on 2010-12-23.
*/


if(!isset($_SESSION["logged"])){
	session_start();
	$_SESSION["logged"]=true;
}


/* sort_by_date
* sorts all of the pictures by date added on the server and returns an array
*/
function sort_by_date($groups,$album){ 
	include "settings.php";
	$images=array();
	$folders= glob($dirname.$album."/*");	
	
	foreach($folders as $folder_id => $folder){
		$authorized=array();
		if(is_file($folder."/authorized.txt")){
			$lines=file($folder."/authorized.txt");
			foreach($lines as $line_num => $line)
				$authorized[]=substr($line,0,strlen($line)-1);  // substr is here for taking car of the "\n" 
			
			if(sizeof(array_intersect($groups,$authorized))!=0){	
				$images=array_merge($images, glob($folder."/*"));
			}
		}else{
			$images=array_merge($images, glob($folder."/*"));
		}
	}
	
	array_multisort(array_map('filemtime', $images), SORT_DESC, $images); 
	return $images;
}


/* sort_by_random
* sorts all of the pictures by date added on the server and returns an array
*/
function sort_by_random($groups,$album){ 
	include "settings.php";
	$images=array();
	$folders= glob($dirname.$album."/*");	
	
	foreach($folders as $folder_id => $folder){
		$authorized=array();
		if(is_file($folder."/authorized.txt")){
			$lines=file($folder."/authorized.txt");
			foreach($lines as $line_num => $line)
				$authorized[]=substr($line,0,strlen($line)-1);  // substr is here for taking car of the "\n" 
			
			if(sizeof(array_intersect($groups,$authorized))!=0){	
				$images=array_merge($images, glob($folder."/*"));
			}
		}else{
			$images=array_merge($images, glob($folder."/*"));
		}
	}
	
	shuffle($images);
	return $images;
}

/* display_vignettes
* displays $num thumbnails taken fom the $images array
*/
function display_thumbnails($images,$first,$num){
	include "settings.php";
	
	
	for($i=$first;$i<$first+$num && $i < sizeof($images);$i++)
	{	
		$images[$i] = str_replace("./","",$images[$i]);
				
		if(strpos($images[$i],"/.")===false && !is_dir($images[$i]) && is_file($images[$i]) && substr($images[$i],-3,3) != "txt")	
		{

			if(!is_file($thumbdir.$images[$i]))
			{
					$x=100;
					$y=100;
					$src=$images[$i];
					$dest=$thumbdir.$images[$i];
					$dirs=explode("/",$images[$i]);
					for($sec=0;$sec<sizeof($dirs);$sec++){
						$tempvar=$thumbdir;
						for($sectemp=0;$sectemp<$sec;$sectemp++){
							$tempvar="$tempvar/$dirs[$sectemp]";
						}
						if(!is_dir($tempvar)){
							 mkdir($tempvar);
							 chmod($tempvar,0777);
							}
					}
					
					include "thumb.php";
			}

			echo ('
				<li class="list_item">
				<a title="'.$images[$i].'"> 
				<div class="img_contain"><div class="around_img"><img src="'.$thumbdir.$images[$i].'"/></div></div>
				</a>
				</li>
			');
		}

	}

}

/* array_to_ret
* Returns a string containing the "get" structure of the array, with name $name
*/
function array_to_get($array,$name){
	$ret="";
	for($i=0;$i<sizeof($array);$i++){
		$ret="$ret&$name\[\]=$array[$i]";
	}
	return $ret;
}

/* log_me_in
* Checks the login/pass of a user
*/

function log_me_in($name,$pass){
	define("ME_IZ_GOOD","TRUE");
	include "pass.php";
	define("ME_IZ_GOOD","FALSE");	
	return (in_array($name.":".sha1($pass),$groups));
}
?>