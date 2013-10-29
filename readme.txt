=== IE Check ===
Contributors: josemarques
Tags: browser compatilibity, web standards, Internet Explorer
Tested up to: 3.7
Requires at least: 3.0
Stable tag: trunk
License: GPLv2 or later 

Checks if the browser is an older version of Internet Explorer, releases rage if it's IE<11

== Description ==

Older versions of Internet Explorer are a pain that doesn't go away. Of course everyone tries to design and implement themes that work well on all browsers, but there's always something that doesn't quite work in an older version of Internet Explorer.
What this plugin does is display a message to the user informing that the version of Internet Explorer he/she is using is old. 

There are some options you can change like the text, the URI of the site with browser info and an option to show or hide the dismiss button, in case you really don't want to allow the users see your site unless they have a decent browser. 

There's a CSS file with all the styles you can change.

== Installation ==

1. Install the IE Check plugin on your blog
2. Activate it, 
3. Add the add the following code to your template:  `<?php if (function_exists("ie_check")) { ie_check(); } ?>`  (I recommend adding it to your footer.php)
4. Go to "Settings > IE Check" to configure the plugin
5. Open your blog on an outdate Internet Explorer instalationn (or use developer tools to see the results)
6. Finito!

== Features ==

* Personalizable text
* Personalizable display options
* Choose the recommended site

== Todo ==

* Customizable CSS on the plugin admin

== Screenshots ==
1. Full screen showing warning message
2. Plugin configuration


== Changelog ==

= 0.9.2 =
* Fixed debug flag

= 0.9.1 =
* Minor fixes

= 0.9 =
* Added support up to IE 11

= 0.8.2 =
* Removed bug that was printing the browser agent

= 0.8.1 =
* Upgrade button launches new page

= 0.8.0 =
* First released version