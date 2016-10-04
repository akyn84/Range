<?php

namespace Range;

use Latte\Engine,
    Nette\Bridges,
    Nette\Http\Request,
    Nette\Forms\Controls\TextInput,
    Nette\Utils\Html,
    Nette\Forms\Controls;

/** @author Lubo Andrisek */
class Range extends Controls\BaseControl {

    /** @var string */
    private $basePath;

    /** @var Array */
    private $cookies;

    /** @return IRangeFactory */
    public function create() {
        return $this;
    }

    public function __construct($label = null, Request $request) {
	parent::__construct($label);
        $this->cookies = $request->getCookies();
    }

    /** getters */
    public function getControl() {
        return '<div class="buffer"><div id="date-slider"></div></div>';
    }

    public function getValue() {
        return [(isset($this->cookies['range-from'])) ? cookies['range-from'] : $this->value['from'],
                  (isset($this->cookies['range-to'])) ? cookies['range-to'] : $this->value['to'],  
        ];
    }

    public function renderHead() {
        $latte = new Engine();
        $template = new Bridges\ApplicationLatte\Template($latte);
        $template->basePath = __DIR__;
        $template->setFile(__DIR__ .  '/templates/head.latte');
        return $template->render();
    }

    public function renderFooter() {
        $latte = new Engine();
        $template = new Bridges\ApplicationLatte\Template($latte);
        $template->basePath = __DIR__;
        $template->setFile(__DIR__ .  '/templates/footer.latte');
        return $template->render();
    }

}

interface IRangeFactory {
    /** @return Range */
    function create();
}