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
class Application_Form_User extends Zend_Form
{
    /**
     * @throws Zend_Form_Exception
     */
    public function init()
    {
        $this->setMethod('post');

        $this->addElement(
            'text', 'username', array(
                'label' => 'Username:',
                'required' => true,
                'filters'    => array('StringTrim'),
            ));

        $this->addElement('password', 'password', array(
            'label' => 'Passwort:',
            'required' => true,
        ));

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Login',
        ));

    }
} 