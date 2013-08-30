<html lang="en"><head>
    <meta charset="utf-8">
    <title>Template · Bootstrap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="http://getbootstrap.com/2.3.2/assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
    <link href="http://getbootstrap.com/2.3.2/assets/css/bootstrap-responsive.css" rel="stylesheet">

  </head>

  <body>

    <div class="container-narrow">

      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a>Tag Redirect Stats</a></li>
        </ul>
        <h3 class="muted">McDonalds Redirect Tag</h3>
      </div>

      <hr>

      <div class="jumbotron">
        <h1><?php echo $visit; ?> Redirects </h1>
      
      </div>

      <hr>

      <div class="row-fluid marketing">
        <div class="span12">
         <?php $ctr=0; foreach (($visits?:array()) as $key=>$track): $ctr++; ?>
         <h5><?php echo $track['time']; ?></h5>
         <p><?php echo $track['User-Agent']; ?></p>
        <?php endforeach; ?>
        </div>
      </div>

      <hr>

      <div class="footer">
        <p>©CrossCliq  2013</p>
      </div>

    </div> <!-- /container -->

  

  

</body></html>


