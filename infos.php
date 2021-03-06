<?php 
/*
*  Created by Thibaud Rohmer on 2010-12-25. - Yup. Merry Christmas to you too.
*/
require_once 'settings.php';
require_once 'functions.php';

if(!isset($_SESSION["logged"])){
	session_start();
	$_SESSION["logged"]=true;
}

$file=$_POST["file"];

if(!isset($_POST['addcom'])) $file=$thumbdir.substr($file,0,strrpos($file,".")).".xml";


if(is_file($file)){
	$library = simplexml_load_file($file);
}else{
	$library = new SimpleXMLElement("<library></library>");
}

/** In case the user posted a comment **/
if(isset($_POST["author"])) $author = $_POST["author"];
if(isset($_POST["comm"])) 	$comm = $_POST["comm"];

if(isset($author) && isset($comm)){

	rssupdate(htmlentities($comm),$urlbase,$title,'comm','Comment by '.htmlentities($author));

	$new_comment = $library->addChild('comment');
	$new_comment -> addChild('content',$comm);
	$new_comment -> addChild('author',$author);

	$library->asXML($file);

}

echo '<div class="close"><a href="#">x</a></div>';
echo '<div id="commentsdivcontent"><div id="commentslist">';

$numcomm=0;

/** Let's display those comms **/
foreach ($library->comment as $comment) {
	$cont=stripslashes(trim($comment->content));
	$auth=stripslashes(trim($comment->author));
	echo ("<div class='comment'>
			<div class='comm'>".htmlentities($cont)."</div> 
			<div class='author'>".htmlentities($auth)."</div>
		   </div>"); 
	$numcomm++;
}



echo("
<script type='text/javascript'>

function addcom(plip){
	setup_keyboard();
	var myauthor=$(plip).children('p').children('input[name$=\"author\"]').val();
	var mycomm=$(plip).children('p').children('textarea[name$=\"comm\"]').val();
	if(myauthor.length < 1 || mycomm.length < 1){
		alert('Please fill all fields.');
	}else{
        	$.post('infos.php',{ author:myauthor, comm: mycomm, file: '$file', addcom: 'true' },function(data){
                	$('#commentsdiv .content').html(data);
                	$('#tabcomments').html(data);
        	});
	}
}

$(document).ready(function() {
");
if($numcomm>0){
	echo "$('#comments a').html('<span class=\"comments_num\">COMMENTS ($numcomm)</span>');";
}else{
	echo "$('#comments a').html('COMMENTS');";
}

?>

	$('.validate').unbind();	
	$('.validate').click(function(){
		addcom($(this).parent());
	});
	
	
	$('input').focus(function(){
		remove_keyboard();
	});

	$(".close").click(function(){
		$(this).parent().parent().fadeOut("slow");
	});
});    


</script>
</div>
<div class="addcomm">Add comment :</p>
<form accept-charset="utf-8" >	
	Name :<p><input type="text" name="author"></p>
	Comment :<p><textarea name="comm" cols="30" rows="5"></textarea></p>
	<div class="validate formvalidate">Validate</div>
</form>
</div>
</div>
