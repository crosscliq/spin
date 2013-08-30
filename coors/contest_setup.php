<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $format = 'Y-m-d H:i:s';
    $mongo = new Mongo();
    $db = $mongo->selectDB('attcontest');
    $prizecollection = $db->prizecollection;
    
    $start_time = DateTime::createFromFormat($format, '2013-03-23 14:40:00');
    $end_time = DateTime::createFromFormat($format,  '2013-03-23 19:00:00');
    $prize_distribution = array('galaxy' => 14, 'coozie' => 80, 'grand' => 1);
    $interval = $end_time->getTimestamp() - $start_time->getTimestamp();//get the # of seconds between start and end
    $prize_array_sec = array();
    foreach($prize_distribution as $prize_str => $num_prizes)
    {
        for($i = 0; $i < $num_prizes; $i++)
        {
            $idx = get_rand_wo_collision(array_keys($prize_array_sec), 0, $interval);
            $prize_array_sec[$idx] = $prize_str;
        }
    }
    
    ksort($prize_array_sec);
    $prize_array = array();
    foreach($prize_array_sec as $secs => $prize_str)
    {
        $date = new DateTime();
        $date->setTimestamp($start_time->getTimestamp()+$secs);
        $prize_array[$date->format($format)] = $prize_str;
    }
    //print_r($prize_array);
    

    exit();
    //$prizecollection->drop();

    $prize_id = $prizecollection->insert($prize_array);
    echo $prize_id;
    echo "<pre>".print_r($prize_array,1)."</pre>";


    
    
    
    //helper funcs
    function get_rand_wo_collision($list, $min, $max)
    {
        if(count($list) >= ($max-$min))
        {
            return 0;//termination cond if the array has all possible random #s
        } 
        
        $rand = rand($min, $max);
        if(in_array($rand, $list))//collision, try again
        {
            return get_rand_wo_collision($list, $min, $max);
        }
        else//no collision, return random #
        {
            return $rand;
        }
        
    }
?>
