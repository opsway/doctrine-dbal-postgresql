<?php

namespace Opsway\Tests\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Opsway\Doctrine\ORM\Query\AST\Functions\ArrayReplace;
use PHPUnit\Framework\TestCase;

class ArrayReplaceTest extends TestCase
{
    private $arrayReplace;

    public function setUp()
    {
        $this->arrayReplace = new ArrayReplace('test');
    }

    public function testFunction()
    {
        $parser = $this->prophesize(Parser::class);
        $expr = $this->prophesize(ParenthesisExpression::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->StringPrimary()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_COMMA]);
        $parser->InputParameter()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $this->arrayReplace->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');
        $sqlWalker->walkInputParameter()->shouldBeCalled()->withArguments([$expr->reveal()])->willReturn('test');
        $this->assertEquals(
            'array_replace(test, test, test)',
            $this->arrayReplace->getSql($sqlWalker->reveal())
        );
    }
}
