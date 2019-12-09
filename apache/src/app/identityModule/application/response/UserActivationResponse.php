<?php

namespace App\Identity\Application\Response;

use App\Shared\Application\AbstractApplicationResponse;

/**
 * Description of UserActivationResponse
 *
 * @author mochiwa
 */
class UserActivationResponse extends AbstractApplicationResponse {
    /**
     * the link to the activation process
     * @var string 
     */
    private $link;
    
    /**
     * instruction to send to user
     * @var string 
     */
    private $instruction;
    
    public function __construct(string $link='',$error=[])
    {
        $this->link=$link;
    }

    public static function of(): self {
        return new self();
    }

    public function withLink(string $link) : self
    {
        $this->link=$link;
        return $this;
    }
    public function withInstruction(string $instruction) : self
    {
        $this->instruction=$instruction;
        return $this;
    }
    
    /**
     * Return true if link is not empty
     * @return bool
     */
    public function hasActivationLink():bool
    {
        return !empty($this->link);
    }
    
    public function getLink() {
        return $this->link;
    }

    public function getInstruction() {
        return $this->instruction;
    }



}
