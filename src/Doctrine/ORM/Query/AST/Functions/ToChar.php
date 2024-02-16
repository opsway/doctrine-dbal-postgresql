<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

use function sprintf;

class ToChar extends FunctionNode
{
    /** @var Node */
    /** @psalm-suppress all */
    public $dateExpression;

    /** @var Node */
    /** @psalm-suppress all */
    public $patternExpression;

    public function parse(Parser $parser) : void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->dateExpression = $parser->ArithmeticExpression();
        $parser->match(TokenType::T_COMMA);
        $this->patternExpression = $parser->StringPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    /** @psalm-suppress all */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        return sprintf(
            'TO_CHAR(%s, %s)',
            $this->dateExpression->dispatch($sqlWalker),
            $this->patternExpression->dispatch($sqlWalker)
        );
    }
}
