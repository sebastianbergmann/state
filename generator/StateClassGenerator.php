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
        $buffer   = '';
        $template = file_get_contents(new TemplateFilename('StateClassMethod'));

        foreach ($data['transitions'] as $operation => $to) {
            $buffer .= str_replace(
                array(
                    '___STATE___',
                    '___METHOD___'
                ),
                array(
                    $to,
                    $operation
                ),
                $template
            );
        }

        file_put_contents(
            new CodeFilename($className),
            str_replace(
                array(
                    '___CLASS___',
                    '___ABSTRACT___',
                    '___METHODS___'
                ),
                array(
                    $className,
                    $abstractClassName,
                    $buffer
                ),
                file_get_contents(new TemplateFilename('StateClass'))
            )
        );
    }
}
