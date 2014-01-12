<?php
class AbstractStateClassGenerator
{
    /**
     * @param array  $operations
     * @param string $abstractClassName
     * @param string $interfaceName
     */
    public function generate(array $operations, $abstractClassName, $interfaceName)
    {
        $buffer   = '';
        $template = file_get_contents(new TemplateFilename('AbstractStateClassMethod'));

        foreach ($operations as $operation => $data) {
            $buffer .= str_replace(
                '___METHOD___',
                $operation,
                $template
            );
        }

        file_put_contents(
            new CodeFilename($abstractClassName),
            str_replace(
                array(
                    '___ABSTRACT___',
                    '___INTERFACE___',
                    '___METHODS___'
                ),
                array(
                    $abstractClassName,
                    $interfaceName,
                    $buffer
                ),
                file_get_contents(new TemplateFilename('AbstractStateClass'))
            )
        );
    }
}
