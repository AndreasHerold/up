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

class Application_Form_Upload extends Zend_Form
{
    /**
     * @throws Zend_Form_Exception
     */
    public function init()
    {
        $this->setMethod('post')
            ->setEnctype('multipart/form-data');

        $uploadPath = APPLICATION_PATH . Application_Model_Files::FILE_PATH;

        $upload = new Zend_Form_Element_File('file');
        $upload->setDestination($uploadPath)
            ->setRequired(true)
            ->setLabel('Der Datei Upload:');

        $this->addElement($upload);

        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Upload',
        ));

    }
} 