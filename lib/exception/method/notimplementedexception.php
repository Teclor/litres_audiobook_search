<?php

namespace Exception\Method;


class NotImplementedException extends \BadMethodCallException
{
    protected string $class;
    protected string $method;
    
    public function __construct(string $class, string $method)
    {
        $this->class = $class;
        $this->method = $method;
        $message = "Method $this->method is not implemented in class $this->class";
        parent::__construct($message);
    }

    public function getClass(): string
    {
        return $this->class;
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }
}