<?php
class SpecificationFilename
{
    /**
     * @return string
     */
    public function __toString()
    {
        return __DIR__ . '/../../build/specification.xml';
    }
}
