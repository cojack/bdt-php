<?php

/**
 *  Gettext-Twig
 *  Gettext support for Twig template system.
 */

/**
 *  Twig node for plural version of gettext node.
 *
 *  @package    gettext_twig
 *  @author     Alfonso Ruzafa <superruzafa@gmail.com>
 *  @version    SVN: $Id$
 */
class NGettextTwigNode extends Twig_Node
{
	protected $_msgid1;
	protected $_msgid2;
	protected $_count;
	protected $_parameters;

	public function __construct(Twig_Node_Expression $msgid1,
	                            Twig_Node_Expression $msgid2,
	                            Twig_Node_Expression $count,
	                            array $parameters,
	                            $lineno)
	{
		parent::__construct($lineno);
		$this->_msgid1     = $msgid1;
		$this->_msgid2     = $msgid2;
		$this->_count      = $count;
		$this->_parameters = $parameters;
	}

	public function compile($compiler)
	{
		$compiler
			->addDebugInfo($this)
			->write("echo sprintf(ngettext(")
			->subcompile($this->_msgid1)
			->write(", ")
			->subcompile($this->_msgid2)
			->write(", ")
			->subcompile($this->_count)
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
