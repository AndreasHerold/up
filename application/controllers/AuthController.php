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
 * Controller für die Authentifizierung
 *
 * @category    Application
 * @package     Application_Model
 * @copyright   Copyright (c) 2014 Andreas Herold
 */
class AuthController extends Zend_Controller_Action
{
    /**
     * Initialmethode für den Authcontroller
     */
    public function init()
    {
        $auth = Zend_Auth::getInstance();
        $this->view->isLoggedIn = $auth->hasIdentity();
    }

    /**
     * Action Handler für den Login
     */
    public function loginAction()
    {
        $params = $this->getAllParams();
        $loginForm = new Application_Form_User();
        if ($this->getRequest()->isPost()) {
            if ($loginForm->isValid($params)) {
                $db = Zend_Db_Table::getDefaultAdapter();
                $adapter = new Zend_Auth_Adapter_DbTable(
                    $db,
                    'user',
                    'userName',
                    'userPassword'
                );

                $adapter->setIdentity($loginForm->getValue('username'));
                $adapter->setCredential(md5($loginForm->getValue('password')));
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($adapter);

                if ($result->isValid()) {
                    $user = $adapter->getResultRowObject();
                    $auth->getStorage()->write($user);
                    $this->_helper->FlashMessenger('Erfolgreich angemeldet');
                    $this->redirect('/');
                    return;
                }

            }
        }

        $loginForm->setAction($this->view->url());

        $this->view->loginForm = $loginForm;

    }

    /**
     * ActionHandler zum Registrieren
     */
    public function registerAction()
    {
        $params = $this->getAllParams();
        $loginForm = new Application_Form_User();

        if ($this->getRequest()->isPost()) {
            if ($loginForm->isValid($params)) {
                $user = new Application_Model_User();

                $result = $user->insert($params);
            }
        }

        $loginForm->setAction($this->view->url());

        $loginForm->getElement('submit')->setLabel('Registrieren');

        $this->view->registerForm = $loginForm;
    }

    /**
     * ActionHandler für den Abmeldevorgang
     */
    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();

        if (!$auth->hasIdentity()) {
            $this->redirect('/auth/login/');
        }

    }

}

