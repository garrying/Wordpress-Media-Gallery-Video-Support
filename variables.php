<?php

global $mlv_dir, $mlv_base;
$mlv_dir=dirname(plugin_basename(__FILE__)); //plugin absolute server directory name
$mlv_base=get_option('siteurl')."/wp-content/plugins/".$mlv_dir; //URL to plugin directory
$mlv_path=ABSPATH."wp-content/plugins/".$mlv_dir; //absolute server pather to plugin directory

?>