<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__).'/TestCase.php';

class Twig_Tests_Node_TransTest extends Twig_Tests_Node_TestCase
{
    /**
     * @covers Twig_Node_Trans::__construct
     */
    public function testConstructor()
    {
        $count = new Twig_Node_Expression_Constant(12, 0);
        $body = new Twig_Node(array(
            new Twig_Node_Text('Hello', 0),
        ), array(), 0);
        $plural = new Twig_Node(array(
            new Twig_Node_Text('Hey ', 0),
            new Twig_Node_Print(new Twig_Node_Expression_Name('name', 0), 0),
            new Twig_Node_Text(', I have ', 0),
            new Twig_Node_Print(new Twig_Node_Expression_Name('count', 0), 0),
            new Twig_Node_Text(' apples', 0),
        ), array(), 0);
        $node = new Twig_Node_Trans($count, $body, $plural, 0);

        $this->assertEquals($body, $node->body);
        $this->assertEquals($count, $node->count);
        $this->assertEquals($plural, $node->plural);
    }

    /**
     * @covers Twig_Node_Trans::compile
     * @covers Twig_Node_Trans::compileString
     * @dataProvider getTests
     */
    public function testCompile($node, $source, $environment = null)
    {
        parent::testCompile($node, $source, $environment);

        $body = new Twig_Node(array(
            new Twig_Node_Expression_Constant('Hello', 0),
        ), array(), 0);
        $node = new Twig_Node_Trans(null, $body, null, 0);

        try {
            $node->compile($this->getCompiler());
            $this->fail();
        } catch (Exception $e) {
            $this->assertEquals('Twig_SyntaxError', get_class($e));
        }
    }

    public function getTests()
    {
        $tests = array();

        $body = new Twig_Node(array(
            new Twig_Node_Text('Hello', 0),
        ), array(), 0);
        $node = new Twig_Node_Trans(null, $body, null, 0);
        $tests[] = array($node, 'echo gettext("Hello");');

        $body = new Twig_Node(array(
            new Twig_Node_Text('J\'ai ', 0),
            new Twig_Node_Print(new Twig_Node_Expression_Name('foo', 0), 0),
            new Twig_Node_Text(' pommes', 0),
        ), array(), 0);
        $node = new Twig_Node_Trans(null, $body, null, 0);
        $tests[] = array($node, 'echo strtr(gettext("J\'ai %foo% pommes"), array("%foo%" => $this->getContext($context, \'foo\'), ));');

        $count = new Twig_Node_Expression_Constant(12, 0);
        $body = new Twig_Node(array(
            new Twig_Node_Text('Hey ', 0),
            new Twig_Node_Print(new Twig_Node_Expression_Name('name', 0), 0),
            new Twig_Node_Text(', I have one apple', 0),
        ), array(), 0);
        $plural = new Twig_Node(array(
            new Twig_Node_Text('Hey ', 0),
            new Twig_Node_Print(new Twig_Node_Expression_Name('name', 0), 0),
            new Twig_Node_Text(', I have ', 0),
            new Twig_Node_Print(new Twig_Node_Expression_Name('count', 0), 0),
            new Twig_Node_Text(' apples', 0),
        ), array(), 0);
        $node = new Twig_Node_Trans($count, $body, $plural, 0);
        $tests[] = array($node, 'echo strtr(ngettext("Hey %name%, I have one apple", "Hey %name%, I have %count% apples", abs(12)), array("%name%" => $this->getContext($context, \'name\'), "%name%" => $this->getContext($context, \'name\'), "%count%" => abs(12), ));');

        return $tests;
    }
}
