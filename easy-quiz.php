<?php
/*
  Plugin Name: Easy Quiz
  Plugin URI: http://www.thulasidas.com/plugins/ezquiz
  Description: <em>Lite Version</em>: Easiest Quiz Plugin ever. No Admin page, no options, just a shortcode on a page to create jQuery quiz!
  Version: 1.20
  Author: Manoj Thulasidas
  Author URI: http://www.thulasidas.com
*/

/*
  License: GPL2 or later
  Copyright (C) 2008 www.thulasidas.com
*/

if (class_exists("ezQuiz")) {
  // Another version is probably installed. Ask the user to deactivate it.
  die (__("<strong><em>Easy Quiz:</em></strong> Another version of this plugin is active.<br />Please deactivate it before activating <strong><em>Easy Quiz</em></strong>.", "easy-adsenser"));
}
else {
  class EzQuiz {
    var $plgDir, $plgURL ;
    const shortCode = 'ezquiz' ;
    static $quizPage ;
    function ezQuiz() { //constructor
      $this->plgURL = plugins_url(basename(dirname(__FILE__))) ;
      $this->plgDir = dirname (__FILE__) ;
    }
    static function findShortCode($posts) {
      self::$quizPage = false ;
      if (empty($posts)) return $posts ;
      foreach ($posts as $post) {
        if (stripos($post->post_content, self::shortCode) !== false) {
          self::$quizPage = true ;
          break ;
        }
      }
      return $posts ;
    }
    function ezqStyles() {
      if (!self::$quizPage) return ;
      if (is_admin()) return ;
      wp_register_style('ezQuizCSS', "{$this->plgURL}/jQuizMe.css") ;
      wp_enqueue_style('ezQuizCSS') ;
    }
    function ezqScripts() {
      if (!self::$quizPage) return ;
      if (is_admin()) return ;
      wp_register_script('ezQuizJS', "{$this->plgURL}/jQuizMe.js", array('jquery')) ;
      wp_enqueue_script('ezQuizJS') ;
    }
    function process($content){
      self::$quizPage = true ;
      $lines = explode("\n", strip_tags($content)) ;
      $title = "title: \"Easy Quiz\"," ;
      $help = "help: \"Choose True or False. At the end of the quiz, you will get your score.\"," ;
      $quiz = '<div id="quizArea">
<script type="text/javascript">
jQuery(document).ready(function($) {
$( function($){
var quiz = [' ;
      $comma = '' ;
      foreach ($lines as $line) {
        if (empty($line)) continue ;
        $toOutput = true ;
        @list($label, $rest) = explode(':', $line, 2) ;
        if (strlen($label) > 10) {
          $label = $rest = '' ;
        }
        $label = strtolower(trim($label)) ;
        if (!empty($rest)) {
          switch ($label) {
          case 'q':
          case 'ques' :
          case 'question':
            break ;
          case 'a':
          case 'ans' :
          case 'answer':
            break ;
          case 'title' :
            $title = 'title: "' . $rest . '",' ;
            $toOutput = false ;
            break ;
          case 'help' :
            $help = 'help: "' . $rest . '",' ;
            $toOutput = false ;
            break ;
          default:
          }
        }
        else {
          $rest = $line ;
        }
        if ($toOutput) {
          $quiz .= "$comma{ ques: \"$rest\", ans: \"\" }" ;
          $comma = ',' ;
        }
      }
      $quiz .= '];
var options = {' .
$title .
$help .
'showAns: false,
showAnsInfo: false,
quizType: "tf"
};
var lang = {
quiz : {
tfEqual:""
}
};
$( "#quizArea" ).jQuizMe( quiz, options, lang );
});
})
</script>
</div>' ;
      return $quiz ;
    }
    function displayQuiz($atts, $content='') {
      $quiz = $this->process($content) ;
      return $quiz ;
    }
  }
} //End Class ezQuiz

if (class_exists("ezQuiz")) {
  $ezQuiz = new EzQuiz() ;
  if (isset($ezQuiz)) {
    add_shortcode(EzQuiz::shortCode, array($ezQuiz, 'displayQuiz')) ;
    add_action('wp_enqueue_scripts' , array($ezQuiz, 'ezqStyles')) ;
    add_action('wp_enqueue_scripts' , array($ezQuiz, 'ezqScripts')) ;
    add_filter('the_posts', array("EzQuiz", "findShortCode")) ;
  }
}
