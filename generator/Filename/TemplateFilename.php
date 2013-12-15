<?php
class TemplateFilename
{
    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __DIR__ . '/../templates/' . $this->name . '.tpl';
    }
}
