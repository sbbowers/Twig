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
abstract class Twig_Node_Expression_Binary extends Twig_Node_Expression
{
    public function __construct(Twig_NodeInterface $left, Twig_NodeInterface $right, $lineno)
    {
        parent::__construct(array('left' => $left, 'right' => $right), array(), $lineno);
    }

    public function compile($compiler)
    {
        $compiler
            ->raw('(')
            ->subcompile($this->left)
            ->raw(') ')
        ;
        $this->operator($compiler);
        $compiler
            ->raw(' (')
            ->subcompile($this->right)
            ->raw(')')
        ;
    }

    abstract public function operator($compiler);
}
