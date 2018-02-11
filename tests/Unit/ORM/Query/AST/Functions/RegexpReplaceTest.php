<?php

namespace Opsway\Tests\Doctrine\ORM\Query\AST\Functions;

use Doctrine\ORM\Query\AST\ParenthesisExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Opsway\Doctrine\ORM\Query\AST\Functions\RegexpReplace;
use PHPUnit\Framework\TestCase;

class RegexpReplaceTest extends TestCase
{
    private $regexpReplace;

    public function setUp()
    {
        $this->regexpReplace = new RegexpReplace('test');
    }

    public function testFunction()
    {
        $parser = $this->prophesize(Parser::class);
        $expr = $this->prophesize(ParenthesisExpression::class);
        $lexer = $this->prophesize(Lexer::class);

        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_IDENTIFIER]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_OPEN_PARENTHESIS]);
        $parser->StringPrimary()->shouldBeCalled()->willReturn($expr->reveal());
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_COMMA]);
        $parser->match()->shouldBeCalled()->withArguments([Lexer::T_CLOSE_PARENTHESIS]);
        $parser->getLexer()->shouldBeCalled()->willReturn($lexer->reveal());
        $sqlWalker = $this->prophesize(SqlWalker::class);

        $this->regexpReplace->parse($parser->reveal());

        $expr->dispatch()->shouldBeCalled()->withArguments([$sqlWalker->reveal()])->willReturn('test');

        $this->assertEquals('regexp_replace(test, test, test)', $this->regexpReplace->getSql($sqlWalker->reveal()));

        $lexerEntity = $lexer->reveal();
        $lexerEntity->lookahead['type'] = Lexer::T_COMMA;
        $parser->getLexer()->shouldBeCalled()->willReturn($lexerEntity);
        $this->regexpReplace->parse($parser->reveal());

        $this->assertEquals('regexp_replace(test, test, test, test)', $this->regexpReplace->getSql($sqlWalker->reveal()));
    }
}
