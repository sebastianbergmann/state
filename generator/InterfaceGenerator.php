<?php
class InterfaceGenerator
{
    /**
     * @param array $specification
     * @param string $interfaceName
     */
    public function generate(array $specification, $interfaceName)
    {
        $buffer = sprintf("<?php\ninterface %s\n{", $interfaceName);

        foreach (array_keys($specification['operations']) as $operation) {
            $buffer .= sprintf("\n    public function %s();", $operation);
        }

        $buffer .= "\n}";

        file_put_contents(new CodeFilename($interfaceName), $buffer);
    }
}
