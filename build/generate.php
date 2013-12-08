#!/usr/bin/env php
<?php
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die(
        'You need to set up the project dependencies using the following commands:' . PHP_EOL .
        'wget https://getcomposer.org/composer.phar' . PHP_EOL .
        'php composer.phar install' . PHP_EOL
    );
}

require __DIR__ . '/../vendor/autoload.php';

use TheSeer\fDOM\fDOMDocument;

$dom = new fDOMDocument;
$dom->load(__DIR__ . '/specification.xml');

$class         = $dom->queryOne('configuration/class')->getAttribute('name');
$interface     = $dom->queryOne('configuration/interface')->getAttribute('name');
$abstractClass = $dom->queryOne('configuration/abstractClass')->getAttribute('name');

$states = array();

foreach ($dom->query('states/state') as $state) {
    /** @var TheSeer\fDOM\fDOMElement $state */
    $states[$state->getAttribute('name')] = array(
        'transitions' => array(),
        'query'       => $state->getAttribute('query')
    );
}

$operations = array();

foreach ($dom->query('transitions/transition') as $transition) {
    /** @var TheSeer\fDOM\fDOMElement $transition*/
    $from         = $transition->getAttribute('from');
    $to           = $transition->getAttribute('to');
    $operation    = $transition->getAttribute('operation');
    $operations[] = $operation;

    $states[$from]['transitions'][$operation] = $to;
}

$abstractBuffer  = sprintf("<?php\nabstract class %s implements %s\n{", $abstractClass, $interface);
$interfaceBuffer = sprintf("<?php\ninterface %s\n{", $interface);

foreach ($operations as $operation) {
    $abstractBuffer  .= sprintf("\n    /**\n     * @throws IllegalStateTransitionException\n     */\n    public function %s()\n    {\n        throw new IllegalStateTransitionException;\n    }\n", $operation);
    $interfaceBuffer .= sprintf("\n    public function %s();", $operation);
}

$abstractBuffer  .= "}";
$interfaceBuffer .= "\n}";

file_put_contents(__DIR__ . '/../src/' . $abstractClass . '.php', $abstractBuffer);
file_put_contents(__DIR__ . '/../src/' . $interface . '.php', $interfaceBuffer);

foreach ($states as $state => $data) {
    $buffer = sprintf("<?php\nclass %s extends %s\n{", $state, $abstractClass);

    foreach ($data['transitions'] as $operation => $to) {
        $buffer .= sprintf("\n    /**\n     * @return %s\n     */\n    public function %s()\n    {\n        return new %s;\n    }\n", $to, $operation, $to);
    }

    $buffer .= "}";

    file_put_contents(__DIR__ . '/../src/' . $state . '.php', $buffer);
}

$buffer = sprintf("<?php\nclass %s implements %s\n{\n    /**\n     * @var %s\n     */\n    private \$state;\n\n    public function __construct(%s \$state)\n    {\n        \$this->setState(\$state);\n    }\n", $class, $interface, $interface, $interface);

foreach ($operations as $operation) {
    $buffer .= sprintf("\n    /**\n     * @throws IllegalStateTransitionException\n     */\n    public function %s()\n    {\n        \$this->setState(\$this->state->%s());\n    }\n", $operation, $operation);
}

foreach ($states as $state => $data) {
    $buffer .= sprintf("\n    /**\n     * @return bool\n     */\n    public function %s()\n    {\n        return \$this->state instanceof %s;\n    }\n", $data['query'], $state);
}

$buffer .= sprintf("\n    private function setState(%s \$state)\n    {\n        \$this->state = \$state;\n    }\n}", $interface);

file_put_contents(__DIR__ . '/../src/' . $class . '.php', $buffer);
