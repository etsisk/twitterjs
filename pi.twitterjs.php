<?php

/**
 * October 2, 2012
 * Inspired by first party add-on created by Mubashar Iqbal (http://mubs.me)
 */

class Plugin_twitterjs extends Plugin {

  var $meta = array(
    'name'       => 'TwitterJS',
    'version'    => '0.1',
    'author'     => 'Eamon Sisk',
    'author_url' => 'http://www.eamonthomas.com'
  );

  public function tweets()
  {
    $name = $this->fetch_param('name', null); // defaults to no
    $count = $this->fetch_param('count', 10, 'is_numeric'); // defaults to no
    $show_intents = $this->fetch_param('show_intents', true, false, true); // defaults to yes

    if ($show_intents) {
      $show_intents = 1;
    } else {
      $show_intents = 0;
    }

    if ($name) {
      $js = '<div id="'.$name.'_tweets" class="tweets"></div>
        <script type="text/javascript">
          "use strict";
          function displayTweets(data){
            var re = /((http|https|ftp):\/\/[\w?=&.\/-;#~%-]+(?![\w\s?&.\/;#~%"=-]*>))/g;
            var show_intents = '.$show_intents.';
            var innerHTML;
            
            for(var t in data){
              var tweet = data[t].text.replace(re, \'<a target="_blank" href="$1">$1</a> \');
              tweet = tweet.replace(/(^|\s)@(\w+)/g, \'$1<a href="http://www.twitter.com/$2" target="_blank">@$2</a>\');
              tweet = tweet.replace(/(^|\s)#(\w+)/g, \'$1<a href="http://search.twitter.com/search?q=%23$2" target="_blank">#$2</a>\');
              
              var intents = "";
              if (show_intents) {
                intents = intents + \'<ul class="intents">\';
                intents = intents + \'<li><a href="https://twitter.com/intent/tweet?in_reply_to=##ID##" class="reply">Reply</a></li>\';
                intents = intents + \'<li><a href="https://twitter.com/intent/retweet?tweet_id=##ID##" class="retweet">Retweet</a></li>\';
                intents = intents + \'<li><a href="https://twitter.com/intent/favorite?tweet_id=##ID##" class="favorite">Favorite</a></li>\';
                intents = intents + \'</ul>\';
                intents = intents.replace(/##ID##/g, data[t].id_str);
              } 
              var container = document.getElementById("'.$name.'_tweets");
              if(innerHTML === undefined) { container.innerHTML = "<p>" + tweet + "</p>" + intents; innerHTML = container.innerHTML; } else { innerHTML += "<p>" + tweet + "</p>" + intents; container.innerHTML = innerHTML; }
            }
          }
        </script>
        <script type="text/javascript" src="http://api.twitter.com/1/statuses/user_timeline.json?include_rts=1&screen_name='.$name.'&count='.$count.'&callback=displayTweets"></script>';
      return $js;
    }

    return '';
  }
}