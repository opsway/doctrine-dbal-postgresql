<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

use function sprintf;

class Extract extends FunctionNode
{
    /** @var mixed */
    /** @psalm-suppress all */
    private $field;

    /** @var mixed */
    /** @psalm-suppress all */
    private $value;

    /** @psalm-suppress all */
    public function parse(Parser $parser) : void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);

        $parser->match(TokenType::T_IDENTIFIER);
        $this->field = $parser->getLexer()->token->value;

        $parser->match(TokenType::T_FROM);

        $this->value = $parser->ScalarExpression();

        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }

    /** @psalm-suppress all */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        return sprintf(
            'EXTRACT(%s FROM %s)',
            $this->field,
            $this->value->dispatch($sqlWalker)
        );
    }
}
