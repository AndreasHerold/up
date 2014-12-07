<?php
/**
 * Up
 *
 * @category    Application
 * @package     Application_Model
 * @copyright   Copyright (c) 2014 Unister GmbH
 * @author      Andreas Herold <andreas.herold@googlemail.com>
 * @version     $Id$
 */

/**
 * Model für den Benutzer
 *
 * @category    Application
 * @package     Application_Model
 * @copyright   Copyright (c) 2014 Andreas Herold
 */

class Application_Form_Confirm extends Zend_Form
{
    /**
     * @throws Zend_Form_Exception
     */
    public function init()
    {
        $this->setMethod('post');

        $yes = new Zend_Form_Element_Submit('yes');
        $yes->setLabel('Ja');

        $no = new Zend_Form_Element_Submit('no');
        $no->setLabel('nein');

        $this->addElements(array($yes, $no));
    }
} 