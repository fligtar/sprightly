<?php
/*
    This script should be run once per minute to refresh data. This can be done
    via crontab or by loading the page in a browser.
*/

require dirname(__FILE__).'/sprightly.php';

$s = new sprightly;
$s->update_data('minutely');

?>

<meta http-equiv="refresh" content="60">