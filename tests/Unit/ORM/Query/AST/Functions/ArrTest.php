<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use OpsWay\Doctrine\ORM\Query\AST\Functions\Arr;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ArrTest extends TestCase
{
    use ProphecyTrait;

    /** @var Arr */
    private $arrFunction;

    public function setUp() : void
    {
        $this->arrFunction = new Arr('test');
    }

    public function testFunction() : void
    {
        $parser = $this->prophesize(Parser::class);
        $expr   = $this->prophesize(ParenthesisExpression::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->StringPrimary()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $this->arrFunction->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals('ARRAY[test]', $this->arrFunction->getSql($sqlWalker->reveal()));
    }
}
