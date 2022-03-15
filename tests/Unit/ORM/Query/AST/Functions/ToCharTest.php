<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use OpsWay\Doctrine\ORM\Query\AST\Functions\ToChar;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ToCharTest extends TestCase
{
    use ProphecyTrait;

    /** @var ToChar */
    private $toChar;

    public function setUp() : void
    {
        $this->toChar = new ToChar('test');
    }

    public function testFunction() : void
    {
        $parser    = $this->prophesize(Parser::class);
        $expr      = $this->prophesize(ParenthesisExpression::class);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->ArithmeticExpression()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_COMMA]);
        $parser->StringPrimary()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);

        $this->toChar->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals('TO_CHAR(test, test)', $this->toChar->getSql($sqlWalker->reveal()));
    }
}
