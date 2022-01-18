<!--javascript sdk-->
<div id="fb-root"></div>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<script>
	FB.init({
	appId  : '147434471982112', //? not safe
	status : true, // check login status
	cookie : true, // enable cookies to allow the server to access the session
	xfbml  : true  // parse XFBML
	});
  
  	//using the javascript sdk for FB.ui for stream.publish dialog
	function publishPost() {
	  FB.ui({
	    method: 'stream.publish',
	    attachment: {
			//name:,
			//caption:,
		    media: [{
		        type: 'image',
		        href: 'http://apps.facebook.com/important_feed/',
		        src: 'http://rahilpatel.com/facebook/important_feed/exclamation_mark.jpg'
		    }]
	    },
	    action_links: [{
	      text: 'Post something important',
	      href: 'http://apps.facebook.com/important_feed/'
	    }],
	    user_message_prompt: "What's so important"

	  },
		function(response) {
		    if (response && response.post_id) {
		      alert('Post was published');
		      window.location = "http://apps.facebook.com/important_feed/?post_id="+response.post_id; //to send post_id to php
		    }
		    else 
		      alert('Post was not published.');
		}
	  );
	}
</script>

	<button onclick="publishPost()">Post</button>
	
	
	
	
echo 'Lorem ' . $var; // Results in 'Lorem Ipsum'
echo "Lorem $var"; // Results in 'Loren Ipsum'


$text = 'Lorem Ipsum';
$uri = 'http://www.lipsum.com';

echo '<a href="' . $uri . '">' . $text . '</a>';
echo "<a href=\"$uri\">$text</a>";

"Hi there {$user->name}, you last visited at {$data['visit']} and your favourite colour is {$user->favcolour}.";

echo "<a href='$uri'>$text</a>";


curl -F 'access_token=147434471982112|2.neExe_ANH6MaQ5_y1V25lw__.3600.1296968400-33608046|QKJXfGrQcB2r-HkAFZVY3RSS8dk' \
     -F 'message=I'm posting this message via cURL in terminal' \
     https://graph.facebook.com/me/feed