<?php

/**
 *  Gettext-Twig
 *  Gettext support for Twig template system.
 */

/**
 *  Twig token parser for gettext.
 *
 *  @package    gettext_twig
 *  @author     Alfonso Ruzafa <superruzafa@gmail.com>
 *  @version    SVN: $Id$
 */
class GettextTwigTokenParser extends Twig_TokenParser
{
	public function parse(Twig_Token $token)
	{
		$lineno = $token->getLine();

		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, "(");
		$string = $this->parser->getExpressionParser()->parseExpression();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, ")");

		$parameters = array();
		while (!$nodeType = $this->parser->getStream()->test(Twig_Token::BLOCK_END_TYPE))
		{
			$parameters []= $this->parser->getExpressionParser()->parseExpression();
		}
		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

		return new GettextTwigNode($string, $parameters, $lineno, $this->getTag());
	}

	public function getTag()
	{
		return "gettext";
	}
}

/**
 *  Twig token parser for shorter alias of gettext
 */
class _TwigTokenParser extends GettextTwigTokenParser
{
	public function getTag()
	{
		return "_";
	}
}

?>
