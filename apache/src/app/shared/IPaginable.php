<?php
namespace App\Shared;
/**
 * Description of IPaginable
 *
 * @author mochiwa
 */
interface IPaginable {
    function getForPagination(int $current,int $max) : array;
    /**
     * Should return the count of data;
     */
    function dataCount(): int;
}
