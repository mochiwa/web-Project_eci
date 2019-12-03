<?php
namespace App\Article\view\ViewFactory;

use Framework\Html\Attribute;
use Framework\Html\Form;

/**
 * Description of FormFactory
 *
 * @author mochiwa
 */
class FormBuilder {
    
    public function createParkingForm($action,array $errors=[],array $values=[],$cancelTarget='#'): Form
    {
        $formFactory=new ParkingFormFactory($action);
        $inputFactory=new ParkingInputFactory();
        
        $form=new Form($formFactory);
        $form->addStyle('form');
        $form->addAttribute(Attribute::oneContent('enctype', 'multipart/form-data'));
        
        $form->addInputWithLabel($inputFactory->build('text', 'title'),'Title :')
            ->addInputWithLabel($inputFactory->build('file', 'picture'),'Picture')
            ->addInputWithLabel($inputFactory->build('text', 'city'),'City')
            ->addInputWithLabel($inputFactory->build('text', 'place'),'Count of place')
            ->addInputWithLabel($inputFactory->build('text', 'name'),'parking name')
            ->addInputWithLabel($inputFactory->build('textArea', 'description'), 'Parking description')
            ->addCancel('cancel', $cancelTarget)
            ->addSubmit('Upload article');
        
        $form->setErrors($errors);
        $form->fillForm($values);
        return $form;
        
    }
}
