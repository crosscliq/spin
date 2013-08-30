<?php

$f3=require('lib/base.php');

$f3->config('config.ini');

$f3->route('GET|POST /controller/@gameid',
	function($f3) {

	$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
	$game=new DB\Jig\Mapper($db,'game');
	$game->load(array('@game=?','1'));
	
	$response = array('gameon'=>$game->gameon, 'plays'=>$game->plays, 'winner'=> $game->winner, 'won'=> $game->won );
	
	$f3->set('gameon',$game->gameon);
	$f3->set('plays',$game->plays);
	$f3->set('winner',$game->winner);
	$f3->set('won',$game->won);
	$f3->set('title',$game->title);
	
	
	echo View::instance()->render('controller.htm');

	}
);



$f3->route('GET|POST /game/@gameid',
	function($f3) {

	$maxplaysbeforewin = 30;	
	$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
	$game=new DB\Jig\Mapper($db,'game');
	$gameid = $f3->get('PARAMS.gameid');

	$game->load(array('@game=?',$gameid));
	$game->plays++;

	 if($game->gameon == 1) {
	if($game->won == 0) {
		if($game->plays > 1) {
			$chance = rand($game->plays, $maxplaysbeforewin); 
			$win = rand($game->plays, $maxplaysbeforewin); 
			if($chance === $win) {

				$game->winner = 1;
				$game->won = 1;
			}
		} 
	} else {
		$game->winner = 0;
	}
}
	
	$game->save();

	$response = array('gameon'=>$game->gameon, 'plays'=>$game->plays, 'winner'=> $game->winner, 'won'=> $game->won );
	
	echo json_encode($response);

	}
);

$f3->route('GET|POST /results/@game',
	function($f3) {

	$maxplaysbeforewin = 200;	
	$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
	$game=new DB\Jig\Mapper($db,'game');
	$game->load(array('@game=?','1'));
	
	$response = array('gameon'=>$game->gameon, 'plays'=>$game->plays, 'winner'=> $game->winner, 'won'=> $game->won );
	
	echo json_encode($response);

	}
);

$f3->route('GET /launch/@key/@gameid',
	function($f3) {
		$key = $f3->get('PARAMS.key');
	  $gameid = $f3->get('PARAMS.gameid');	
	if($key ==  'A3FS7J') {
			
			//copy the db from offline to online
			copy('db/data/game', 'db/data/offlinegame' );


			$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
			$game=new DB\Jig\Mapper($db,'game');
			$game->load(array('@game=?',$gameid));
			$game->gameon=1;
			$game->plays=0;
			$game->winner=0;
			$game->won=0;
			$game->save();

			echo json_encode($game->cast());
			
		} else {
			echo 'invalid key';
		}
		

	}
);
$f3->route('GET /gameoff/@key/@gameid',
	function($f3) {
		$key = $f3->get('PARAMS.key');
	$gameid = $f3->get('PARAMS.gameid');		
if($key ==  'A3FS7J') {
			$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
			$game=new DB\Jig\Mapper($db,'game');
			$game->load(array('@game=?',$gameid));
			$game->gameon=0;
			$game->plays=0;
			$game->winner=0;
			$game->won=0;
			$game->save();
	
			echo json_encode($game->cast());
		} else {
			echo 'invalid key';
		}
	}
);



$f3->route('GET /mcdonalds',
	function($f3) {
		$track = array();
		foreach ($_SERVER  as $name => $value) {
    $track[$name] = $value;
}

$track['time'] = date("Y-m-d H:i:s");
			
			$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
			$mcdonalds=new DB\Jig\Mapper($db,'mcdonalds');
			$mcdonalds->load(array('@mcdonalds=?',1));
			$mcdonalds->visit++;

			$tracks = $mcdonalds->visits;
			$tracks[] = $track;
			$mcdonalds->visits = $tracks;

			$mcdonalds->save();

		$f3->reroute('http://www.paywithisis.com/');	
	}
);


$f3->route('GET /mcdonalds/stats',
	function($f3) {
		$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
			$mcdonalds=new DB\Jig\Mapper($db,'mcdonalds');
			$mcdonalds->load(array('@mcdonalds=?',1));
			
			$f3->set('visit', $mcdonalds->visit );
			$f3->set('visits', $mcdonalds->visits );

			

		// Render HTML layout
		echo Template::instance()->render('stats.htm');	
	}
);


$f3->run();
