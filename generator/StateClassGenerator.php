<?php
class StateClassGenerator
{
    /**
     * @param array $data
     * @param string $className
     * @param string $abstractClassName
     */
    public function generate(array $data, $className, $abstractClassName)
    {
        $buffer = sprintf("<?php\nclass %s extends %s\n{", $className, $abstractClassName);

        foreach ($data['transitions'] as $operation => $to) {
            $buffer .= sprintf(
                "\n    /**\n     * @return %s\n     */\n    public function %s()\n    {\n        return new %s;\n    }\n",
                $to,
                $operation,
                $to
            );
        }

        $buffer .= "}";

        file_put_contents(new CodeFilename($className), $buffer);
    }
}
