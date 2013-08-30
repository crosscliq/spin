<?php

$f3=require('lib/base.php');

$f3->config('config.ini');

$f3->route('GET|POST /nfcforum/app/game',
	function($f3) {

	$maxplaysbeforewin = 100;	
	$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
	$game=new DB\Jig\Mapper($db,'game');
	$game->load(array('@game=?','1'));
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

$f3->route('GET|POST /nfcforum/app/results',
	function($f3) {

	$maxplaysbeforewin = 200;	
	$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
	$game=new DB\Jig\Mapper($db,'game');
	$game->load(array('@game=?','1'));
	
	$response = array('gameon'=>$game->gameon, 'plays'=>$game->plays, 'winner'=> $game->winner, 'won'=> $game->won );
	
	echo json_encode($response);

	}
);


$f3->route('GET /nfcforum/app/launch/@key',
	function($f3) {
		$key = $f3->get('PARAMS.key');
		if($key ==  'A3FS7J') {
			
			//copy the db from offline to online
			copy('db/data/game', 'db/data/offlinegame' );


			$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
			$game=new DB\Jig\Mapper($db,'game');
			$game->load(array('@game=?','1'));
			$game->gameon=1;
			$game->plays=0;
			$game->winner=0;
			$game->won=0;
			$game->save();

			echo 'Game is ready';
		} else {
			echo 'invalid key';
		}
		

	}
);
$f3->route('GET /nfcforum/app/gameoff/@key',
	function($f3) {
		$key = $f3->get('PARAMS.key');
		if($key ==  'A3FS7J') {
			$db=new DB\Jig('db/data/',DB\Jig::FORMAT_JSON);
			$game=new DB\Jig\Mapper($db,'game');
			$game->load(array('@game=?','1'));
			$game->gameon=0;
			$game->plays=0;
			$game->winner=0;
			$game->won=0;
			$game->save();
	
			echo 'Game is off';
		} else {
			echo 'invalid key';
		}
	}
);

$f3->run();
