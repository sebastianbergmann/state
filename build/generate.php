#!/usr/bin/env php
<?php
$states      = array();
$operations  = array();
$transitions = array();

$reader = new XMLReader;
$reader->open(__DIR__ . '/transitions.xml');

while ($reader->read()) {
    if ($reader->nodeType !== XMLReader::ELEMENT ||
        $reader->name === 'transitions') {
        continue;
    }

    $from     = $reader->getAttribute('from');
    $states[] = $from;

    $to       = $reader->getAttribute('to');
    $states[] = $to;

    $operation    = $reader->getAttribute('operation');
    $operations[] = $operation;

    if (!isset($transitions[$from])) {
        $transitions[$from] = array();
    }

    $transitions[$from][$operation] = $to;
}

$operations = array_unique($operations);
$states     = array_unique($states);

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

foreach ($states as $state) {
    $buffer = sprintf("<?php\nclass %s extends AbstractDoorState\n{", $state);

    foreach ($transitions[$state] as $operation => $to) {
        $buffer .= sprintf("\n    /**\n     * @return %s\n     */\n    public function %s()\n    {\n        return new %s;\n    }\n", $to, $operation, $to);
    }

    $buffer .= "}";

    file_put_contents(__DIR__ . '/../src/' . $state . '.php', $buffer);
}