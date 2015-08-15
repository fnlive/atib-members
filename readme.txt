Plugin ATIB-Members
=== ATIB-Members ===
Contributors: fn64live
Donate link: 
Tags: 
Requires at least: 3.0.1
Tested up to: 4.2.2
Stable tag: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Membership functions for web site "Annika och Torkel i Berg".

== Description ==

Membership functions for web site "Annika och Torkel i Berg".
Restricts access to members content.  
- Adds filter that checks for protected member content: 
	- singular of CPT 'slakt_handelser'
	- archive of CPT 'slakt_handelser'
	- taxonomy of 'slakt-gren' and 'handelse_typ'
	- page 'slakttrad'
- If visitor is not member, return page 'ej_medlem_content.php
- Removes toolbar for logged in users except for administrator
- Registers a Widget Sidebar that can be included in member template pages
- Adds "Släktgren" user profile field
- Adds shortcode to create hyperlink to person in pedigree (släktträd)

Inspired by http://justintadlock.com/archives/2012/10/16/how-i-run-a-membership-site
Requires plugin "Members"
- Assumes that 'Custom Capability' 'visa_medlems_innehall' has been defined and added to roles that are allowed to access "Members content". 
- Also assumes new role 'betalande_medlem' is defined with capability 'visa_medlems_innehall' and 'read'.



== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php do_action('plugin_name_hook'); ?>` in your templates

== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

= What about foo bar? =

Answer to foo bar dilemma.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets 
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png` 
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==



= 0.1 =
* Initial version.

== Arbitrary section ==

You may provide arbitrary sections, in the same format as the ones above.  This may be of use for extremely complicated
plugins where more information needs to be conveyed that doesn't fit into the categories of "description" or
"installation."  Arbitrary sections will be shown below the built-in sections outlined above.

== A brief Markdown Example ==

Ordered list:

1. Some feature
1. Another feature
1. Something else about the plugin

Unordered list:

* something
* something else
* third thing

Here's a link to [WordPress](http://wordpress.org/ "Your favorite software") and one to [Markdown's Syntax Documentation][markdown syntax].
Titles are optional, naturally.

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"

Markdown uses email style notation for blockquotes and I've been told:
> Asterisks for *emphasis*. Double it up  for **strong**.

`<?php code(); // goes in backticks ?>`

