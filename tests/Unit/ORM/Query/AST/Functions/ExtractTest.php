<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use OpsWay\Doctrine\ORM\Query\AST\Functions\Extract;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class ExtractTest extends TestCase
{
    use ProphecyTrait;

    /** @var Extract */
    private $extract;

    public function setUp() : void
    {
        $this->extract = new Extract('test');
    }

    public function testFunction() : void
    {
        $parser      = $this->prophesize(Parser::class);
        $expr        = $this->prophesize(ParenthesisExpression::class);
        $sqlWalker   = $this->prophesize(SqlWalker::class);
        $lexer       = $this->prophesize(Lexer::class);
        $lexerEntity = $lexer->reveal();

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);

        $lexerEntity->token['value'] = 'test';
        $parser->getLexer()->shouldBeCalled()->willReturn($lexerEntity);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_FROM]);
        $parser->ScalarExpression()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);

        $this->extract->parse($parser->reveal());
        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals('EXTRACT(test FROM test)', $this->extract->getSql($sqlWalker->reveal()));
    }
}
