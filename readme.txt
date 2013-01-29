=== Easy Quiz ===
Contributors: manojtd
Donate link: http://buy.thulasidas.com/easy-quiz
Tags: quiz, survey, questions, education
Requires at least: 3.1
Tested up to: 3.3
Stable tag: 1.20
License: GPLv2 or later

A quick and easy quiz plugin to present a set of questions to your reader and let them check the answers.

== Description ==

*Easy Quiz* is a quick and easy quiz plugin to present a set of questions to your reader and let them check the answers. The answers and the statistics are *not* stored in your database and the purpose of the quiz is purely your reader's entertainment. It creates quizzes vaguely similar to the [BBC Weekly Quiz](http://www.bbc.co.uk/news/magazine-19511527 "BBC Quiz").

All the survey/quiz plugins I found in the directory looked too complex for my purpose. They were good, but were geared toward serious purposes like collecting information, education and tracking etc.  All I wanted to do was to create an interactive page for my readers to take a test for their own entertainment. I didn't want to store their info on my server, give statistical analysis etc. So I wrote this plugin based on the excellent jQuery script called [jQuizMe](http://code.google.com/p/jquizme/ "jQuizMe code page").

= Features =

1. No setup other than cutting and pasting your questions on your post.
2. True or False type questions.
3. Results without server submit.
4. View results at any point, and continue the quiz.

= Pro Version =

A [pro version](http://buy.thulasidas.com "Pro Version of Eazy Quiz for $2.95") of this plugin is in the works with the following added features:

1. Color customization to match your theme.
2. Per quiz color overrides.
3. More quiz types (multiple choice, fill in the blanks etc.)
4. Other quiz options from the core JS exposed in the admin page and as short code options.

== Upgrade Notice ==

Bug fixes (Fatal error: Call-time pass-by-reference has been removed).

== Installation ==

You can install it using the WordPress Plugins -> Add New interface.

Or,
1. Upload the Easy Quiz plugin (the whole `easy-quiz` folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in your blog.

To use the plugin, use the shorttage `[ezquiz]`. In other words, enclose your quiz questions between `[ezquiz][/ezquiz]` tags on a page. See the FAQ for an example.

== Screenshots ==

1. Sample quiz.

== Frequently Asked Questions ==

= Why another quiz plugin? =

This plugin is the simplest quiz plugin you can imagine. To use it, you include a set of statements between the shorttags `[ezquiz][/ezquiz]` in a post. The statements will be neatly rendered as a true or false quiz. Note that all the right answers are true! It will change in the near future.

= Still not clear how to use it. An example please? =

Create a new post on your test blog with the following content.
`This is a quiz about the wonderful WordPress blogging platform.
[ezquiz]
title:WordPress is free and priceless
WordPress is priceless.
WordPress is free.
[/ezquiz]
If you agree with these statements, you are a good man.`
Publish it and browse to the published page.

== Change Log ==

* V1.20: Bug fixes (Fatal error: Call-time pass-by-reference has been removed). [Jan 30, 2013]
* V1.10: Adding more features. [Sep 30, 2012]
* V1.00: Initial release. [Sep 10, 2012]
