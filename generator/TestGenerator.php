<?php
class TestGenerator
{
    /**
     * @param array  $data
     * @param array  $operations
     * @param array  $queries
     * @param array  $states
     * @param string $state
     * @param string $className
     * @param string $abstractState
     */
    public function generate(array $data, array $operations, array $queries, array $states, $state, $className, $abstractState)
    {
        $buffer        = '';
        $state         = substr($state, 0, strlen($state) - strlen('State'));
        $abstractState = substr($abstractState, 0, strlen($abstractState) - strlen('State'));
        $template      = file_get_contents(new TemplateFilename('TestMethodQuery'));

        foreach ($queries as $query) {
            if ($query == $data['query']) {
                $assert = 'True';
                $test   = ucfirst($query);
            } else {
                $assert = 'False';
                $test   = str_replace('Is', 'IsNot', ucfirst($query));
            }

            $buffer .= str_replace(
                array(
                    '___CLASS___',
                    '___OBJECT___',
                    '___TEST___',
                    '___ASSERT___',
                    '___QUERY___'
                ),
                array(
                    $className,
                    strtolower($className),
                    $test,
                    $assert,
                    $query
                ),
                $template
            );
        }

        $operationTemplate          = file_get_contents(new TemplateFilename('TestMethodOperation'));
        $operationExceptionTemplate = file_get_contents(new TemplateFilename('TestMethodOperationException'));

        foreach ($operations as $operation => $names) {
            if (isset($data['transitions'][$operation])) {
                $template = $operationTemplate;
                $test     = ucfirst($names['allowed']);
                $_state   = $state;
                $query    = $states[$data['transitions'][$operation]]['query'];
            } else {
                $template = $operationExceptionTemplate;
                $test     = ucfirst($names['disallowed']);
                $_state   = $abstractState;
                $query    = '';
            }

            $buffer .= str_replace(
                array(
                    '___CLASS___',
                    '___OBJECT___',
                    '___STATE___',
                    '___TEST___',
                    '___OPERATION___',
                    '___QUERY___'
                ),
                array(
                    $className,
                    strtolower($className),
                    $_state,
                    $test,
                    $operation,
                    $query
                ),
                $template
            );
        }

        file_put_contents(
            new TestFilename($state . 'Test'),
            str_replace(
                array(
                    '___STATE___',
                    '___CLASS___',
                    '___OBJECT___',
                    '___METHODS___'
                ),
                array(
                    $state,
                    $className,
                    strtolower($className),
                    $buffer
                ),
                file_get_contents(new TemplateFilename('TestClass'))
            )
        );
    }
}
