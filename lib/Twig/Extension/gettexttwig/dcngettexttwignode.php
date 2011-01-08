<?php

/**
 *  Gettext-Twig
 *  Gettext support for Twig template system.
 */

/**
 *  Twig node for plural version of gettext
 *  overriding the current domain and category.
 *
 *  @package    gettext_twig
 *  @author     Alfonso Ruzafa <superruzafa@gmail.com>
 *  @version    SVN: $Id$
 */
class DCNGettextTwigNode extends Twig_Node
{
	protected $_domain;
	protected $_msgid1;
	protected $_msgid2;
	protected $_count;
	protected $_category;
	protected $_parameters;

	public function __construct(Twig_Node_Expression $domain,
	                            Twig_Node_Expression $msgid1,
	                            Twig_Node_Expression $msgid2,
	                            Twig_Node_Expression $count,
	                            Twig_Node_Expression $category,
	                            array $parameters,
	                            $lineno)
	{
		parent::__construct($lineno);
		$this->_domain     = $domain;
		$this->_msgid1     = $msgid1;
		$this->_msgid2     = $msgid2;
		$this->_count      = $count;
		$this->_category   = $category;
		$this->_parameters = $parameters;
	}

	public function compile($compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write("echo sprintf(dcngettext(")
			->subcompile($this->_domain)
			->write(", ")
			->subcompile($this->_msgid1)
			->write(", ")
			->subcompile($this->_msgid2)
			->write(", ")
			->subcompile($this->_count)
			->write(", ")
			->subcompile($this->_category)
			->write(")")
		;

		foreach ($this->_parameters as $parameter)
		{
			$compiler
				->write(", ")
				->subcompile($parameter)
			;
		}

		$compiler
			->write(")")
			->raw(";\n")
		;
	}
}

?>
