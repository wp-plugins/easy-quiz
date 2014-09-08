<?php

/*
  Plugin Name: Easy Quiz
  Plugin URI: http://www.thulasidas.com/plugins/easy-quiz
  Description: <em>Lite Version</em>: Easiest Quiz Plugin ever. No complicated setup, no server load or submit, just a shortcode on a page to create jQuery quiz!
  Version: 4.20
  Author: Manoj Thulasidas
  Author URI: http://www.thulasidas.com
 */

/*
  Copyright (C) 2008 www.ads-ez.com

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (class_exists("EzQuiz")) {
  $plg = "Easy Quiz Lite";
  $lite = plugin_basename(__FILE__);
  include_once('ezDenyLite.php');
  ezDenyLite($plg, $lite);
}
else {

  class EzQuestion {

    var $type, $ques, $ans, $ansSel;

    function EzQuestion($type) {
      $this->type = $type;
    }

    function sanitize() {
      if (empty($this->ansSel)) {
        $this->type = 'tf';
      }
      if ($this->type == 'tf') {
        $this->ans = trim($this->ans, "'");
        if (empty($this->ans)) {
          $this->ans = 'true';
        }
        $this->ansSel = array();
      }
    }

    function render() {
      $this->sanitize();
      $question = sprintf("{ ques: '%s',", $this->ques);
      if ($this->type == "tf") {
        $question .= sprintf("\nans: %s", $this->ans);
      }
      else {
        $question .= sprintf("\nans: %s", $this->ans);
        if (!empty($this->ansSel)) {
          $question .= sprintf(",\nansSel: [");
          foreach ($this->ansSel as $a) {
            $question .= sprintf(" '%s', ", $a);
          }
          $question = rtrim($question, ', ') . ' ]';
        }
      }
      $question .= " },\n";
      return $question;
    }

  }

  class EzQuiz {

    var $title, $help, $showAns, $showAnsInfo, $allRandom, $questions, $credit;
    var $options, $optionName;
    var $slug, $domain, $plgDir, $plgURL, $ezTran, $ezAdmin, $myPlugins;
    private $adminMsg = '';

    const shortCode = 'ezquiz';

    static $quizPage;

    function EzQuiz() { //constructor
      $this->plgDir = dirname(__FILE__);
      $this->plgURL = plugin_dir_url(__FILE__);
      $this->title = "Easy Quiz";
      $this->help = "Choose True or False, or enter an answer. After you attempt a qustion, you can check your answer and proceed. At the end of the quiz, you will get your score.";
      $this->showAns = 'false';
      $this->showAnsInfo = 'false';
      $this->allRandom = 'false';
      $this->optionName = "ezQuiz";
      $this->options = get_option($this->optionName);
      if (empty($this->options)) {
        $this->options = $this->mkDefaultOptions();
      }
      $this->credit = "<a href='http://buy.thulasidas.com/easy-quiz' target='_blank'>Easy Quiz</a> by <a href='http://www.Thulasidas.com/' target='_blank' title='Unreal Blog proudly brings you Easy AdSense'>Unreal</a>";
      if (is_admin()) {
        require_once($this->plgDir . '/EzTran.php');
        $this->domain = $this->slug = 'easy-quiz';
        $this->ezTran = new EzTran(__FILE__, "Easy Quiz", $this->domain);
        $this->ezTran->setLang();
      }
    }

    static function findShortCode($posts) {
      self::$quizPage = false;
      if (empty($posts)) {
        return $posts;
      }
      foreach ($posts as $post) {
        if (stripos($post->post_content, self::shortCode) !== false) {
          self::$quizPage = true;
          break;
        }
      }
      return $posts;
    }

    function ezqStyles() {
      if (!self::$quizPage) {
        return;
      }
      if (is_admin()) {
        return;
      }
      wp_register_style('ezQuizCSS', "{$this->plgURL}/jQuizMe.css");
      wp_enqueue_style('ezQuizCSS');
    }

    function ezqScripts() {
      if (!self::$quizPage) {
        return;
      }
      if (is_admin()) {
        return;
      }
      wp_register_script('ezQuizJS', "{$this->plgURL}/jQuizMe.js", array('jquery'), false, true);
      wp_enqueue_script('ezQuizJS');
    }

    function render() {
      $types = array();
      $quiz = sprintf("<div id='quizArea'>
<script type='text/javascript'>
jQuery(document).ready(function($) {
$( function($){
var quiz = {\n");
      foreach ($this->questions as $q) {
        $q->sanitize();
        $types[$q->type][] = $q;
      }
      foreach ($types as $type => $questions) {
        $quiz .= sprintf("%s:\n[", $type);
        foreach ($questions as $q) {
          $quiz .= $q->render();
        }
        $quiz = rtrim($quiz, ",\n");
        $quiz .= "],\n";
      }
      $quiz = rtrim($quiz, ",\n");
      $quiz .= "\n};
var options = {
title: '{$this->title}',
help: '{$this->help}',
showAns: {$this->showAns},
showAnsInfo: {$this->showAns},
allRandom: {$this->allRandom},
random: {$this->allRandom}
};
var lang = {
quiz : { tfEqual:'' }
};
$( '#quizArea' ).jQuizMe( quiz, options, lang );
});
}(jQuery))
</script>
</div>
";
      if ($this->options['showCredit']) {
        $quiz .= "<div style='text-align:center;font-size:x-small;'>{$this->credit}</div>\n";
      }
      return $quiz;
    }

    function process($content, $type) {
      self::$quizPage = true;
      $lines = explode("\n", strip_tags($content));
      foreach ($lines as $line) {
        if (empty($line)) {
          continue;
        }
        @list($label, $rest) = explode(':', $line, 2);
        $rest = trim($rest);
        if (strlen($label) > 10) {
          $rest = $label;
          $label = 'q';
        }
        $label = strtolower(trim($label));
        if (!empty($rest)) {
          switch ($label) {
            case 'q':
            case 'ques' :
            case 'question':
              $q = new EzQuestion($type);
              $q->ques = $rest;
              $this->questions[] = $q;
              break;
            case 'a':
            case 'ans' :
            case 'answer':
              $q->ans = $rest;
              break;
            case 'c':
            case 'choice' :
            case 'o':
            case 'option':
              $q->ansSel[] = $rest;
              break;
            case 'title' :
              $this->title = $rest;
              break;
            case 'help' :
              $this->help = $rest;
              break;
            case 'type' :
              $type = $rest;
              if ($type == 'multi' || $type == 'multipleChoice') {
                $type = 'multiList';
              }
              break;
          }
        }
        else {
          $rest = $line;
        }
      }
      $quiz = $this->render();
      return $quiz;
    }

    function displayQuiz($atts, $content = '') {
      extract(shortcode_atts(array("type" => "tf"), $atts));
      $quiz = $this->process($content, $type);
      return $quiz;
    }

    function mkDefaultOptions() {
      $options = array();
      $options['showCredit'] = false;
      return $options;
    }

    function handleSubmits() {
      if (empty($_POST)) {
        return;
      }
      if (!empty($_POST['update_ezQuiz'])) {
        $this->adminMsg = '<div class="updated"><p><strong>Options saved.</strong></p> </div>';
        $this->options['showCredit'] = isset($_POST['showCredit']);
        update_option($this->optionName, $this->options);
      }
    }

    function printAdminPage() {
// if translating, print translation interface
      if ($this->ezTran->printAdminPage()) {
        return;
      }
      require($this->plgDir . '/myPlugins.php');
      $slug = $this->slug;
      $plgURL = $this->plgURL;
      $plg = $this->myPlugins[$slug];
      require_once($this->plgDir . '/EzAdmin.php');
      $this->ezAdmin = new EzAdmin($plg, $slug, $plgURL);
      $this->ezAdmin->domain = $this->domain;
      $ez = $this->ezAdmin;

      $this->handleSubmits();
      echo $this->adminMsg;
      if ($this->options['showCredit']) {
        $showCredit = "checked='checked'";
      }
      else {
        $showCredit = "";
      }

      echo <<<EOF1
<script type="text/javascript" src="{$this->plgURL}/wz_tooltip.js"></script>
<div class="wrap" style="width:850px">
<h2>Easy Quiz Help</h2>
<form method="post" action=''>
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
      include ($this->plgDir . '/head-text.php');
      echo <<<EOF2
</tr>
<tr>
<td colspan="3" style="text-align:center;">
<hr />
<p>In the Pro version, this section will have color pickers and a quiz preview to customize your quiz display.<p>
<a href='#' onclick="Tip('&lt;img src=&quot;{$this->plgURL}/screenshot-2.png&quot; /&gt;', WIDTH, 810, TITLE, 'Pro Version Screenshot',STICKY, 1, CLOSEBTN, true, FIX, [this, -350, -20])">Show Pro Screenshot</a>
</td></tr>
<tr><td colspan="3">
<label for="showCredit" style="color:#e00;"><input type="checkbox" id="showCredit"  name="showCredit" $showCredit /> &nbsp; Show a tiny credit link at the bottom of the quiz. (Please consider showing it if you would like to support this plugin. Thanks!)</label>
</td></tr>
</table>
<div class="submit">
<input type="submit" name="update_ezQuiz" value="Save Changes" title="Save the changes as specified above" onmouseover="Tip('Save the changes as specified above',WIDTH, 240, TITLE, 'Save Changes')" onmouseout="UnTip()"/>
</div>
</form>
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
//      echo "<form method='post'>";
//      $this->ezTran->renderTranslator();
//      echo "</form><br />";
      $ez->renderSupport();
      $ez->renderWhyPro();
      include ($this->plgDir . '/tail-text.php');
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

      if (!function_exists('ezQuiz_ap')) {

        function ezQuiz_ap() {
          global $ezQuiz;
          $mName = 'Easy Quiz';
          add_options_page($mName, $mName, 'activate_plugins', basename(__FILE__), array($ezQuiz, 'printAdminPage'));
        }

      }

      add_action('admin_menu', 'ezQuiz_ap');
    }
  }
}
