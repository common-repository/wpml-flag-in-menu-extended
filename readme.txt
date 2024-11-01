=== WPML flag in menu Extended ===
Contributors: jvandemerwe
Credits: Ramon Fincken (for WPML flag in menu plugin) (http://www.ramonfincken.com/)
         Tareq Hasan for the settings plugin (http://tareq.wedevs.com/2012/06/wordpress-settings-api-php-class/)
Tags: wpnav,nav,wp_nav_menu,menu,header,view,wpml,flag,show,language,languages
Requires at least: 2.0.2
Tested up to: 4.3.1
Stable tag: 1.7

Shows flags of translated content in selected menu's

== Description ==

Shows translated flags (for every language except current viewing lang) in any selected menu in the plugin settings
LTR support

In addition to the plugin from Ramon Fincken, this plugin let you select on which menu you want to add the language flags.
You can even make an empty menu and make it dedicated for your language flags and put it as a custom menu in the sidebar.

To make the flags appear on a translated menu, you go in the settings of the plugin and add the translated menu to the selection
you made. This has to be done with every translated menu where you want the flags to appear. So, if you just have translated a
menu. Go back to the options of the widget and you will see that this translated menu is added to the list. Select this menu too and
save the options.

=== additional classes ===

You can also add an additional class for the IMG and LI tag. On the LI tag is automatically added the class "not-last" for all
flag items but the last one, and "last" for the last flag.

Now you can add some styling like:

<code>
/* make a border around the flag icon */
img.wpml_flags {
    border : 1px solid #f1f1f1;
}

/* bring the icons a bit closer together */
li.wpml_flags.not-last {
    margin-right : -20px;
}

/* a little left padding after the flag, when name is used too */
.wpml_flags_name {
    padding-left : 10px;
}
</code>

You can find these styles in the style.css in the css directory of the plugin.
Modify this to your own requirements.

== How to right justify the menu's on a menu?

You can add a wrapper class around the flags and put some CSS styles on it.

In the wrap element you put: div
In the wrap extra classes you put: flag_wrapper.

<code>
#main-nav ul div.flag_wrapper {
    float: right;
}
</code>

In the wrap element you put: span
In the wrap extra classes you put: flag_wrapper.

<code>
#main-nav ul span.flag_wrapper {
    float: right;
    line-height: 14px; /* this could be different */
}
</code>

=== Limitations ===

This plugin will not create flags on the main navigation menu of SOME(!) Genesis Framework (child) Themes !!!

Coding by: <a href="http://www.enovision.net">Johan van de Merwe

== Installation ==

1. Upload directory `wpml-flag-in-menu-extended` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Select menu's where you want the flags to appear in the Settings/WPML Flags in Menu Extended Dashboard option
4. You are done!

=== How to add a menu to a seperate widget area? ===

1. Create a new menu f.e.  "Language Selecter"
2. Add a custom link to this menu with in the label: [wpml_flag_menu]
3. Save the menu
4. Create/Translate the menu in all your languages (to make it exists in all languages)
5. Modify the wmpl_flag_menu settings and select the menus used created/translated
6. Save the settings
7. Add a custom menu widget to the sidebar or any other widget area and select the "Language Selecter" as selected menu
8. Save the settings and look for the result

== Frequently Asked Questions ==

= I have a lot of questions and I want support where can I go? =

<a href="http://www.enovision.net/contact/">http://www.enovision.net/contact/</a> and drop your question.

== Changelog ==

= 1.7 =
* Added the possibility to have wrapper classes around the flag's li elements

= 1.6 =
* Added setting that makes it possible to show also the flag of the active langauge

= 1.5 =
* Solved the issue that the plugin broke down due to using the same class name as in the WPML plugin.
* Split the settings in "General Settings" and "Styling"
* Some code optimisation
* Tested with version 3.3.1 of WPML (thanks to Han Six D. at montemeleto.com)

= 1.4 =
* Solved a bug that in some cases no flags would show in case the menu item that was send in the arguments of the main function was a string instead of an object

= 1.3 =
* Updated the menu link in the admin menu, it didn't show up when WP Better Security plugin is installed
* Corrected the hover on the last menu flag, sometimes it didn't add the 'last' CSS class
* Added height and width for the flag image to the settings of this plugin (default = 16x16)

= 1.2 =
* Added menu in seperate widget area
* Added language name added to the icon
* Additional settings in the admin panel

= 1.1 =
* Minor update

= 1.0 =
* First release

== Screenshots ==

1. Sample of a horizontal menu with flags
2. Sample of a vertical menu with flags in sidebar
3. Settings panel
4. Custom menu installation