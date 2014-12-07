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
 * Model zum Speichern der Informationen zur abgelegten Datei
 *
 * @category    Application
 * @package     Application_Model
 * @copyright   Copyright (c) 2014 Andreas Herold
 */
class Application_Model_Files extends Zend_Db_Table_Abstract
{
    const FILE_PATH = '\..\public\uploads\\';
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
    protected $_name = 'files';

    /**
     * @var int
     */
    protected $_userId;
    /**
     * @var string
     */
    protected $_fileName;
    /**
     * @var string
     */
    protected $_uploadName;

    /**
     * @var int
     */
    protected $_fileId;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * @param int $userId
     *
     * @return Application_Model_Files
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUploadName()
    {
        return $this->_uploadName;
    }

    /**
     * @param string $uploadedName
     *
     * @return Application_Model_Files
     */
    public function setUploadName($uploadedName)
    {
        $this->_uploadName = $uploadedName;
        return $this;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->_fileName;
    }

    /**
     * @param string $fileName
     *
     * @return Application_Model_Files
     */
    public function setFileName($fileName)
    {
        $this->_fileName = $fileName;
        return $this;
    }

    /**
     * @return int
     */
    public function getFileId()
    {
        return $this->_fileId;
    }

    /**
     * @param int $fileId
     *
     * @return Application_Model_Files
     */
    public function setFileId($fileId)
    {
        $this->_fileId = $fileId;
        return $this;
    }


    /**
     * Methode um einen neuen Benutzer zu speichern
     *
     *
     * @return mixed
     */
    public function save()
    {
        $params = array(
            'user_id' => $this->getUserId(),
            'filename' => $this->getFileName(),
            'uploadname' => $this->getUploadName(),
            'creationDate' => date('Y-m-d H:i:s')
        );
        return parent::insert($params);
    }

    /**
     * Lädt die Dateien eines Benutzers
     * @param int $fileId Die Id einer Datei
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function getUserFiles($fileId = null)
    {

        $userId = Zend_Auth::getInstance()->getIdentity()->id;
        $select = $this->select()->where('user_id = ?', $userId);
        if (!empty($fileId)) {
            $select->where('id = ?', $fileId);
        }

        $result = $this->fetchAll($select);

        return $result;
    }

    /**
     * Löscht die Datei im Dateisystem und den Eintrag in der DB
     *
     * @param int $fileId Die Id der Datei die gelöscht werden soll
     *
     * @return int
     */
    public function delete($fileId)
    {
        $file = $this->getUserFiles($fileId)->current();
        $filePath = APPLICATION_PATH . Application_Model_Files::FILE_PATH . $file->filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        return parent::delete('id = ' . $fileId);
    }


}
