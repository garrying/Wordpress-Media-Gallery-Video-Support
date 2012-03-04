<?php

$label = "Add";
$term_id=0;
$term=null;


if (!current_user_can(mlv::$adminCapability))
	wp_die( __( 'Cheatin&#8217; uh?' ) );


print "
<div class='wrap'>
<h2>$label Video</h2><br />";

global $wpdb;


if($term_id==0)
{

	if ($_POST[mlv_title] && $_GET[mode]!="pca") {
		
		$title = mlv::cleanQuery(($_POST[mlv_title]));
		$embedCode = mlv::cleanQuery(($_POST[mlv_embedCode]));
		if($title&&$title!=''&&$embedCode&&$embedCode!='')
		{
			
			//Script to insert into db
			$date = date("Y-m-d G:i:s");
			$script = "
				INSERT INTO ". $wpdb->prefix . "posts
				(
					`post_author`,
					`post_date`,
					`post_date_gmt`,
					`post_content`,
					`post_title`,
					`post_excerpt`,
					`post_status`,
					`comment_status`,
					`ping_status`,
					`post_password`,
					`post_name`,
					`to_ping`,
					`pinged`,
					`post_modified`,
					`post_modified_gmt`,
					`post_content_filtered`,
					`post_parent`,
					`guid`,
					`menu_order`,
					`post_type`,
					`post_mime_type`,
					`comment_count`
				)
				VALUES
				(
					1	
					,'".$date."'	
					,'".$date."'
					,'$embedCode'	
					,'$title'	
					,''		
					,'inherit'	
					,'open'	
					,'open'	
					,''
					,'".sanitize_title(iz-overtherainbow)."'		
					,''			
					,''			
					,'".$date."'	
					,'".$date."'		
					,''			
					,0	
					,'$embedCode'
					,0	
					,'attachment'	
					,'video/x-flv'	
					,0
				);

			";
			//'$mlv_base/youtube.jpg'	
			//print $script;
			$wpdb->query($script);
	
		
			print "<div class='highlight'>Successful Addition</div><br> <!--meta http-equiv='refresh' content='0'-->"; //header("location:$_SERVER[HTTP_REFERER]");
		
		}
		else
		{
			print "<div class='highlight'>ALL FIELDS ARE REQUIRED!</div><br>";
		
		}
		
	}
	else
	{
		print "<div class='highlight'>All fields are required.</div><br>";

	}
}



	
$base=get_option('siteurl');

print "
<div id='poststuff' class='postbox'>
<h3 class='hndle'>Youtube/Vimeo Links</h3>
<div class='inside'>
<form name='manualAddForm' method=post>
		
			<label for='video_title' style='display:inline-block;width:70px'>Title</label> <input type='text' id='video_title' name='mlv_title' size=40 ><br>
			<label for='video_link' style='display:inline-block;width:70px'>Video URL</label> <input type='text' id='video_link' name='mlv_embedCode' size=40 >
			<br><br>
			<input type='submit' value='$label Video' class='button'> 
			<a href=\"/wp-admin/upload.php\" class=\"button\">Cancel</a>
</form>
</div>
</div>
</div>";
?>


<?php

?>