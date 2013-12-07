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

$states     = array();
$operations = array();

foreach ($dom->query('states/state') as $state) {
    /** @var TheSeer\fDOM\fDOMElement $state */
    $states[$state->getAttribute('name')] = array(
        'transitions' => array(),
        'query'       => $state->getAttribute('query')
    );
}

foreach ($dom->query('transitions/transition') as $transition) {
    /** @var TheSeer\fDOM\fDOMElement $transition*/
    $from         = $transition->getAttribute('from');
    $to           = $transition->getAttribute('to');
    $operation    = $transition->getAttribute('operation');
    $operations[] = $operation;

    $states[$from]['transitions'][$operation] = $to;
}

$abstractBuffer  = "<?php\nabstract class AbstractDoorState implements DoorInterface\n{";
$interfaceBuffer = "<?php\ninterface DoorInterface\n{";

foreach ($operations as $operation) {
    $abstractBuffer  .= sprintf("\n    /**\n     * @throws IllegalStateTransitionException\n     */\n    public function %s()\n    {\n        throw new IllegalStateTransitionException;\n    }\n", $operation);
    $interfaceBuffer .= sprintf("\n    public function %s();", $operation);
}

$abstractBuffer  .= "}";
$interfaceBuffer .= "\n}";

file_put_contents(__DIR__ . '/../src/AbstractDoorState.php', $abstractBuffer);
file_put_contents(__DIR__ . '/../src/DoorInterface.php', $interfaceBuffer);

foreach ($states as $state => $data) {
    $buffer = sprintf("<?php\nclass %s extends AbstractDoorState\n{", $state);

    foreach ($data['transitions'] as $operation => $to) {
        $buffer .= sprintf("\n    /**\n     * @return %s\n     */\n    public function %s()\n    {\n        return new %s;\n    }\n", $to, $operation, $to);
    }

    $buffer .= "}";

    file_put_contents(__DIR__ . '/../src/' . $state . '.php', $buffer);
}

$buffer = "<?php\nclass Door implements DoorInterface\n{\n    /**\n     * @var DoorInterface\n     */\n    private \$state;\n\n    public function __construct(DoorInterface \$state)\n    {\n        \$this->setState(\$state);\n    }\n";

foreach ($operations as $operation) {
    $buffer .= sprintf("\n    /**\n     * @throws IllegalStateTransitionException\n     */\n    public function %s()\n    {\n        \$this->setState(\$this->state->%s());\n    }\n", $operation, $operation);
}

foreach ($states as $state => $data) {
    $buffer .= sprintf("\n    /**\n     * @return bool\n     */\n    public function %s()\n    {\n        return \$this->state instanceof %s;\n    }\n", $data['query'], $state);
}

$buffer .= "\n    private function setState(DoorInterface \$state)\n    {\n        \$this->state = \$state;\n    }\n}";

file_put_contents(__DIR__ . '/../src/Door.php', $buffer);
