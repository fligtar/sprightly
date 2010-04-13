<?php
/*
    This script should be run once per hour to refresh data. This can be done
    via crontab or by loading the page in a browser.
*/

require 'sprightly.php';

$s = new sprightly;
$s->update_data('hourly');

?>

<meta http-equiv="refresh" content="3600">