<?php

/**
 *  Gettext-Twig
 *  Gettext support for Twig template system.
 */

/**
 *  Twig token parser for gettext overriding the current domain and category.
 *
 *  @package    gettext_twig
 *  @author     Alfonso Ruzafa <superruzafa@gmail.com>
 *  @version    SVN: $Id$
 */
class DCGettextTwigTokenParser extends Twig_TokenParser
{
	public function parse(Twig_Token $token)
	{
		$lineno = $token->getLine();

		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, "(");
		$domain = $this->parser->getExpressionParser()->parseExpression();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, ",");
		$string = $this->parser->getExpressionParser()->parseExpression();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, ",");
		$category = $this->parser->getExpressionParser()->parseExpression();
		$this->parser->getStream()->expect(Twig_Token::OPERATOR_TYPE, ")");

		$parameters = array();
		while (!$nodeType = $this->parser->getStream()->test(Twig_Token::BLOCK_END_TYPE))
		{
			$parameters []= $this->parser->getExpressionParser()->parseExpression();
		}

		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

		return new DCGettextTwigNode($domain, $string, $category, $parameters, $lineno, $this->getTag());
	}

	public function getTag()
	{
		return "dcgettext";
	}
}

?>
