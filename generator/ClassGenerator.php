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
        $buffer   = '';
        $template = file_get_contents(new TemplateFilename('ClassOperation'));

        foreach (array_keys($specification['operations']) as $operation) {
            $buffer .= str_replace('___METHOD___', $operation, $template);
        }

        $template = file_get_contents(new TemplateFilename('ClassQuery'));

        foreach ($specification['states'] as $state => $data) {
            $buffer .= str_replace(
                array(
                    '___METHOD___',
                    '___STATE___'
                ),
                array(
                    $data['query'],
                    $state
                ),
                $template
            );
        }

        file_put_contents(
            new CodeFilename($className),
            str_replace(
                array(
                    '___CLASS___',
                    '___INTERFACE___',
                    '___METHODS___'
                ),
                array(
                    $className,
                    $interfaceName,
                    $buffer
                ),
                file_get_contents(new TemplateFilename('Class'))
            )
        );
    }
}
