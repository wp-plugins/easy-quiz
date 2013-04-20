<?php
/*
  Plugin Name: Easy Quiz
  Plugin URI: http://www.thulasidas.com/plugins/easy-quiz
  Description: <em>Lite Version</em>: Easiest Quiz Plugin ever. No complicated setup, no server load or submit, just a shortcode on a page to create jQuery quiz!
  Version: 3.11
  Author: Manoj Thulasidas
  Author URI: http://www.thulasidas.com
 */

/*
  License: GPL2 or later
  Copyright (C) 2008 www.thulasidas.com
 */

if (class_exists("EzQuiz")) {
  // Another version is probably installed. Ask the user to deactivate it.
  die(__("<strong><em>Easy Quiz:</em></strong> Another version of this plugin is active.<br />Please deactivate it before activating <strong><em>Easy Quiz</em></strong>.", "easy-adsenser"));
} else {

  class EzQuiz {

    var $plgURL;

    const shortCode = 'ezquiz';

    static $quizPage;

    function EzQuiz() { //constructor
      $this->plgURL = plugins_url(basename(dirname(__FILE__)));
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
              if (!empty($question)) { // output the previous question
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
quizType: "tf"
};
var lang = {
quiz : {
tfEqual:""
}
};
$( "#quizArea" ).jQuizMe( quiz, options, lang );
});
}(jQuery))
</script>
</div>', $title, $help);
      return $quiz;
    }

    function displayQuiz($atts, $content = '') {
      $quiz = $this->process($content);
      return $quiz;
    }
    function printAdminPage() {
      $plgName = 'easy-quiz' ;
      @include(dirname (__FILE__).'/myPlugins.php');
      $ezIsPro = true;

      echo <<<EOF1
<script type="text/javascript" src="{$this->plgURL}/wz_tooltip.js"></script>
<div class="wrap" style="width:810px">
<h2>Easy Quiz Help</h2>
<table>
<tr><td style="width:40%">
<!--  Help Info here -->
<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >
<li>
<a href="#" title="Click for help" onclick="TagToTip('help0',WIDTH, 450, TITLE, 'How to Use it', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
How to use this plugin?
</a>
</li>
<li>
<a href="#" title="Click for help" onclick="TagToTip('help1',WIDTH, 450, TITLE, 'Working Examples', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
Working examples.
</a>
</li>
<li>
<a href="#" title="Click for help" onclick="TagToTip('help2',WIDTH, 450, TITLE, 'Details on Options', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
More detailed explanation of the options.
</a>
</li>
<li>
<a href="#" title="Click for help" onclick="TagToTip('help3',WIDTH, 450, TITLE, 'Types of Quizes', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
What types of questions can I give?
</a>
</li>
<li>
<a href="#" title="Click for help" onclick="TagToTip('help4',WIDTH, 450, TITLE, 'Color Customization', STICKY, 1, CLOSEBTN, true, CLICKCLOSE, true, FIX, [this, 15, 5])">
How can I change the colors?
</a>
</li>
</ul>
</td>
EOF1;
      @include (dirname (__FILE__).'/head-text.php');
      echo <<<EOF2
</tr>
<tr>
<td colspan="3" style="text-align:center;">
<hr />
<p>In the Pro version, this section will have color pickers and a quiz preview to customize your quiz display.<p>
<button onclick="Tip('&lt;img src=&quot;{$this->plgURL}/screenshot-2.png&quot; /&gt;', WIDTH, 810, TITLE, 'Pro Version Screenshot',STICKY, 1, CLOSEBTN, true, FIX, [this, -350, -20])">Show Pro Screenshot</button>
</td></tr>
</table>
<hr />
<div id="help0" style='display:none;'>
You use the plugin with the help of short tags. You create a post or page with a set of statements between the short tags <code>[ezquiz][/ezquiz]</code>. The statements will be neatly rendered as a true or false quiz. Note that all the right answers are, by default, true.
</div>
<div id="help1" style='display:none;'>
Here is a simple example of the quiz (the one that generates the preview quiz on this admin page if you are using the Pro version):
<pre><code>This is a quiz about the wonderful WordPress blogging platform.
[ezquiz]
title:WordPress is free and priceless
WordPress is priceless.
WordPress is free.
[/ezquiz]
If you agree with these statements, you are a good man.</code></pre>
A more complex example (where you specify the answers) follows:
<pre><code>This is a quiz about the wonderful WordPress blogging platform.
[ezquiz]
title:WordPress is free and priceless
help: All things good about WordPress
q: WordPress is priceless.
a: true
q: WordPress is free.
a: true
q: WordPress is worthless.
a: false
[ezquiz]
If you agree with these statements, you are a good man.</code></pre>
</div>
<div id="help2" style='display:none;'>
<p>Each line within the <code>[ezquiz]..[/ezquiz]</code> block contains a label (like <code>title:</code>) and some text. It may be easiest to cut and paste the example above on a test page and see how it is rendered.</p>
<p>The label <code>title:</code> lets you specify a title for your quiz page. If you don't specify it, the title defaults to <em>"Easy Quiz"</em>.</p>
<p>The <code>help:</code> label is a little help text to your readers. Its default value is <em>"Choose True or False. At the end of the quiz, you will get your score."</em></p>
<p>The label <code>q:</code> (or <code>ques:</code> or <code>question:</code>) is optional. It is to specify a question. You could just give statements, which will be rendered as questions.</p>
<p>The answer (with a label <code>a:</code> or <code>ans:</code> or <code>answer:</code>) is optional as well. If you don't give an answer, it is assumed to be <em>true</em>. In other words, the question statement is assumed to be true. The possible values are <em>true</em> or <em>false</em> (in lowercase).</p>
</div>
<div id="help3" style='display:none;'>
<p>In the Pro version, you can change the type of question by giving a label <code>type:</code>. The possible values are:</p>
<ul>
<li><code>flash:</code> (or <code>flashCard:</code>) A basic flash card game. Questions are shown, then answers.
</li>
<li>
<code>fill:</code> (or <code>fillInTheBlank:</code>) Fill in the blank (using <code>&lt;input type=text/&gt;</code> tags).
</li>
<li>
<code>multi:</code> (or <code>multipleChoice:</code>) Multiple choice quiz with drop-down menu (using the <code>&lt;select&gt;&lt;option&gt;</code> tag)s.
</li>
<li>
<code>multiList:</code> (or <code>multipleChoiceOl:</code>) Multiple choice quiz with all answers listed (using the <code>&lt;ol&gt;&lt;li&gt;</code> tags).
</li>
<li>
<code>tf:</code> (or <code>trueOrFalse:</code>) [Default] True or false with radiobuttons (using <code>&lt;input type=radio/&gt;</code> tags).
</li>
</ul>
</div>
<div id="help4" style='display:none;'>
<p>In the Pro version, you can tweak the colors using the color pickers below. You can also see the effect of your color choices right here on the admin screen using a live preview. If you are using the Lite version, please click on the button below to see a screen shot of how it works.</p>
<p>If you prefer to stay with the Lite version, you can change the quiz colors by editing the style file <code>jQuizMe.css</code> in the plugin folder.</p>
</div>
EOF2;
      echo '<div style="background-color:#fcf;padding:5px;border: solid 1px;margin:5px;">' ;
      @include (dirname (__FILE__).'/support.php');
      echo '</div>' ;
      echo '<div style="background-color:#cff;padding:5px;border: solid 1px;margin:5px;padding-bottom:15px;">' ;
      include (dirname (__FILE__).'/why-pro.php');
      echo '</div>' ;
      @include (dirname (__FILE__).'/tail-text.php');
      echo <<<EOF3
<table>
<tr><th scope="row">Credits</th></tr>
<tr><td>
<ul style="padding-left:10px;list-style-type:circle; list-style-position:inside;" >
<li>
<b>Easy Quiz</b> is a WordPress interface to the remarkable jQuery quiz package called  <a href="http://code.google.com/p/jquizme/" title="jQuizMe code page">jQuizMe</a>, which does all the heavy-lifting of rendering the quiz.
</li>
<li>
<b>Easy Quiz</b> uses the excellent Javascript color picker by <a href="http://jscolor.com" target="_blank" title="Javascript color picker"> JScolor</a>.
</li>
<li>
It also uses the excellent Javascript/DHTML tooltips by <a href="http://www.walterzorn.com" target="_blank" title="Javascript, DTML Tooltips"> Walter Zorn</a>.
</li>
</ul>
</td>
</tr>
</table>
</div>
EOF3;
    }
  }
} //End Class EzQuiz

if (class_exists("EzQuiz")) {
  $ezQuiz = new EzQuiz();
  if (isset($ezQuiz)) {
    add_shortcode(EzQuiz::shortCode, array($ezQuiz, 'displayQuiz'));
    add_action('wp_enqueue_scripts', array($ezQuiz, 'ezqStyles'));
    add_action('wp_enqueue_scripts', array($ezQuiz, 'ezqScripts'));
    add_filter('the_posts', array("EzQuiz", "findShortCode"));
    if (is_admin()) {
      function ezQuiz_ap() {
        global $ezQuiz ;
        if (function_exists('add_options_page')) {
          $mName = 'Easy Quiz'  ;
          add_options_page($mName, $mName, 'activate_plugins', basename(__FILE__),
            array($ezQuiz, 'printAdminPage'));
        }
      }
      add_action('admin_menu', 'ezQuiz_ap');
    }
  }
}
