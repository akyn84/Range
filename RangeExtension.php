<?php

namespace Range;

use Nette;

class RangeExtension extends Nette\DI\CompilerExtension {

    public function loadConfiguration() {
    }

    public function beforeCompile() {
        parent::beforeCompile();
    }

    public function afterCompile(Nette\PhpGenerator\ClassType $class) {
        $initialize = $class->methods['initialize'];
        $initialize->addBody('Nette\Forms\Container::extensionMethod("\Nette\Forms\Container::addRange", function (\Nette\Forms\Container $_this, $name, $label = null) { return $_this[$name] = new Range\Range($label, $this->getByType(?)); });', ['Nette\Http\IRequest']);
    }

}
