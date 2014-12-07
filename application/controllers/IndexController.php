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
class IndexController extends Zend_Controller_Action
{
    /**
     * Initialmethode
     */
    public function init()
    {
        $auth = Zend_Auth::getInstance();
        if (!$auth->hasIdentity()) {
            $this->redirect('/auth/login/');
    }
        $this->view->isLoggedIn = $auth->hasIdentity();
    }

    /**
     * ActionHandler für die Startseite
     * hier werden wenn angemeldet, die ganzen Dateien angezeigt
     */
    public function indexAction()
    {
        $fileModel = new Application_Model_Files();
        $files = $fileModel->getUserFiles();

        $this->view->files = $files;
    }

    /**
     * ActionHandler für den Uploadvorgang
     * hiermit ist es möglich, Dateien hochzuladen
     */
    public function uploadAction()
    {
        $uploaderForm = new Application_Form_Upload();

        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if ($uploaderForm->isValid($formData)) {

                $originalFilename = pathinfo($uploaderForm->file->getFileName());
                $newFilename = 'upload-' . uniqid() . '.' . $originalFilename['extension'];
                $uploaderForm->file->addFilter('Rename', $newFilename);

                try {
                    $uploaderForm->file->receive();
                    //upload complete!

                    $file = new Application_Model_Files();
                    $auth = Zend_Auth::getInstance();
                    $file->setUserId($auth->getIdentity()->id)
                        ->setFileName($newFilename)
                        ->setUploadName($originalFilename['basename']);
                    $file->save();
                } catch (Exception $e) {
                    //error
                }

            } else {
                $uploaderForm->populate($formData);
            }
        }

        $this->view->form = $uploaderForm;
    }

    /**
     * ActionHandler für den Löschvorgang
     * hiermit ist es möglich, Dateien zu speichern
     */
    public function deleteAction()
    {
        $confirmForm = new Application_Form_Confirm();
        $fileId = $this->getParam('id', false);

        if (!$fileId) {
            $this->redirect('/');
        }
        $fileModel = new Application_Model_Files();
        $files = $fileModel->getUserFiles($fileId);#

        $file = $files->current();
        if (!$file) {
            $this->redirect('/');
        }
        $this->view->file = $file;


        if ($this->getRequest()->isPost()) {
            $formData = $this->getRequest()->getPost();
            if (isset($formData['yes'])) {
                $fileModel->delete($fileId);
            }
            $this->redirect('/');
        }

        $this->view->confirmForm = $confirmForm;
    }

    /**
     * ActionHandler um die Datei herunterzuladen
     */
    public function downloadAction()
    {
        $fileId = $this->getParam('id', false);

        if (!$fileId) {
            $this->redirect('/');
        }
        $fileModel = new Application_Model_Files();
        $file = $fileModel->getUserFiles($fileId)->current();

        if (!$file) {
            $this->redirect('/');
        }
        $filePath = APPLICATION_PATH . Application_Model_Files::FILE_PATH . $file->filename;
        $fileInfo = pathinfo($filePath);
        header('Content-Type: ' . $fileInfo['extension']);
        header('Content-Disposition: attachment; filename="' . $file->uploadname . '"');
        readfile($filePath);

        // disable layout and view
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }
}

