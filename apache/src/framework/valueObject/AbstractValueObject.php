<?php
/**
 * Description of AbstractValueObject
 *
 * @author mochiwa
 */
abstract class AbstractValueObject {
    abstract function equals(AbstractValueObject $source):bool;
}
