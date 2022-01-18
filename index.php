<?php 
	require_once('configure_mysql.php');
	require_once('facebook.php');

	//vars from http://www.facebook.com/developers/apps.php?app_id=147434471982112&ret=2
	$app_id = '147434471982112';
	$app_secret = '6b8f06a8e677511b80fa33bcec51c1b1';
	$app_url = 'http://apps.facebook.com/important_feed/';
	$app_logo = 'http://rahilpatel.com/facebook/important_feed/exclamation_mark.jpg';
	
	//php sdk
	$facebook = new Facebook(array(
	  'appId'  => $app_id,
	  'secret' => $app_secret,
	  'cookie' => true //enable optional cookie support
	));
	
	session_start();

/*
	$_SESSION['test'] = 42;
	$test = 43;
	echo $_SESSION['test'];
*/
	
	
	//authorization
	print("before authorize application");
	authorize_application($app_id, $app_url);
	$code = $_REQUEST["code"]; //contains a JSON of two strings
	print_r("<br>code: ".$_REQUEST["code"]);
	
	print("before authorize user");
	$access_token_vars = authorize_user($app_id, $app_url, $app_secret, $code);
	//print_r("<br>access token: ".$access_token); //returns access_token=...&expires=...
	

	
	if($_REQUEST['session'])
		$facebook->setSession($_REQUEST['session']);
	else
		$session = $facebook->getSession(); //returns Array ( [uid] => 33608046 [access_token] => 147434471982112|2.Pitg5U57pAhAYugRncyS3g__.3600.1297004400-33608046|S811x8aHl0Z3s4d_SxjeSc8TVUg [expires] => 1297004400 [sig] => 2393d91f42d51cd43470c2e38c1aa99f ), it calls getSignedRequest, validates $_REQUEST['$session'] and in cookie, then builds $session
		
	$access_token = $facebook->getAccessToken(); //returns the value of the token, return $session['access_token']
	//print_r("<br>facebook->getAccessToken: ".$facebook->getAccessToken());
		
	//$access_token = $session['access_token'];
	
	//try storing $session in cookie and check example php again!, read the entirety of facebook.php
	//order of post: click submit -> new page loads -> authorize application runs -> session invalid error
	//not sure where to direct to after post
	

	
	print("<br>session: "); print_r($session);
	
	
	
	
	
	
/*
	if (isset($_POST['hidden_session_value'])) {
	    $hsessionvalue = $_POST['hidden_session_value'];    
	    //create your $session array from what was provided.
	    parse_str($hidden_session_value, $session);
	    //set the session object in your $facebook class based on the old session value provided
	    //by your Form's post.
	    $facebook->setSession($session,true);
	}
*/
	
	print('<br>test0');
	//check if get/post is set
	if(isset($_POST['post_message'])) {
	print('<br>test1');
		//post using graph api in php
	    $result = $facebook->api('/me/feed/', 'post', array(
	    	'access_token' => $access_token,
	    	'message' => $_POST["post_message"],
	    	'actions' => '{"name": "Post something important", "link": "$app_url"}'
		));
	print('<br>test2');	
		//get post ID of the last message
	    $graph_url = "https://graph.facebook.com/me/feed?access_token='$access_token'"; //wall
	    $object = json_decode(file_get_contents($graph_url));
	    $post_id = $object->data[1]->id;
	    $user_id = $object->data[1]->from->id;
	print('<br>test3');
		//insert post id and user id into the table
		mysql_query("INSERT INTO posts (id, user_id) VALUES ('$post_id', '$user_id')") or die (mysql_error());
	print('<br>test4');
	}
	print('<br>test5');
		
	?>
	<h3>Important Feed</h3>
	<?php
	//show posts of friends of the current user
	
	//method 1: store posts in a personal database, then check if the post is a friend of the current user, show post
	//store post id and user id when a new post is inserted
	//store the user's friend's user ids in an array
	//query the personal database using the friend's user id array
	
	//method 2: request the profile feed of every friend of th current user, then check if post attribution property == important feed
	//look into facebook query (fqi?)
	
	//later ideas: set up javascript events to add new posts
	
	
	$hidden_session_value = http_build_query($session, '', '&amp;');
	?>
		
	<h3>Post</h3>
	<form method="post" action="<?php print("http://apps.facebook.com/important_feed/?code=".urlencode($code)); //$_SERVER["PHP_SELF"] index.php?code={$code} ?>" 
		<textarea id="post_message" name="post_message" cols=40 rows=8 placeholder="just a killed a man"></textarea><br>
	 	<input type="hidden" name="session" value="'.$session.'"/>
		<input type="submit" value="post to your wall">
	</form>
	
	
	

		
		
		
	<!--testing-->
