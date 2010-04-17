<?php
/*
    This script should be run once every 5 minutes to refresh data. This can be done
    via crontab or by loading the page in a browser.
*/

require dirname(__FILE__).'/sprightly.php';

$s = new sprightly;
$s->update_data('5minutely');

?>

<meta http-equiv="refresh" content="300">