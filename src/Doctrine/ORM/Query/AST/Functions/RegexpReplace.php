<?php

namespace Opsway\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class RegexpReplace extends FunctionNode
{
    /**
     * @var Node
     */
    private $text;

    /**
     * @var Node
     */
    private $pattern;

    /**
     * @var Node
     */
    private $replacement;

    /**
     * @var Node|null
     */
    private $flags;

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->text = $parser->StringPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->pattern = $parser->StringPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->replacement = $parser->StringPrimary();

        $this->flags = null;
        if (Lexer::T_COMMA === $parser->getLexer()->lookahead['type']) {
            $parser->match(Lexer::T_COMMA);
            $this->flags = $parser->StringPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(SqlWalker $sqlWalker)
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
            join(', ', $arguments)
        );
    }
}
