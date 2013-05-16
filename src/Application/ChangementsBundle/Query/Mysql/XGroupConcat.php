<?php

namespace Application\ChangementsBundle\Query\Mysql;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projet
 *
 * @ORM\Table(name="changement_status")
 * @ORM\Entity
 */

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer;

// limited support for GROUP_CONCAT
class XGroupConcat extends FunctionNode
{
    public $isDistinct = false;
    public $expression = null;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'GROUP_CONCAT(' .
            ($this->isDistinct ? 'DISTINCT ' : '') .
            $this->expression->dispatch($sqlWalker) .
        ')';
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        
        $lexer = $parser->getLexer();
        if ($lexer->isNextToken(Lexer::T_DISTINCT)) {
            $parser->match(Lexer::T_DISTINCT);
            
            $this->isDistinct = true;
        }

        $this->expression = $parser->SingleValuedPathExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

}