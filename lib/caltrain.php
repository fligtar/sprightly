<?php
define('CALTRAIN_LOCAL', 'local');
define('CALTRAIN_BULLET', 'bullet');
define('CALTRAIN_LIMITED', 'limited');
define('CALTRAIN_LOCAL_SATURDAY', 'saturday');

// FML. August 31, 2009 schedule
$caltrain = array(
    'weekdays' => array(
        
        'northbound' => array(
            array('4:49', CALTRAIN_LOCAL),
            array('5:24', CALTRAIN_LOCAL),
            array('5:57', CALTRAIN_BULLET),
            array('6:23', CALTRAIN_LIMITED),
            array('6:37', CALTRAIN_LIMITED),
            array('6:57', CALTRAIN_BULLET),
            array('7:05', CALTRAIN_LIMITED),
            array('7:23', CALTRAIN_LIMITED),
            array('7:37', CALTRAIN_LIMITED),
            array('7:57', CALTRAIN_BULLET),
            array('8:05', CALTRAIN_LIMITED),
            array('8:23', CALTRAIN_LIMITED),
            array('8:37', CALTRAIN_LIMITED),
            array('8:59', CALTRAIN_LIMITED),
            array('9:29', CALTRAIN_LOCAL),
            array('9:59', CALTRAIN_LIMITED),
            array('10:29', CALTRAIN_LOCAL),
            array('11:29', CALTRAIN_LOCAL),
            array('12:29', CALTRAIN_LOCAL),
            array('13:29', CALTRAIN_LOCAL),
            array('14:29', CALTRAIN_LOCAL),
            array('14:59', CALTRAIN_LIMITED),
            array('15:26', CALTRAIN_LOCAL),
            array('16:03', CALTRAIN_LIMITED),
            array('16:37', CALTRAIN_BULLET),
            array('16:58', CALTRAIN_BULLET),
            array('17:03', CALTRAIN_LIMITED),
            array('17:37', CALTRAIN_BULLET),
            array('17:46', CALTRAIN_LIMITED),
            array('17:58', CALTRAIN_BULLET),
            array('18:03', CALTRAIN_LIMITED),
            array('18:37', CALTRAIN_BULLET),
            array('18:46', CALTRAIN_LIMITED),
            array('19:00', CALTRAIN_LIMITED),
            array('19:09', CALTRAIN_LOCAL),
            array('19:49', CALTRAIN_LOCAL),
            array('20:49', CALTRAIN_LOCAL),
            array('21:49', CALTRAIN_LOCAL),
            array('22:49', CALTRAIN_LOCAL)
        ),
        
        'southbound' => array(
            //array('1:09', CALTRAIN_LOCAL),
            array('6:03', CALTRAIN_LOCAL),
            array('6:33', CALTRAIN_LOCAL),
            array('7:07', CALTRAIN_LIMITED),
            array('7:38', CALTRAIN_LIMITED),
            array('7:44', CALTRAIN_BULLET),
            array('7:58', CALTRAIN_BULLET),
            array('8:09', CALTRAIN_LIMITED),
            array('8:38', CALTRAIN_LIMITED),
            array('8:44', CALTRAIN_BULLET),
            array('8:58', CALTRAIN_BULLET),
            array('9:09', CALTRAIN_LIMITED),
            array('9:38', CALTRAIN_LIMITED),
            array('9:44', CALTRAIN_BULLET),
            array('10:15', CALTRAIN_LOCAL),
            array('10:37', CALTRAIN_LIMITED),
            array('11:15', CALTRAIN_LOCAL),
            array('12:15', CALTRAIN_LOCAL),
            array('13:15', CALTRAIN_LOCAL),
            array('14:15', CALTRAIN_LOCAL),
            array('15:15', CALTRAIN_LOCAL),
            array('15:37', CALTRAIN_LIMITED),
            array('16:15', CALTRAIN_LOCAL),
            array('16:37', CALTRAIN_LIMITED),
            array('16:51', CALTRAIN_BULLET),
            array('17:11', CALTRAIN_LIMITED),
            array('17:36', CALTRAIN_LIMITED),
            array('17:50', CALTRAIN_LIMITED),
            array('17:56', CALTRAIN_BULLET),
            array('18:12', CALTRAIN_LIMITED),
            array('18:36', CALTRAIN_LIMITED),
            array('18:50', CALTRAIN_LIMITED),
            array('18:56', CALTRAIN_BULLET),
            array('19:36', CALTRAIN_LIMITED),
            array('19:50', CALTRAIN_LIMITED),
            array('20:38', CALTRAIN_LOCAL),
            array('21:48', CALTRAIN_LOCAL),
            array('22:48', CALTRAIN_LOCAL),
            array('23:48', CALTRAIN_LOCAL)
        )
    ),
    
    'weekends' => array(
        
        'northbound' => array(
            array('7:19', CALTRAIN_LOCAL_SATURDAY),
            array('8:19', CALTRAIN_LOCAL),
            array('9:19', CALTRAIN_LOCAL),
            array('10:19', CALTRAIN_LOCAL),
            array('11:19', CALTRAIN_LOCAL),
            array('12:19', CALTRAIN_LOCAL),
            array('13:19', CALTRAIN_LOCAL),
            array('14:19', CALTRAIN_LOCAL),
            array('15:19', CALTRAIN_LOCAL),
            array('16:19', CALTRAIN_LOCAL),
            array('17:19', CALTRAIN_LOCAL),
            array('18:19', CALTRAIN_LOCAL),
            array('19:19', CALTRAIN_LOCAL),
            array('20:19', CALTRAIN_LOCAL),
            array('21:19', CALTRAIN_LOCAL),
            array('22:49', CALTRAIN_LOCAL_SATURDAY)
        ),
        
        'southbound' => array(
            //array('1:15', CALTRAIN_LOCAL_SATURDAY),
            array('9:29', CALTRAIN_LOCAL),
            array('10:29', CALTRAIN_LOCAL),
            array('11:29', CALTRAIN_LOCAL),
            array('12:29', CALTRAIN_LOCAL),
            array('13:29', CALTRAIN_LOCAL),
            array('14:29', CALTRAIN_LOCAL),
            array('15:29', CALTRAIN_LOCAL),
            array('16:29', CALTRAIN_LOCAL),
            array('17:29', CALTRAIN_LOCAL),
            array('18:29', CALTRAIN_LOCAL),
            array('19:29', CALTRAIN_LOCAL),
            array('20:29', CALTRAIN_LOCAL),
            array('21:29', CALTRAIN_LOCAL),
            array('22:29', CALTRAIN_LOCAL),
            array('23:29', CALTRAIN_LOCAL_SATURDAY)
        )
    )
);


?>