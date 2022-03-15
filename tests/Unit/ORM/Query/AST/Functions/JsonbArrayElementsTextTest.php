<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use OpsWay\Doctrine\ORM\Query\AST\Functions\JsonbArrayElementsText;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class JsonbArrayElementsTextTest extends TestCase
{
    use ProphecyTrait;

    /** @var JsonbArrayElementsText */
    private $jsonbArrayElementsText;

    public function setUp() : void
    {
        $this->jsonbArrayElementsText = new JsonbArrayElementsText('test');
    }

    public function testFunction() : void
    {
        $parser = $this->prophesize(Parser::class);
        $expr   = $this->prophesize(ParenthesisExpression::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->ArithmeticPrimary()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $this->jsonbArrayElementsText->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals(
            'jsonb_array_elements_text(test)',
            $this->jsonbArrayElementsText->getSql($sqlWalker->reveal())
        );
    }
}
