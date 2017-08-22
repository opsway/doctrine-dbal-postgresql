<?php

namespace Opsway\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class RegexpReplace extends FunctionNode
{
    private $expr1;
    private $expr2;
    private $expr3;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->expr2 = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->expr3 = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            "regexp_replace(%s, %s, %s)",
            $this->expr1->dispatch($sqlWalker),
            $this->expr2->dispatch($sqlWalker),
            $this->expr3->dispatch($sqlWalker)
        );
    }
}
