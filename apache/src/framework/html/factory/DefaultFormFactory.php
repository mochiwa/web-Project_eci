<?php

namespace Framework\Html\Factory;

/**
 * Description of DefaultFormFactory
 *
 * @author mochiwa
 */
class DefaultFormFactory extends AbstractFormFactory{
    private $action;
    private $method;
    private $id;
    
    
    public function __construct(string $action='#',string $method='POST',string $id='form') {
        $this->action= Attribute::oneContent('action', $action);
        $this->method= Attribute::oneContent('method',$method);
        $this->id=$id;
    }
    
    public function action(): Attribute {
        return $this->action;
    }

    public function id(): string {
        return $this->id;
    }

    public function method(): Attribute {
        return $this->method;
    }

    public function sectionButtons(): ?HtmlTag {
        return HtmlTag::make('div')->setId($this->id.'-section-buttons');
    }

    public function sectionFields(): ?HtmlTag {
        return HtmlTag::make('div')->setId($this->id.'-section-fields');;
    }

    public function label(string $text, string $for) :HtmlTag{
        $label=HtmlTag::make('label');
        $label->addAttribute(Attribute::oneContent('for', $for));
        $label->addText($text);
        return $label;
    }

    public function sectionErrors(array $errors=[]): ?HtmlTag {
        $div= HtmlTag::make('div')->setId($this->id.'section-errors');
        foreach ($errors as $fieldErrors) {
            $ul= HtmlTag::make('ul');
            foreach ($fieldErrors as $error) {
                $ul->addChild(HtmlTag::make('li')->addText($error));
            }
            $div->addChild($ul);
        }
        return $div;
    }

    public function submit(string $text): Input {
        $button= new Input('submit', 'submit');
        $button->setValue($text);
        $button->setId($this->id.'-submit');
        return $button;
    }

    public function cancel(string $text, string $target): HtmlTag {
        $link= HtmlTag::make('a');
        $link->addAttribute(Attribute::oneContent('href', $target))
                ->addText($text)
                ->setId($this->id.'-cancel');
        return $link;
    }

}
