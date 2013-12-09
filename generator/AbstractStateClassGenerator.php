<?php
class AbstractStateClassGenerator
{
    /**
     * @param array $specification
     * @param string $abstractClassName
     * @param string $interfaceName
     */
    public function generate(array $specification, $abstractClassName, $interfaceName)
    {
        $buffer = sprintf(
            "<?php\nabstract class %s implements %s\n{",
            $abstractClassName,
            $interfaceName
        );

        foreach ($specification['operations'] as $operation => $data) {
            $buffer .= sprintf(
                "\n    /**\n     * @throws IllegalStateTransitionException\n     */\n    public function %s()\n    {\n        throw new IllegalStateTransitionException;\n    }\n",
                $operation
            );
        }

        $buffer .= "}";

        file_put_contents(new CodeFilename($abstractClassName), $buffer);
    }
}
