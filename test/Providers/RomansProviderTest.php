<?php

namespace IlluminateTest\Romans\Providers;

use Illuminate\Romans\Providers\RomansProvider;
use Illuminate\Support\ServiceProvider;
use IlluminateTest\Romans\Foundation\Application;
use PHPUnit\Framework\TestCase;
use Romans\Grammar\Grammar;
use Romans\Lexer\Lexer;
use Romans\Parser\Parser;

class RomansProviderTest extends TestCase
{
    protected function setUp()
    {
        $this->application = $this->getMockForAbstractClass(Application::class);
        $this->provider    = new RomansProvider($this->application);
    }

    public function testInstance()
    {
        $this->assertInstanceOf(ServiceProvider::class, $this->provider);
    }

    public function testDeferred()
    {
        $this->assertTrue($this->provider->isDeferred());
    }

    public function testProvides()
    {
        $provides = $this->provider->provides();

        $this->assertContains(Grammar::class, $provides);
        $this->assertContains(Lexer::class, $provides);
        $this->assertContains(Parser::class, $provides);
    }

    public function testBind()
    {
        $this->provider->register();

        $this->assertTrue($this->application->bound(Grammar::class));
        $this->assertTrue($this->application->bound(Lexer::class));
        $this->assertTrue($this->application->bound(Parser::class));
    }

    public function testGrammar()
    {
        $this->provider->register();

        $grammar = $this->application->make(Grammar::class);

        $this->assertSame($grammar, $this->application->make(Grammar::class));
    }

    public function testLexer()
    {
        $this->provider->register();

        $grammar = $this->application->make(Grammar::class);
        $lexer   = $this->application->make(Lexer::class);

        $this->assertSame($lexer, $this->application->make(Lexer::class));
        $this->assertSame($grammar, $lexer->getGrammar());
    }

    public function testParser()
    {
        $this->provider->register();

        $grammar = $this->application->make(Grammar::class);
        $parser  = $this->application->make(Parser::class);

        $this->assertSame($parser, $this->application->make(Parser::class));
        $this->assertSame($grammar, $parser->getGrammar());
    }
}
