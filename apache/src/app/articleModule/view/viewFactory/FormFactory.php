<?php
namespace App\Article\view\ViewFactory;

use Framework\Html\Attribute;
use Framework\Html\Form;
use Framework\Html\Input;

/**
 * Description of FormFactory
 *
 * @author mochiwa
 */
class FormFactory {
    
    public static function createParkingForm($action,array $errors=[],array $values=[],$cancelTarget='#'): Form{
        $factory=new ParkingFormFactory($action, 'POST');
        $form=new Form($factory);
        $form->addStyle('form');
        $form->addAttribute(Attribute::oneContent('enctype', 'multipart/form-data'));
        $form->addInputWithLabel(Input::TEXT('title'),'Types the parking title');
        $form->addInputWithLabel(Input::FILE('picture'),'Picture');
        $form->addInputWithLabel(Input::TEXT('city'),'City');
        $form->addInputWithLabel(Input::TEXT('place'),'Count of place');
        $form->addInputWithLabel(Input::TEXT('parking_name'),'the name of the parking');
        $form->addCancel('cancel', $cancelTarget);
        $form->addSubmit('Upload article');
        $form->setErrors($errors);
        $form->fillForm($values);
        return $form;
        
    }
}
