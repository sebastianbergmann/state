<?php
class TestFilename
{
    /**
     * @var string
     */
    private $testName;

    /**
     * @param string $testName
     */
    public function __construct($testName)
    {
        $this->testName = $testName;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __DIR__ . '/../../example/tests/' . $this->testName . '.php';
    }
}
