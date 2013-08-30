<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $debug = isset($_GET["debug"]) ? intval($_GET["debug"]) : 0;

    $format = 'Y-m-d H:i:s';
    $mongo = new Mongo();
    $db = $mongo->selectDB('attcontest');
    $prizecollection = $db->prizecollection;

    //get the prize queue and times for the give-aways
    //they are in order so we only need to get the first prize
    //to see if it's time yet.
    $prize_id = new MongoId("x");//uh, hard-coded i guess
    $filter = array('_id'=>$prize_id);
    $prizes = $prizecollection->findone($filter);
    
    //echo "<pre>".print_r($prizes,1)."</pre>";
    $prize_str = 'not-a';
    $alert = 'false';
    if(count($prizes) > 1 && $debug !== 1)//if there are any prizes left
    {
        //unset($prizes[key($prizes)]);
        next($prizes);//advance past the mongo id
        
        $prize_date_str = key($prizes);
        $prize_str = current($prizes);
        //echo key($prizes)."=>".current($prizes)."<br/>";

    
        $prizedate = DateTime::createFromFormat($format,  $prize_date_str);
        $now = new DateTime("now");
        //echo $prizedate->format($format)." == ".$now->format($format);
        //check if it's time to give away the next prize
        if($now >= $prizedate)//give away next big prize!
        {
            //echo "you won ".$prize_str."!!!";
            //pop the prize out of the queue and update mongo
            $alert = 'true';
            unset($prizes[$prize_date_str]);
            $res = $prizecollection->update($filter, $prizes);
        }
        else
        {
            $prize_str = 'not-a';
        }

    }
    $longnames = array('galaxy' => 'Samsung Galaxy SIII', 'coozie' => 'Drink Coozie', 'not-a' => 'nothing', 'grand' => '$1000 Grand Prize');
    $lname = isset($longnames[$prize_str]) ? $longnames[$prize_str] : 'nothing';
    
    
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>

<title>Tap and Win</title>
<link href="styles/style.css" rel="stylesheet" type="text/css">
<link href="styles/buttons.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jquery/jquery-1.6.2.min.js"></script> 
<script type="text/javascript" src="jquery/modernizr.custom.26584.js"></script> 
<script type="text/javascript" src="includes/jquery.rabidScratchCard.prod.js"></script>
<script type="text/javascript">

var prize = <?php echo $alert; ?>;

		$(function(){
			$("#1").rabidScratchCard({
				
				revealRadius:50,
				percentComplete:85,
				revealOnComplete:true,
				updateOnMouseMove:true,//setting this option is processor intensive. So, set this only if necessary. If set to false, % will be calculated only on muse up of scracthing
				updateOnFingerMove:false,
			    onScratchComplete : function(percentScratched) {
					//you can access the current object by this or this.$elem[0]
      				//Do any thing you want here
      				if(prize === true) alert("You won a <?php echo $lname;?>! To redeem present this to the nearest AT&T representative.");

			    },
				onUpdate:function(percentScratched) {
					$("#percentText1").html("Percent: "+percentScratched)
			    }
		});
		
		});
	</script>
	
   <script type="text/javascript" src="js/libraries/jquery.easing.1.3.js"></script> 
          
         </head>

<body>
<!-- debug: <?php echo $debug; ?>-->
<div class="header">
<div class="logo-container">
	<div class="logo">
	    <a href="/"><img src="images/logo.png" width="100%;" alt="img"></a>
    </div>
</div>
</div>
<br>
<br>



<div id="1" class="scratchCard" style="margin-top:55px; margin-left:auto; margin-right:auto; display: block; width:270px; height:270px; border:solid 5px #32a0d1; border-radius:8px; background-color:#32a0d1; box-shadow: 3px 3px 2px #474747;"  data-backGroundImage="images/<?php echo $prize_str?>-winner.jpg"  data-foreGroundImage="images/reveal.jpg">
</div>
</div>

<br>
<br>


<a href="http://www.facebook.com/ATT?fref=ts" style="background-image:url(images/yes_button.png); width:211px; height:61px; margin-left: auto;   margin-right: auto; margin-bottom: 15px; display:block;"></a>
</div>






<div>



<p class="copyright"style=" position:relative;bottom:0px; margin-left: auto; ">The Game is open to legal residents of the 50 United States including District of Columbia and Puerto Rico who are 18 years of age or older (19 in Nebraska and Alabama and 21 in Mississippi). Ends 5:59pm MT, March 23, 2013.  For complete Game rules, see AT&T Representative. Void where prohibited. <a href="http://www.att.com/gen-mobile/privacy-policy?pid=2506" target="_blank">privacy policy</a></a></p>

</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-39359298-2']);
  _gaq.push(['_setDomainName', 'nf1.cc']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
