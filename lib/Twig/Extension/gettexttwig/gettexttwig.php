<?php

/**
 *  Gettext-Twig
 *  Gettext support for Twig template system.
 *
 *  Installation:
 *
 *  require_once "/path/to/lib/Twig/Autoloader.php";
 *  Twig_Autoloader::register();
 *  require_once "path/to/gettexttwig.php";
 *  GettextTwig::register();
 *
 *  $loader = new Twig_Loader_Filesystem("path/to/templates");
 *  $twig = new Twig_Environment($loader, array());
 *  $twig->addExtension(new GettextTwig());
 *
 *  Usage in templates:
 *
 *  {% gettext(<string>) [<parameter> {, <parameter>}] %}
 *  {% _(<string>) [<parameter> {, <parameter>}] %} {# the same as above #}
 *  {% ngettext(<msgid1>, <plural form of string>, <count>) [<parameter> {, <parameter>}] %}
 *  {% dgettext(<domain>, <string>) [<parameter> {, <parameter>}] %}
 *  {% dngettext(<domain>, <msgid1>, <msgid2>, <count>) [<parameter> {, <parameter>}] %}
 *  {% dcgettext(<domain>, <string>, <category>) [<parameter> {, <parameter>}] %}
 *  {% dcngettext(<domain>, <msgid1>, <msgid2>, <count>, <category>) [<parameter> {, <parameter>}] %}
 *
 *  Examples:
 *
 *  {% _("Welcome back.") %}
 *  {% gettext("Welcome back again, %s.") user.name %}
 *  {% gettext("My name is %s and I"m %d years old.") user.name user.age %}
 *  {% ngettext("%d user registered so far", "%d users registered so far", users.count) users.count %}
 *  {% dgettext("OtherDomain", "Hello world!") %}
 *  {% dngettext("OtherDomain", "%d user registered so far.", "%d users registered so far.", users.count) users.count %}
 *  {% dcgettext("OtherDomain", "Current day: %s", LC_TIME) day ) %}
 *  {% dcngettext("OtherDomain", "Current day: %s", "Current day: %s", LC_TIME) day %}
 *
 *  Extracting strings from templates:
 *
 *  xgettext is able to parse the template files to extract all strings as a PHP file
 *
 *  $ xgettext <templatefiles> --language=PHP [<other options>]
 */

/**
 *  Twig extension for gettext tags collection.
 *
 *  @package    gettext_twig
 *  @author     Alfonso Ruzafa <superruzafa@gmail.com>
 *  @version    SVN: $Id$
 */
class GettextTwig extends Twig_Extension
{
	public function getTokenParsers()
	{
		return array
		(
			new GettextTwigTokenParser(),
			new _TwigTokenParser(),
			new NGettextTwigTokenParser(),
			new DGettextTwigTokenParser(),
			new DNGettextTwigTokenParser(),
			new DCGettextTwigTokenParser(),
			new DCNGettextTwigTokenParser(),
		);
	}

	public function getName()
	{
		return "gettext";
	}

	/**
	 *  Autoloader for Gettext classes.
	 *  @param $className Name of the class.
	 */
	public static function autoload($className)
	{
		$gettextTwigDir = realpath(dirname(__FILE__));
		$classFilename = $gettextTwigDir . strtolower("/$className.php");
		if (file_exists($classFilename))
		{
			require_once $classFilename;
		}
	}

	/**
	 *  Register custom function for include Gettext files.
	 *  @return true on success.
	 *          false otherwise.
	 */
	public static function register()
	{
		return spl_autoload_register("GettextTwig::autoload");
	}
}

?>
