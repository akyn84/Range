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
        $url = $request->getUrl();
        $this->cookies = $request->getCookies();
        $this->basePath = $url->scheme . '://' . $url->host . $url->scriptPath . 'vendor/landrisek/nette-range';
    }

    /** getters */
    public function getControl() {
        return '<div class="buffer"><div id="date-slider"></div></div>';
    }

    public function getValue() {
        return ['from' => $this->cookies['range-from'],
                'to' => $this->cookies['range-to']  
        ];
    }

    public function renderHead() {
        $latte = new Engine();
        $template = new Bridges\ApplicationLatte\Template($latte);
        $template->basePath = $this->basePath;
        $template->setFile(__DIR__ .  '/templates/head.latte');
        return $template->render();
    }

    public function renderFooter() {
        $latte = new Engine();
        $template = new Bridges\ApplicationLatte\Template($latte);
        $template->basePath = $this->basePath;
        $template->setFile(__DIR__ .  '/templates/footer.latte');
        return $template->render();
    }

}

interface IRangeFactory {
    /** @return Range */
    function create();
}