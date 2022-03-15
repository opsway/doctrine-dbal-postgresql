<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use OpsWay\Doctrine\ORM\Query\AST\Functions\Contains;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ContainsTest extends TestCase
{
    use ProphecyTrait;

    /** @var Contains */
    private $contains;

    public function setUp() : void
    {
        $this->contains = new Contains('test');
    }

    public function testFunction() : void
    {
        $parser = $this->prophesize(Parser::class);
        $expr   = $this->prophesize(ParenthesisExpression::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->StringPrimary()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_COMMA]);
        $parser->InputParameter()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $this->contains->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');
        $sqlWalker->walkInputParameter()->shouldBeCalled()->withArguments([$expr->reveal()])->willReturn('test');
        $this->assertEquals(
            '(test @> test)',
            $this->contains->getSql($sqlWalker->reveal())
        );
    }
}
