<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\InputParameter;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

use function sprintf;

/** @psalm-suppress all */
class ArrayReplace extends FunctionNode
{
    /** @var Node */
    /** @psalm-suppress all */
    private $expr1;

    /** @var InputParameter */
    /** @psalm-suppress all */
    private $expr2;

    /** @var InputParameter */
    /** @psalm-suppress all */
    private $expr3;

    public function parse(Parser $parser) : void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->expr1 = $parser->StringPrimary();
        $parser->match(TokenType::T_COMMA);
        $this->expr2 = $parser->InputParameter();
        $parser->match(TokenType::T_COMMA);
        $this->expr3 = $parser->InputParameter();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    /** @psalm-suppress all */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        return sprintf(
            'array_replace(%s, %s, %s)',
            $this->expr1->dispatch($sqlWalker),
            $sqlWalker->walkInputParameter($this->expr2),
            $sqlWalker->walkInputParameter($this->expr3)
        );
    }
}
