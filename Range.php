<?php

namespace Range;

use Latte\Engine,
	Nette\Bridges,
    Nette\Http\Request,
    Nette\Forms\Controls\TextInput,
	Nette\Utils\Html;

/** @author Lubo Andrisek */
class Range extends TextInput {

    /** @var string */
    private $basePath;

    /** @var Array */
    private $range;

    /** @return IRangeFactory */
    public function create() {
        return $this;
    }

    public function __construct($label = null, Request $request) {
		parent::__construct($label);
        $url = $request->getUrl();
        $cookies = $request->getCookies();
        $this->range = ['from' => $cookies['range-from'],'to' => $cookies['range-to']];
        $this->basePath = $url->scheme . '://' . $url->host . $url->scriptPath;
	}

    /** getters */
	public function getControl() {
        return '<div class="buffer"><div id="date-slider"></div></div>';
	}

    public function getValue() {
        return $this->range;
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