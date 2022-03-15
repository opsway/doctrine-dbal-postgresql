<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

use function array_map;
use function implode;

class TsConcat extends FunctionNode
{
    /** @var array */
    private $expr = [];

    /** @psalm-suppress all */
    public function parse(Parser $parser) : void
    {
        $lexer = $parser->getLexer();
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expr[] = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->expr[] = $parser->StringPrimary();

        while (Lexer::T_COMMA === $lexer->lookahead['type']) {
            $parser->match(Lexer::T_COMMA);
            $this->expr[] = $parser->StringPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /** @psalm-suppress all */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        return implode(' || ', array_map(
            function ($expr) use ($sqlWalker) {
                return $expr->dispatch($sqlWalker);
            },
            $this->expr
        ));
    }
}
