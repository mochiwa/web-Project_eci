<?php
namespace Test\Framework\HtmlBuilder;

use Framework\HtmlBuilder\Form;
use Framework\HtmlBuilder\Input;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FormTest
 *
 * @author mochiwa
 */
class FormTest extends TestCase {

    private $form;

    function test_constructor_shouldThrowException_whenNameIsEmpty() {
        $this->expectException(InvalidArgumentException::class);
        new Form('');
    }

    function test_constructor_shouldThrowException_whenActionIsEmpty() {
        $this->expectException(InvalidArgumentException::class);
        new Form('aName', '');
    }

    function test_constructor_shouldThrowException_whenMethodIsDiffThanGET() {
        $this->expectException(InvalidArgumentException::class);
        new Form('aName', 'aTarget', '');
    }

    function test_constructor_shouldThrowException_whenMethodIsDiffThanPOST() {
        $this->expectException(InvalidArgumentException::class);
        new Form('aName', 'aTarget', '');
    }

    function test_constructor_shouldAppendIdForSection() {
        $form = new Form('aName', '#', 'POST', 'formId');
        $this->assertSame('<form name="aName" id="formId" action="#" method="POST"><div id="formId-section-fields"></div><div id="formId-section-buttons"></div></form>', $form->toHtml());
    }

    function test_setId_ShouldUpdateIdOfSection() {
        $form = new Form('aName', '#', 'POST', 'formId');
        $this->assertSame('<form name="aName" id="formId" action="#" method="POST"><div id="formId-section-fields"></div><div id="formId-section-buttons"></div></form>', $form->toHtml());
        $form->setId('anId');
        $this->assertSame('<form name="aName" id="anId" action="#" method="POST"><div id="anId-section-fields"></div><div id="anId-section-buttons"></div></form>', $form->toHtml());
    }

    function test_addInput_shouldAppendAnId_whenInputNotAlreadyHas() {
        $form = new Form('aName', '#', 'POST', 'formId');
        $form->addInput(new Input('username'));
        $this->assertNotNull($form->getInput('formId-field-username'));
    }

    function test_addInput_shouldAppendFormPredicatIfFormHaventId_whenInputNotAlreadyHas() {
        $form = new Form('aName', '#', 'POST');
        $form->addInput(new Input('username'));
        $this->assertNotNull($form->getInput('form-field-username'));
    }

    function test_getInput_shouldReturnNull_whenInputNotFound() {
        $form = new Form('aName', '#', 'POST');


        $this->assertNull($form->getInput('anInput'));
    }

    function test_getInput_shouldReturnTheInput_whenItFoundById() {
        $form = new Form('aName', '#', 'POST');
        $input = new Input('anInput');
        $input->setId('input-01');
        $form->addInput($input);

        $this->assertNotNull($form->getInput('input-01'));
    }

    function test_addInput_shouldAppendAnIdIncremented_whenInputIdAlreadyUsed() {
        $form = new Form('aName', '#', 'POST', 'formId');
        $form->addInput(new Input('username'));
        $form->addInput(new Input('username'));
        $form->addInput(new Input('username'));
        $this->assertNotNull($form->getInput('formId-field-username'));
        $this->assertNotNull($form->getInput('formId-field-username-2'));
        $this->assertNotNull($form->getInput('formId-field-username-3'));
    }
    function test_setErrors_shouldAppendNothing_whenErrorsIsEmpty()
    {
        $errors=[];
        $form = new Form('aName', '#', 'POST', 'formId');
        $form->addInput(new Input('username'));
        $form->setErrors($errors);
        $this->assertNull($form->getChild('formId-field-username-error'));
    }
    
    function test_setErrors_shouldAppendAnDivWhereIdContainTheFieldError()
    {
        $errors['username']=['A message error !'];
        $form = new Form('aName', '#', 'POST', 'formId');
        $form->addInput(new Input('username'));
        $form->setErrors($errors);
        $this->assertNotNull($form->getChild('formId-section-errors')->getChild('formId-field-username-errors'));
    }
    function test_setErrors_shouldAppendAnDivWithErrorAboutTheField()
    {
        $errors['username']=['A message error !'];
        $form = new Form('aName', '#', 'POST', 'formId');
        $form->addInput(new Input('username'));
        $form->setErrors($errors);
        
        $erroDiv=$form->getChild('formId-section-errors')->getChild('formId-field-username-errors');
        $this->assertSame('<div id="formId-field-username-errors"><ul><li>A message error !</li></ul></div>',$erroDiv->toHtml());
    }

}
