#!/usr/bin/env php
<?php
$states      = array();
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

    $operation = $reader->getAttribute('operation');

    if (!isset($transitions[$from])) {
        $transitions[$from] = array();
    }

    $transitions[$from][$operation] = $to;
}

$states = array_unique($states);

foreach ($states as $state) {
    $buffer = sprintf("<?php\nclass %s extends AbstractDoorState\n{", $state);

    foreach ($transitions[$state] as $operation => $to) {
        $buffer .= sprintf("\n    /**\n     * @return %s\n     */\n    public function %s()\n    {\n        return new %s;\n    }\n", $to, $operation, $to);
    }

    $buffer .= "}";

    file_put_contents(__DIR__ . '/../src/' . $state . '.php', $buffer);
}