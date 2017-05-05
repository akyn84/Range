Range
==================
Range extension for nette forms

Demo
----
- Composer package: https://packagist.org/packages/landrisek/nette-range

Installation
------------
1. Install [composer](https://getcomposer.org/download/) if you don't have it yet
2. run `composer require landrisek/nette-range:1.*`
3. Copy files from nette-range/assets to your www/assets and include them into your template or follow Optional settings

Required settings
-----------------
Edit your config.neon  

extensions:
	range: Range\RangeExtension

Add to template:

    <a href="#" onclick="submitRange();">{input yourSubmitButton}</a>

Add in MyForm::attached:
	$this->addRange('myRangeId', 'My range label')
        ->setDefaultValue('min'=>$minimalValue,'max'=>$maximalValue,'from'=>$fromValue,'to'=>$toValue);

Add in MyForm::render:
    $this->templates->ranges = ['myRangeId','myOtherRangeId'];

In formSucced method you will get array:

    public function formSucceed($form) {
        $values = $form->getValues();
        $from = $values['myRangeId']['from'];
        $to = $values['myRangeId']['to'];
    }

Optional settings
-----------------

Edit your config.neon: 

services:
- Range\IRangeFactory

Add to your form / component:

    /** @var IRangeFactory */
    private $rangeFactory;
	public function __construct(Range\IRangeFactory $rangeFactory) {
        $this->rangeFactory = $rangeFactory;
    }
    protected function createComponentRange() {
        return $this->rangeFactory->create();
    }
Add to template of your form / component:

    {control range:head}
	{control $myForm}
	{control range:footer}