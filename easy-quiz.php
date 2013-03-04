<?php

/*
  Plugin Name: Easy Quiz
  Plugin URI: http://www.thulasidas.com/plugins/ezquiz
  Description: <em>Lite Version</em>: Easiest Quiz Plugin ever. No Admin page, no options, just a shortcode on a page to create jQuery quiz!
  Version: 2.00
  Author: Manoj Thulasidas
  Author URI: http://www.thulasidas.com
 */

/*
  License: GPL2 or later
  Copyright (C) 2008 www.thulasidas.com
 */

if (class_exists("ezQuiz")) {
  // Another version is probably installed. Ask the user to deactivate it.
  die(__("<strong><em>Easy Quiz:</em></strong> Another version of this plugin is active.<br />Please deactivate it before activating <strong><em>Easy Quiz</em></strong>.", "easy-adsenser"));
} else {

  class EzQuiz {

    var $plgDir, $plgURL;

    const shortCode = 'ezquiz';

    static $quizPage;

    function ezQuiz() { //constructor
      $this->plgURL = plugins_url(basename(dirname(__FILE__)));
      $this->plgDir = dirname(__FILE__);
    }

    static function findShortCode($posts) {
      self::$quizPage = false;
      if (empty($posts))
        return $posts;
      foreach ($posts as $post) {
        if (stripos($post->post_content, self::shortCode) !== false) {
          self::$quizPage = true;
          break;
        }
      }
      return $posts;
    }

    function ezqStyles() {
      if (!self::$quizPage)
        return;
      if (is_admin())
        return;
      wp_register_style('ezQuizCSS', "{$this->plgURL}/jQuizMe.css");
      wp_enqueue_style('ezQuizCSS');
    }

    function ezqScripts() {
      if (!self::$quizPage)
        return;
      if (is_admin())
        return;
      wp_register_script('ezQuizJS', "{$this->plgURL}/jQuizMe.js", array('jquery'));
      wp_enqueue_script('ezQuizJS');
    }

    function process($content) {
      self::$quizPage = true;
      $lines = explode("\n", strip_tags($content));
      $lines[] = 'q: last' ; // to write out the last question
      $question = '' ;
      $answer = sprintf('ans: true') ;
      $quizType = 'quizType: "tf"' ;
      $title = "title: \"Easy Quiz\",";
      $help = "help: \"Choose True or False. At the end of the quiz, you will get your score.\",";
      $quiz = '<div id="quizArea">
<script type="text/javascript">
jQuery(document).ready(function($) {
$( function($){
var quiz = [';
      $comma = $prevQuestion = '' ;
      foreach ($lines as $line) {
        if (empty($line))
          continue;
        @list($label, $rest) = explode(':', $line, 2);
        $rest = trim($rest) ;
        if (strlen($label) > 10) {
          $rest = $label;
          $label = 'q' ;
        }
        $label = strtolower(trim($label));
        if (!empty($rest)) {
          switch ($label) {
            case 'q':
            case 'ques' :
            case 'question':
              if (!empty($question)) { // ouput the previous question
                $quiz .= "$comma{ $question $answer }";
                $comma = ',';
                $question = '' ;
                $answer = sprintf('ans: true') ;
              }
              $question = sprintf('ques: "%s",', $rest) ;
              break;
            case 'a':
            case 'ans' :
            case 'answer':
              $answer = sprintf('ans: %s', $rest) ;
              break;
            case 'title' :
              $title = sprintf('title: "%s",', $rest) ;
              break;
            case 'help' :
              $help = sprintf('help: "%s",', $rest) ;
              break;
            case 'type' :
              $quizType = sprintf('quizType: "%s",', $rest) ;
            default:
          }
        }
        else {
          $rest = $line;
        }

      }
      $quiz .= sprintf('];
var options = {
%s
%s
showAns: false,
showAnsInfo: false,
%s
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
</div>', $title, $help, $quizType);
      return $quiz;
    }

    function displayQuiz($atts, $content = '') {
      $quiz = $this->process($content);
      return $quiz;
    }

  }

} //End Class ezQuiz

if (class_exists("ezQuiz")) {
  $ezQuiz = new EzQuiz();
  if (isset($ezQuiz)) {
    add_shortcode(EzQuiz::shortCode, array($ezQuiz, 'displayQuiz'));
    add_action('wp_enqueue_scripts', array($ezQuiz, 'ezqStyles'));
    add_action('wp_enqueue_scripts', array($ezQuiz, 'ezqScripts'));
    add_filter('the_posts', array("EzQuiz", "findShortCode"));
  }
}
