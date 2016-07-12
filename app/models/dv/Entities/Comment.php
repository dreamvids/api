<?php
class Comment extends Entry {
    use FlagTrait;
    
    /**
     * @var User
     */
    public $poster;
}