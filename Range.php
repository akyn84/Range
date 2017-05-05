<?php

namespace Range;

use Latte\Engine,
    Nette\Application\UI\Form,
    Nette\Bridges,
    Nette\Http\Request,
    Nette\Forms\Controls,
    Nette\Localization\ITranslator;

/** @author Lubomir Andrisek */
class Range extends Controls\BaseControl implements IRangeFactory {

    /** @var ITranslator */
    private $translator;
    
    /** @var string */
    private $basePath;

    /** @var Array */
    private $cookies;

    /** @var Array */
    private $defaults;

    /** @var string */
    private $type;
    
    /** @var string */
    private $key;

    /** @var string */
    protected $label;

    /** @var string */
    private $id;

    /** @return IRangeFactory */
    public function create() {
        return $this;
    }

    public function __construct($label = null, Array $defaults = [], Request $request, ITranslator $translator) {
        parent::__construct($label);
        $url = $request->getUrl();
        $this->cookies = $request->getCookies();
        $this->defaults = $defaults;
        $this->label = $label;
        $this->translator = $translator;
        $this->basePath = $url->scheme . '://' . $url->host . $url->scriptPath . 'vendor/landrisek/nette-range';
    }

    public function attached($form) {
        parent::attached($form);
        if ($form instanceof Form) {
            $parent = $this->getForm()->getParent();
            $this->key = (is_object($parent)) ? $parent->getName() : $this->getForm()->getName();
            $this->key .= '-' . $this->getName() . '-';
            $this->cookies[$this->key . '>'] = (isset($this->cookies[$this->key . '>'])) ? $this->cookies[$this->key . '>'] : $this->defaults['>'];
            $this->cookies[$this->key . '<'] = (isset($this->cookies[$this->key . '<'])) ? $this->cookies[$this->key . '<'] : $this->defaults['<'];
            $this->id = (is_object($parent)) ? $parent->getName() . '-' . $this->getForm()->getName() : $this->getForm()->getName();
        }
    }

    /** setters */
    public function setCookieKey($key) {
        $this->key = $key;
        return $this;
    }

    /** getters */
    public function getControl() {
        if ((bool) strpbrk($this->cookies[$this->key . '>'], 1234567890) and
                strtotime($this->cookies[$this->key . '>']) and
                (bool) strpbrk($this->cookies[$this->key . '<'], 1234567890) and
                strtotime($this->cookies[$this->key . '<'])) {
            $this->type = 'date';
            return '<div class="buffer"><div id="date-slider"></div></div>';
        } elseif(preg_match('/\./', $this->cookies[$this->key . '>']) or preg_match('/\./', $this->cookies[$this->key . '<'])) {
            $this->type = 'float';
            return  '<label for="amount">' . $this->translator->translate($this->label) . ':</label><input type="text" id="amount" readonly style="border:0; font-weight:bold;">' .
                    '<div class="buffer"><div id="float-slider"></div></div>';
        } else {
            $this->type = 'edit';
            return '<div class="buffer"><div id="edit-slider"></div></div>';
        }
    }

    public function getValue() {
        return ['>' => $this->cookies[$this->key . '>'],
            '<' => $this->cookies[$this->key . '<']
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
        $template->link = $this->getForm()->getPresenter()->link('this');
        $template->basePath = $this->basePath;
        $template->key = $this->key;
        $template->id = $this->id;
        $template->min = $this->defaults['min'];
        $template->max = $this->defaults['max'];
        $template->type = $this->type;
        $template->from = (isset($this->cookies[$this->key . '>'])) ? $this->cookies[$this->key . '>'] : $this->defaults['>'];
        $template->to = (isset($this->cookies[$this->key . '<'])) ? $this->cookies[$this->key . '<'] : $this->defaults['<'];
        $template->setFile(__DIR__ . '/templates/footer.latte');
        $template->setTranslator($this->translator);
        return $template->render();
    }

}

interface IRangeFactory {

    /** @return Range */
    function create();
}
