<?php

namespace Range;

use Latte\Engine,
    Nette\Bridges,
    Nette\Http\Request,
    Nette\Forms\Controls,
    Nette\Application\UI\Form;

/** @author Lubo Andrisek */
class Range extends Controls\BaseControl implements IRangeFactory {

    /** @var string */
    private $basePath;

    /** @var Array */
    private $cookies;

    /** @var string */
    private $key;
    
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

    public function attached($form) {
        parent::attached($form);
        if ($form instanceof Form) {
            $parent = $this->getForm()->getParent();
            $this->key = (is_object($parent)) ? $parent->getName() : $this->getForm()->getName();
            $this->key .= '-' . $this->getName() . '-';
        }
    }

    /** setters */
    public function setCookieKey($key) {
        $this->key = $key;
        return $this;
    }

    /** getters */
    public function getControl() {
        return '<div class="buffer"><div id="date-slider"></div></div>';
    }

    public function getValue() {
        return ['from' => $this->cookies[$this->key . 'range-from'],
            'to' => $this->cookies[$this->key . 'range-to']
        ];
    }

    public function renderHead() {
        $latte = new Engine();
        $template = new Bridges\ApplicationLatte\Template($latte);
        $template->basePath = $this->basePath;
        $template->setFile(__DIR__ . '/templates/head.latte');
        return $template->render();
    }

    public function renderFooter() {
        $latte = new Engine();
        $template = new Bridges\ApplicationLatte\Template($latte);
        $template->basePath = $this->basePath;
        $template->key = $this->key;
        $template->min = $this->value['min'];
        $template->max = $this->value['max'];
        $template->from = (isset($this->cookies[$this->key . '-range-from'])) ? $this->cookies[$this->key . '-range-from'] : $this->value['from'];
        $template->to = (isset($this->cookies[$this->key . '-range-to'])) ? $this->cookies[$this->key . '-range-to'] :$this->value['to'];
        $template->setFile(__DIR__ . '/templates/footer.latte');
        return $template->render();
    }

}

interface IRangeFactory {

    /** @return Range */
    function create();
}
