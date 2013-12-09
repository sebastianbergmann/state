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

use SebastianBergmann\FinderFacade\FinderFacade;

$finder = new FinderFacade(array(__DIR__ . '/../example/src'), array(), array('*.php'));

print 'digraph G {' . PHP_EOL;

foreach ($finder->findFiles() as $file) {
    $file = new PHP_Token_Stream($file);

    foreach ($file->getClasses() as $className => $class) {
        if ($class['parent'] == 'AbstractDoorState') {
            foreach ($class['methods'] as $methodName => $method) {
                if (!in_array($methodName, array('open', 'close', 'lock', 'unlock'))) {
                    continue;
                }

                $annotations = array('return' => array());

                if (preg_match_all('/@(?P<name>[A-Za-z_-]+)(?:[ \t]+(?P<value>.*?))?[ \t]*\r?$/m', $method['docblock'], $matches)) {
                    for ($i = 0; $i < count($matches[0]); ++$i) {
                        $annotations[$matches['name'][$i]][] = $matches['value'][$i];
                    }
                }

                foreach ($annotations['return'] as $return) {
                    printf(
                        '  "%s" -> "%s";' . PHP_EOL,
                        str_replace(array('Door', 'State'), array(' Door', ''), $className),
                        str_replace(array('Door', 'State'), array(' Door', ''), $return)
                    );
                }
            }
        }
    }
}

print '}' . PHP_EOL;
