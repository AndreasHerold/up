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
class Application_Model_User extends Zend_Db_Table_Abstract
{
    /**
     * Key für den PrimaryKey
     *
     * @var string
     */
    protected $_primary = 'id';

    /**
     * Name der Tabelle
     *
     * @var string
     */
    protected $_name = 'user';

    /**
     * Methode um einen neuen Benutzer zu speichern
     *
     * @param array $data
     *
     * @return mixed
     */
    public function insert(array $data)
    {
        $params = array(
            'userName' => $data['username'],
            'userPassword' => md5($data['password']),
            'registerDate' => date('Y-m-d H:i:s')
        );
        return parent::insert($params);
    }
}
 