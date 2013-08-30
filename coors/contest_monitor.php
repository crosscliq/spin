<?php

    $pw = $_GET['password'];
    if($pw !== '37skater37') exit;
    
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $format = 'Y-m-d H:i:s';
    $mongo = new Mongo();
    $db = $mongo->selectDB('attcontest');
    $prizecollection = $db->prizecollection;
    /*
    $prizecollection->drop();
    $prize_array = array(
    '2013-03-21 15:00:00'=>'prize1',
    '2013-03-21 15:15:00'=>'prize2',
    '2013-03-21 15:30:00'=>'prize3',
    '2013-03-21 15:45:00'=>'prize4',
    '2013-03-21 16:00:00'=>'prize5',
    );
    $prize_id = $prizecollection->insert($prize_array);
    echo $prize_id;
    echo "<pre>".print_r($prize_array,1)."</pre>";
    exit;
    */
    //get the prize queue and times for the give-aways
    //they are in order so we only need to get the first prize
    //to see if it's time yet.
    $prize_id = new MongoId("514df7750087818e3200000f");//uh, hard-coded i guess
    $filter = array('_id'=>$prize_id);
    $prizes = $prizecollection->findone($filter);
    
    echo "<pre>".print_r($prizes,1)."</pre>";
    

    
    
?>
