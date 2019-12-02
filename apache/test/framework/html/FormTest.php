<?php

namespace Test\Framework\html;

use Framework\Html\Form;
use Framework\Html\Input;
use PHPUnit\Framework\TestCase;

/**
 * Description of FormTest
 *
 * @author mochiwa
 */
class FormTest extends TestCase{
    
    
    
    function test_toHtml_shouldReturnAnEmptyPostFormWithoutAction_whenItHasNothing()
    {
        $form=new Form();
        $this->assertEquals('<form id="form" action="#" method="POST"></form>', $form->toHtml());
    }
    
    function test_toHtml_shouldReturnAnEmptyFormWithAGenericId_whenIdIsNotSpecified()
    {
        $form=new Form();
        $this->assertEquals('<form id="form" action="#" method="POST"></form>', $form->toHtml());
    }
    
    
    function test_toHtml_shouldReturnAnEmptyFormWithActionAndMethod_whenItSpecifiedIntoConstructor()
    {
        $form=new Form('mypage','GET','registerForm');
        $this->assertEquals('<form id="registerForm" action="mypage" method="GET"></form>', $form->toHtml());
    }
    
    function test_addInput_shouldAppendIdLike_form_InputName_whenInputHasNotId()
    {
        $form=new Form('mypage','GET');
        $input= new Input('myInput');
        $form->addInput($input);
        $this->assertEquals('<input name="myInput" type="text" id="form-myInput">', $form->getInputById('form-myInput')->toHtml());
    }
    function test_addInput_shouldNotEditTheInputId_whenTheInputHasAlreadyAnId()
    {
        $form=new Form('mypage','GET');
        $input= new Input('myInput');
        $input->setId('teste');
        $form->addInput($input);
        $this->assertEquals('<input name="myInput" type="text" id="teste">', $form->getInputById('teste')->toHtml());
    }
    
    function test_addInput_shouldAppendIdhyphen2_whenIdAlreadyExistOnceInForm()
    {
        $form=new Form('mypage','GET');
        $form->addInput($input= new Input('myInput'))->addInput($input= new Input('myInput'));
        $this->assertNotNull($form->getInputById('form-myInput-2'));
    }
    function test_addInput_shouldAppendIdhyphen3_whenIdAlreadyExistTwiceInForm()
    {
        $form=new Form('mypage','GET');
        $form->addInput(new Input('myInput'))
                ->addInput(new Input('myInput'))
                ->addInput(new Input('myInput'));
        $this->assertNotNull($form->getInputById('form-myInput-3'));
    }
    
    function test_addInputWithLabel_shouldAppendALabelBeforeTheInput()
    {
        $form=new Form();
        $form->addInputWithLabel(new Input('myInput'),'labelValue');
        $this->assertEquals(''
                . '<form id="form" action="#" method="POST">'
                . '<label for="form-myInput">labelValue</label>'
                . '<input name="myInput" type="text" id="form-myInput">'
                . '</form>', $form->toHtml());
    }
    
    
    function test_setErrors_shouldDoingNothing_whenErrorArrayIsEmpty()
    {
        $form=new Form();
        $form->setErrors([]);
        $this->assertEquals('<form id="form" action="#" method="POST"></form>', $form->toHtml());
    }
    
    function test_setErrors_shouldAppendAnErrorDivOnTopOfTheForm_whenErrorsIsNotEmpty()
    {
        $form=new Form();
        $form->addInput(new Input('myInput'));
        $form->setErrors(['myInput'=>['an error occurs']]);
        $this->assertEquals('<form id="form" action="#" method="POST">'
                . '<div><ul>'
                . '<li>an error occurs</li>'
                . '</ul></div>'
                .'<input name="myInput" type="text" id="form-myInput">'
                .'</form>', $form->toHtml());
    }
    
    function test_fillForm_shouldAppendValueOnEachInput_whenFormContainsTheId()
    {
        $form=new Form();
        $form->addInput(new Input('myInput'));
        $form->fillForm(['form-myInput'=>'a value']);
        $this->assertEquals('<form id="form" action="#" method="POST">'
                .'<input name="myInput" type="text" id="form-myInput" value="a value">'
                .'</form>', $form->toHtml());
    }
    
    
}
