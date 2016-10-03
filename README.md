# Range
Range extension for nette forms
Required settings:
Edit your config.neon  
extensions:
	range: Range\RangeExtension
Add to template:
    <a href="#" onclick="submitRange();">{input yourSubmitButton}</a>
Call from form:
	$this->addRange('myRangeId', 'My range label');
In formSucced method you will get array:
    public function formSucceed($form) {
        $values = $form->getValues();
        $from = $values['myRangeId']['from'];
        $to = $values['myRangeId']['to'];
    }
Optional setting:
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