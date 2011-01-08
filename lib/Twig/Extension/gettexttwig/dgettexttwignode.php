<?php

/**
 *  Gettext-Twig
 *  Gettext support for Twig template system.
 */

/**
 *  Twig node for gettext overriding the current domain.
 *
 *  @package    gettext_twig
 *  @author     Alfonso Ruzafa <superruzafa@gmail.com>
 *  @version    SVN: $Id$
 */
class DGettextTwigNode extends Twig_Node
{
	protected $_domain;
	protected $_string;
	protected $_count;
	protected $_parameters;

	public function __construct(Twig_Node_Expression $domain,
	                            Twig_Node_Expression $string,
	                            array $parameters,
	                            $lineno)
	{
		parent::__construct($lineno);
		$this->_domain     = $domain;
		$this->_string     = $string;
		$this->_parameters = $parameters;
	}

	public function compile($compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write("echo sprintf(dgettext(")
			->subcompile($this->_domain)
			->write(", ")
			->subcompile($this->_string)
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
