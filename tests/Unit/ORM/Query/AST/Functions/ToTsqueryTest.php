<?php

namespace Opsway\Tests\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Opsway\Doctrine\ORM\Query\AST\Functions\ToTsquery;
use PHPUnit\Framework\TestCase;

class ToTsqueryTest extends TestCase
{
    private $toTsquery;

    public function setUp()
    {
        $this->toTsquery = new ToTsquery('test');
    }

    public function testFunction()
    {
        $parser = $this->prophesize(Parser::class);
        $expr = $this->prophesize(ParenthesisExpression::class);
        $lexer = $this->prophesize(Lexer::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->StringPrimary()->shouldBeCalled()->willReturn($expr->reveal());

        $parser->getLexer()->shouldBeCalled()->willReturn($lexer);
        $lexer->isNextToken()->shouldBeCalled()->withArguments([Lexer::T_COMMA])->willReturn(false);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $this->toTsquery->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals('to_tsquery(test)', $this->toTsquery->getSql($sqlWalker->reveal()));
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
        $parser->StringPrimary()->shouldBeCalled()->willReturn($config->reveal(), $expr->reveal());

        $parser->getLexer()->shouldBeCalled()->willReturn($lexer);
        $lexer->isNextToken()->shouldBeCalled()->withArguments([Lexer::T_COMMA])->willReturn(true);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_COMMA]);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);

        $this->toTsquery->parse($parser->reveal());

        $this->assertEquals("to_tsquery('english', test)", $this->toTsquery->getSql($sqlWalker->reveal()));
    }
}
