<?php
class ClassGenerator
{
    /**
     * @param array $specification
     * @param string $className
     * @param string $interfaceName
     */
    public function generate(array $specification, $className, $interfaceName)
    {
        $buffer = sprintf(
            "<?php\nclass %s implements %s\n{\n    /**\n     * @var %s\n     */\n    private \$state;\n\n    public function __construct(%s \$state)\n    {\n        \$this->setState(\$state);\n    }\n",
            $className,
            $interfaceName,
            $interfaceName,
            $interfaceName
        );

        foreach (array_keys($specification['operations']) as $operation) {
            $buffer .= sprintf(
                "\n    /**\n     * @throws IllegalStateTransitionException\n     */\n    public function %s()\n    {\n        \$this->setState(\$this->state->%s());\n    }\n",
                $operation,
                $operation
            );
        }

        foreach ($specification['states'] as $state => $data) {
            $buffer .= sprintf(
                "\n    /**\n     * @return bool\n     */\n    public function %s()\n    {\n        return \$this->state instanceof %s;\n    }\n",
                $data['query'],
                $state
            );
        }

        $buffer .= sprintf(
            "\n    private function setState(%s \$state)\n    {\n        \$this->state = \$state;\n    }\n}",
            $interfaceName
        );

        file_put_contents(new CodeFilename($className), $buffer);
    }
}
