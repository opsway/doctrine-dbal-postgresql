<?php

namespace Opsway\Tests\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Opsway\Doctrine\ORM\Query\AST\Functions\ToTsvector;
use PHPUnit\Framework\TestCase;

class ToTsvectorTest extends TestCase
{
    /**
     * @var ToTsvector
     */
    private $toTsvector;

    public function setUp()
    {
        $this->toTsvector = new ToTsvector('test');
    }

    public function testFunction()
    {
        $parser = $this->prophesize(Parser::class);
        $expr = $this->prophesize(ParenthesisExpression::class);
        $lexer = $this->prophesize(Lexer::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->StringExpression()->shouldBeCalled()->willReturn($expr->reveal());

        $parser->getLexer()->shouldBeCalled()->willReturn($lexer);
        $lexer->isNextToken()->shouldBeCalled()->withArguments([Lexer::T_COMMA])->willReturn(false);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $this->toTsvector->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals('to_tsvector(test)', $this->toTsvector->getSql($sqlWalker->reveal()));
    }

    public function testFunctionWithOptionalConfig()
    {
        $parser = $this->prophesize(Parser::class);
        $config = $this->prophesize(ParenthesisExpression::class);
        $expr = $this->prophesize(ParenthesisExpression::class);
        $lexer = $this->prophesize(Lexer::class);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $config->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn("'english'");
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->StringExpression()->shouldBeCalled()->willReturn($config->reveal(), $expr->reveal());

        $parser->getLexer()->shouldBeCalled()->willReturn($lexer);
        $lexer->isNextToken()->shouldBeCalled()->withArguments([Lexer::T_COMMA])->willReturn(true);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_COMMA]);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);

        $this->toTsvector->parse($parser->reveal());

        $this->assertEquals("to_tsvector('english', test)", $this->toTsvector->getSql($sqlWalker->reveal()));
    }
}
