=== Easy Quiz ===
Contributors: manojtd
Donate link: http://buy.thulasidas.com/easy-quiz
Tags: quiz, survey, questions, jquery, jquizme, test, exam, quizzes, education
Requires at least: 3.1
Tested up to: 4.0
Stable tag: 4.20
License: GPLv2 or later

A quick and easy quiz plugin to present a set of questions to your reader and let them check the answers.

== Description ==

*Easy Quiz* is a quick and easy quiz plugin to present a set of questions to your reader and let them check the answers. The answers and the statistics are *not* stored in your database and the purpose of the quiz is purely your reader's entertainment. It creates quizzes vaguely similar to the [BBC Weekly Quiz](http://www.bbc.co.uk/news/magazine-19511527 "BBC Quiz").

All the survey/quiz plugins I found in the directory looked too complex for my purpose. They were good, but were geared toward serious purposes like collecting information, education and tracking etc.  All I wanted to do was to create an interactive page for my readers to take a test for their own entertainment. I didn't want to store their info on my server, give statistical analysis etc. So I wrote this plugin based on the excellent jQuery script called [jQuizMe](http://code.google.com/p/jquizme/ "jQuizMe code page").

= Features =

1. No setup required.
2. Just cut and paste your questions on to your post.
3. Results without server submit -- all the work is done on your readers' machine.
4. View results at any point, and continue the quiz.
5. Detailed help on the admin page.
6. Now available in your own language using machine translation curtsey of Google and Microsoft.

= Pro Version =

A [pro version](http://buy.thulasidas.com/easy-quiz "Pro Version of Eazy Quiz for $2.95") of this plugin is available with the following added features:

1. Color customization to match your theme.
2. More quiz types (Multiple Choice, Fill in The blanks etc.) as short code options
3. Mix and match different types of questions: Multiple Choice, Fill in the Blanks, True or False etc. in the same quiz!
4. Other quiz options as short code options or on the admin page.

== Upgrade Notice ==

Minor fixes. Compatibility with WordPress V4.0.

== Installation ==

You can install it using the WordPress Plugins -> Add New interface.

Or,

1. Upload the Easy Quiz plugin (the whole `easy-quiz` folder) to the '/wp-content/plugins/' directory.
2. Activate the plugin through the 'Plugins' menu in your blog.

To use the plugin, use the shorttags `[ezquiz]`. In other words, enclose your quiz questions between `[ezquiz][/ezquiz]` tags on a page. See the FAQ for an example.

== Screenshots ==

1. Sample quiz.

== Frequently Asked Questions ==

= Why another quiz plugin? =

This plugin is the simplest quiz plugin you can imagine. To use it, you include a set of statements between the shorttags `[ezquiz][/ezquiz]` in a post. The statements will be neatly rendered as a true or false quiz. Note that all the right answers are, by default, true.

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

This creates a quiz with two true-or-false questions. The answers for both questions are "True".

= A more useful example, perhaps? =

Here we go:

`This is a quiz about the wonderful WordPress blogging platform.
[ezquiz]
title:WordPress is free and priceless
help: All things good about WordPress
q: WordPress is priceless.
a: true
q: WordPress is free.
a: true
q: WordPress is worthless.
a: false
[/ezquiz]
If you agree with these statements, you are a good man.`

= A little explanation of the example will help. =

Each line within the `[ezquiz]...[/ezquiz]` block contains a label (like `title:`) and some text. It may be easiest to cut and paste the example above on a test page and see how it is rendered.

The label `title:` lets you specify a title for your quiz page. If you don't specify it, the title defaults to *"Easy Quiz"*.

The `help:` label is a little help text to your readers. Its default value is *"Choose True or False. At the end of the quiz, you will get your score."*

The label `q:` (or `ques:` or `question:`) is optional. It is to specify a question. You could just give statements, which will be rendered as questions.

The answer (with a label `a:` or `ans:` or `answer:`) is optional as well. If you don't give an answer, it is assumed to be *true*. In other words, the question statement is assumed to be true. The possible values are *true* or *false* (in lowercase).

= How do I use other quiz types (Multiple Choice, Fill in The blanks etc.)? =

Please see the FAQ section at the [plugin page](http://www.thulasidas.com/plugins/easy-quiz "Easy Quiz Pro Information Page").

= How do I specify choices in multiple choice quizzes? =

You use the label `c:` as in the example below.

`[ezquiz]
type:multi
title:Some math
help: Fill in
q: 2+1=
a: 3
c: 1
c: 2
c: 4
[/ezquiz]`

= How to mix and match quizzes. =

An example with different types of quizzes mixed and matched at will:

`This is a quiz about the wonderful WordPress blogging platform.
[ezquiz]
type:multi
title:WordPress is free and priceless
help: All things good about WordPress
q: WordPress is priceless.
a: true
q: The whole world says WordPress is worthless.
a: false
c: true
c: may be
c: You must be kidding
q: Manoj, on the other hand, confirms that WordPress is free.
a: true
c: false
c: may be
c: Absolutelytype: tf
q: There is no such thing as a free lunch.
q: Breakfast is a totally different case.
a: false
[/ezquiz]
If you agree with these statements, you are a good man.</code>`

== Change Log ==

* V4.20: Minor fixes. Compatibility with WordPress V4.0. [Sep 8, 2014]
* V4.10: Minor fixes. Compatibility with WordPress V3.9. [May 7, 2014]
* V4.01: Committing files missed in the previous release. [Mar 24, 2014]
* V4.00: Adding a translation interface. Design changes. [Mar 24, 2014]
* V3.50: Compatibility checks for WordPress V3.8. Minor changes on admin page. [Dec 19, 2013]
* V3.40: Compatibility checks for WordPress V3.7. Moving the jQuizMe script to the footer. [Nov 12, 2013]
* V3.30: Now available in your own language using machine translation curtsey of Google and Microsoft. [May 22, 2013]
* V3.20: Porting other types of quizzes (Multiple Choice, Fill in The blanks etc.) to the lite version. [May 10, 2013]
* V3.11: Documentation changes only. [Apr 20, 2013]
* V3.10: Correcting W3C markup validation errors on the admin page. [Apr 14, 2013]
* V3.01: Minor fixes. [Mar 29, 2013]
* V3.00: Major feature: admin page with help info. [Mar 27, 2013]
* V2.02: Minor fix to potential incompatibility. [Mar 18, 2013]
* V2.01: Documentation changes and code cleanup. [Man 6, 2013]
* V2.00: Major improvements. [Mar 5, 2013]
* V1.20: Bug fixes (Fatal error: Call-time pass-by-reference has been removed). [Jan 30, 2013]
* V1.10: Adding more features. [Sep 30, 2012]
* V1.00: Initial release. [Sep 10, 2012]
