<?php
use TheSeer\fDOM\fDOMDocument;

class SpecificationParser
{
    /**
     * @var TheSeer\fDOM\fDOMDocument
     */
    private $dom;

    /**
     * @param SpecificationFilename $file
     */
    public function __construct(SpecificationFilename $file)
    {
        $this->dom = new fDOMDocument;
        $this->dom->load($file);
    }

    /**
     * @return array
     */
    public function getSpecification()
    {
        $states = array();

        foreach ($this->dom->query('states/state') as $state) {
            /** @var TheSeer\fDOM\fDOMElement $state */

            $states[$state->getAttribute('name')] = array(
                'transitions' => array(),
                'query'       => $state->getAttribute('query')
            );
        }

        $operations = array();

        foreach ($this->dom->query('operations/operation') as $operation) {
            /** @var TheSeer\fDOM\fDOMElement $operation */

            $operations[$operation->getAttribute('name')] = array(
                'allowed'    => $operation->getAttribute('allowed'),
                'disallowed' => $operation->getAttribute('disallowed')
            );
        }

        foreach ($this->dom->query('transitions/transition') as $transition) {
            /** @var TheSeer\fDOM\fDOMElement $transition */

            $from      = $transition->getAttribute('from');
            $to        = $transition->getAttribute('to');
            $operation = $transition->getAttribute('operation');

            $states[$from]['transitions'][$operation] = $to;
        }

        return array('states' => $states, 'operations' => $operations);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->dom->queryOne('configuration/class')->getAttribute('name');
    }

    /**
     * @return string
     */
    public function getAbstractClassName()
    {
        return $this->dom->queryOne('configuration/abstractClass')->getAttribute('name');
    }

    /**
     * @return string
     */
    public function getInterfaceClassName()
    {
        return $this->dom->queryOne('configuration/interface')->getAttribute('name');
    }
}
