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
require __DIR__ . '/autoload.php';

$parser            = new SpecificationParser(new SpecificationFilename);
$specification     = $parser->getSpecification();
$className         = $parser->getClassName();
$abstractClassName = $parser->getAbstractClassName();
$interfaceName     = $parser->getInterfaceClassName();

$generator = new InterfaceGenerator;
$generator->generate($specification, $interfaceName);

$generator = new AbstractStateClassGenerator;
$generator->generate($specification, $abstractClassName, $interfaceName);

$generator = new ClassGenerator;
$generator->generate($specification, $className, $interfaceName);

$generator = new StateClassGenerator;

foreach ($specification['states'] as $state => $data) {
    $generator->generate($data, $state, $abstractClassName);
}
