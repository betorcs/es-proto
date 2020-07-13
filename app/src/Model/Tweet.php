<?php

namespace App\Model;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;


/**
 * @ApiResource
 */
class Tweet {

    /**
     * @ApiProperty(identifier=true)
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $author;
    /**
     * @var \DateTimeInterface
     */
    public $data;
    /**
     * @var string
     */
    public $message;

}