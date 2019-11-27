<?php
namespace Framework\HtmlBuilder;

/**
 * This class represent an attribute from an
 * html element like 'class' from <div class=""></div>
 *
 * @author Chiappelloni Nicolas
 * @since 1.0
 */
class Attribute {

    private $name;
    private $contents;

    function __construct(string $name,$contents = []) {
        $this->name = preg_replace('/[^a-z\-]/', '', strtolower($name));
        $this->contents = $contents;
    }

    public static function of(string $name): self {
        return new Attribute($name);
    }

    /**
     * Transform this class to a string that can be used in an HtmlElement
     * @return string class transformed to a string readable by html
     */
    public function toHtml(): string {
        $output = $this->name . '="';
        foreach ($this->contents as $content => $isActive) {
            if ($this->callback($isActive))
                $output .= $content . ' ';
        }
        //sizeof($this->contents) ? $output[-1] = '"' : $output .= '"';
        
        return rtrim($output).'"';
    }

    /**
     * Append a value to the attribute, the value will escape htmlEntities
     * and the value can have an argument to  set if it's active, 
     * this argument can by a closure.
     * @param string the value to append to the attribute
     * @param boolean the argument to enable/disable value
     */
    public function addContent(string $content, $isActive = true): self {
        $this->contents[htmlentities($content)] = $isActive;
        return $this;
    }

    /**
     * Replace actual value to the attribute , the value can have an argument to 
     * set if it's active , this argument can by a closure.
     * @param string the value to append to the attribute
     * @param boolean the argument to enable/disable value
     */
    public function setContent(string $content, $isActive = true): self {
        $this->contents = array();
        $this->addContent($content,$isActive);
        return $this;
    }

    /**
     * Function that execute an argument to enable/disable value for argument,
     * @param  If the arg is callable then return its execution else return the value
     * @return bool in any case this function must be return true or false 
     */
    private function callback($arg): bool {
        if (!is_callable($arg))
            return $arg;
        return $arg();
    }

    /**
     * Return the name of the attribute
     * @return string the name of the attribute
     */
    public function name(): string {
        return $this->name;
    }

    /**
     * @return array that contains values of this attribute
     */
    public function contents(): array {
        return $this->contents;
    }

    /**
     * Return values contained in this attribute in one string
     * ex: class="foo foo-bar"  become  foo foo-bar
     * @return string values
     */
    public function value(): string {
        $output="";
        foreach ($this->contents as $value => $isActive)
            $isActive ? $output.=$value.' ' : null;
        return rtrim($output);
    }

}

?>