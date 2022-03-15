<?php

declare(strict_types=1);

namespace OpsWay\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

use function implode;
use function sprintf;

class RegexpReplace extends FunctionNode
{
    /** @var Node */
    /** @psalm-suppress all */
    private $text;

    /** @var Node */
    /** @psalm-suppress all */
    private $pattern;

    /** @var Node */
    /** @psalm-suppress all */
    private $replacement;

    /** @var Node|null */
    /** @psalm-suppress all */
    private $flags;

    /** @psalm-suppress all */
    public function parse(Parser $parser) : void
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->text = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->pattern = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->replacement = $parser->StringPrimary();
        $this->flags       = null;

        if (Lexer::T_COMMA === $parser->getLexer()->lookahead['type']) {
            $parser->match(Lexer::T_COMMA);
            $this->flags = $parser->StringPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /** @psalm-suppress all */
    public function getSql(SqlWalker $sqlWalker) : string
    {
        $arguments = [
            $this->text->dispatch($sqlWalker),
            $this->pattern->dispatch($sqlWalker),
            $this->replacement->dispatch($sqlWalker),
        ];

        if (null !== $this->flags) {
            $arguments[] = $this->flags->dispatch($sqlWalker);
        }

        return sprintf(
            'regexp_replace(%s)',
            implode(', ', $arguments)
        );
    }
}
