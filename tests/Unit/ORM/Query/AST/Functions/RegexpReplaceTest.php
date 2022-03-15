<?php

declare(strict_types=1);

namespace OpsWay\Tests\Unit\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use OpsWay\Doctrine\ORM\Query\AST\Functions\RegexpReplace;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RegexpReplaceTest extends TestCase
{
    use ProphecyTrait;

    /** @var RegexpReplace  */
    private $regexpReplace;

    public function setUp() : void
    {
        $this->regexpReplace = new RegexpReplace('test');
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
        $parser->StringPrimary()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_COMMA]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);

        $lexerEntity->lookahead['type'] = '';
        $parser->getLexer()->shouldBeCalled()->willReturn($lexerEntity);
        $this->regexpReplace->parse($parser->reveal());

        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals('regexp_replace(test, test, test)', $this->regexpReplace->getSql($sqlWalker->reveal()));

        $lexerEntity->lookahead['type'] = Lexer::T_COMMA;
        $parser->getLexer()->shouldBeCalled()->willReturn($lexerEntity);
        $this->regexpReplace->parse($parser->reveal());

        $this->assertEquals(
            'regexp_replace(test, test, test, test)',
            $this->regexpReplace->getSql($sqlWalker->reveal())
        );
    }
}