<?php

/*
	//dialog form
	$feed_url = "http://www.facebook.com/dialog/feed
	?app_id=".$app_id.
	"&redirect_uri=".urlencode($app_url).
	"&message="."test";
	
	if (empty($_REQUEST["post_id"])) {
	echo("<script> top.location.href='" . $feed_url . "'</script>");
	}
	else {
	echo ("Feed Post Id: " . $_REQUEST["post_id"]);
	}
*/

	
	
	//$session = $facebook->getSession();
	
	//echo("<br><br>testing:<br>");
	
    //print "<br>me: ".$facebook->api('/me'); //user object
	
	
	/*
	//publish stream via php sdk automatically, without a dialog/POSTing
	$parameters2 = array(
		'access_token' => $signed_request_token,
		'message' => 'Did a Test Post :',
		'name' => "This is the title of my post",
		'link' => "http://blogs.canalplan.org.uk/steve/2010/04/28/hitting-a-moving-target/",
		'description' => "this is the body of the post with lots of wiffly woffly text in it, lets see if this all works ok!",
		'picture'=>"http://blogs.canalplan.org.uk/steve/files/2009/12/13742_1291940983817_1389037839_836473_2130235_n.jpg"
	);
	
	$response = $facebook->api('/me/feed', 'POST', $parameters2);
	print(json_decode($response));
	print('test2');
	*/

	
	/* from readme.md
	Logged in vs Logged out:

    if ($facebook->getSession()) {
      echo '<a href="' . $facebook->getLogoutUrl() . '">Logout</a>';
    } else {
      echo '<a href="' . $facebook->getLoginUrl() . '">Login</a>';
    }
    */
    
    
    /*
    $session = $facebook->getSession();

	$me = null;
	// Session based API call.
	if ($session) {
	  try {
	    $uid = $facebook->getUser();
	    $me = $facebook->api('/me');
	  } catch (FacebookApiException $e) {
	    error_log($e);
	  }
	}
    ?>
    
    
    
    <h3>Session</h3>
    <?php if ($me): ?>
    <pre><?php print_r($session); ?></pre>

    <h3>You</h3>
    <img src="https://graph.facebook.com/<?php echo $uid; ?>/picture">
    <?php echo $me['name']; ?>

    <h3>Your User Object</h3>
    <pre><?php print_r($me); ?></pre>
    <?php else: ?>
    <strong><em>You are not Connected.</em></strong>
    <?php endif ?>

    <h3>Naitik</h3>
    <img src="https://graph.facebook.com/naitik/picture">
    <?php echo $naitik['name']; ?>
    */
    
    
	function authorize_application($app_id, $app_url) {
		//authorization using oauth 2
		
		//$signed_request_token = $facebook->getSignedRequest();
		//print_r("signed token:".$signed_request_token);
		
		if (empty($_REQUEST["code"])) {
	        $auth_url = "http://www.facebook.com/dialog/oauth"
		        ."?client_id=".$app_id
		        ."&redirect_uri=".urlencode($app_url)
		        ."&scope=publish_stream,read_stream"; //permissions
		        
		    echo("<script> top.location.href='".$auth_url."'</script>");
		    exit();
		}
	}
	
	function authorize_user($app_id, $app_url, $app_secret, $code) {
		$token_url = "https://graph.facebook.com/oauth/access_token"
			."?client_id=".$app_id
			."&redirect_uri=".urlencode($app_url)
			."&client_secret=".$app_secret
			."&code=".$code;
		
		return file_get_contents($token_url); //returns access_token
	}
     
?>