<?php

/*
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 * (c) 2009 Armin Ronacher
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Represents a block node.
 *
 * @package    twig
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class Twig_Node_Block extends Twig_Node
{
    public function __construct($name, Twig_NodeInterface $body, $lineno, $tag = null)
    {
        parent::__construct(array('body' => $body), array('name' => $name), $lineno, $tag);
    }

    public function compile($compiler)
    {
        $compiler
            ->addDebugInfo($this)
            ->write(sprintf("public function block_%s(\$context, \$parents)\n", $this['name']), "{\n")
            ->indent()
        ;

        $compiler
            ->subcompile($this->body)
            ->outdent()
            ->write("}\n\n")
        ;
    }
}
