<?php

declare(strict_types=1);

namespace Opsway\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

use function sprintf;

/** @psalm-suppress all */
class Extract extends FunctionNode
{
    private mixed $field;
    private mixed $value;

    public function parse(Parser $parser) : void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $parser->match(Lexer::T_IDENTIFIER);
        $this->field = $parser->getLexer()->token['value'];

        $parser->match(Lexer::T_FROM);

        $this->value = $parser->ScalarExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker) : string
    {
        return sprintf(
            'EXTRACT(%s FROM %s)',
            $this->field,
            $this->value->dispatch($sqlWalker)
        );
    }
}
