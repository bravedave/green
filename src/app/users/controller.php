<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace green\users;

use dvc;
use Json;
use strings;

class controller extends \Controller {
  protected $label = config::label;
  
  protected function before() {
    config::green_users_checkdatabase();
    $this->viewPath[] = __DIR__ . '/views/';
    parent::before();
  }

  protected function postHandler() {
    $action = $this->getPost('action');

    if ('delete' == $action) {
      if (($id = (int)$this->getPost('id')) > 0) {
        $dao = new dao\users;
        $dao->delete($id);

        Json::ack($action);
      } else {
        Json::nak($action);
      }
    } elseif ('save-user' == $action) {
      $a = [
        'updated' => \db::dbTimeStamp(),
        'name' => $this->getPost('name'),
        'mobile' => preg_replace('@[^0-9]@', '', $this->getPost('mobile')),
        'email' => $this->getPost('email'),
        'group' => $this->getPost('group'),
        'admin' => $this->getPost('admin'),
        'active' => $this->getPost('active'),
        'birthdate' => '0000-00-00',
        'start_date' => '0000-00-00',

      ];

      if (strtotime($d = $this->getPost('birthdate')) > strtotime('1900-01-01')) {
        $a['birthdate'] = $d;
      }

      if (strtotime($d = $this->getPost('start_date')) > strtotime('1900-01-01')) {
        $a['start_date'] = $d;
      }

      if (config::green_email_enable()) {
        $a['mail_type'] = $this->getPost('mail_type');
        $a['mail_server'] = $this->getPost('mail_server');
        $a['mail_account'] = $this->getPost('mail_account');
        if ($pass = $this->getPost('mail_password')) {
          $a['mail_password'] = \dvc\bCrypt::crypt($pass);
        }
      }

      if (($id = (int)$this->getPost('id')) > 0) {
        $dao = new dao\users;
        $dao->UpdateByID($a, $id);
        Json::ack($action)
          ->add('id', $id);
      } else {
        if ($a['name']) {
          $dao = new dao\users;
          $a['created'] = $a['updated'];
          $id = $dao->Insert($a);
          Json::ack($action)
            ->add('id', $id);
        } else {
          Json::nak($action);
        }
      }
    } elseif ('set-password' == $action) {
      if (($id = (int)$this->getPost('id')) > 0) {
        if ($password = $this->getPost('password')) {
          $a = [
            'updated' => \db::dbTimeStamp(),
            'password' => password_hash($password, PASSWORD_DEFAULT)

          ];
        }

        $dao = new dao\users;
        $dao->UpdateByID($a, $id);
        Json::ack($action);
      } else {
        Json::nak($action);
      }
    } elseif ('email-verify' == $action) {
      $email_type = $this->getPost('email_type');
      if (!$email_type) $email_type = 'imap';

      if (\in_array($email_type, ['imap', 'exchange'])) {
        $email_server = $this->getPost('email_server');
        $email_account = $this->getPost('email_account');
        $email_password = $this->getPost('email_password');

        if ($email_server && $email_account && $email_password) {
          $creds = new dvc\mail\credentials(
            $email_account,
            $email_password,
            $email_server

          );

          $creds->interface = dvc\mail\credentials::imap;
          if ('exchange' == $email_type) {
            dvc\imap\folders::changeDefaultsToExchange();
          }

          if ($inbox = dvc\mail\inbox::instance($creds)) {
            if ($inbox->verify()) {
              Json::ack($action);
            } else {
              Json::nak(sprintf('fail open - %s', $action));
            }
          } else {
            Json::nak(sprintf('fail - %s', $action));
          }
        } else {
          Json::nak(sprintf('missing param %s', $action));
        }
      } else {
        Json::nak(sprintf('invalid type ($s) - %s', $email_type, $action));
      }
    } else {
      parent::postHandler();
    }
  }

  protected function _index() {
    $dao = new dao\users;
    $this->data = (object)[
      'dataset' => $dao->getAll()

    ];

    $this->render([
      'title' => $this->title = $this->label,
      'primary' => 'report',
      'secondary' => [
        'index-title',
        'index-up',
      ],
      'data' => (object)[
        'searchFocus' => true,
        'pageUrl' => strings::url($this->route)
      ],
    ]);
  }

  function edit($id = 0) {
    $this->data = (object)[
      'title' => $this->title = 'Add User',
      'dto' => new dao\dto\users,
      'creds' => false

    ];

    if ($id = abs((int)$id)) {
      $dao = new dao\users;
      if ($dto = $dao->getByID($id)) {

        $this->data->title = $this->title = sprintf('Edit User %s', $dto->name);
        $this->data->dto = $dto;
        $this->data->creds = $dao->credentials($dto);
        $this->load('edit-users');
      } else {
        $this->load('users-not-found');
      }
    } else {
      $this->load('edit-users');
    }
  }

  function setpassword($id = 0) {
    if ($id = (int)$id) {
      $dao = new dao\users;
      if ($dto = $dao->getByID($id)) {
        $this->data = (object)[
          'title' => $this->title = 'Set Password',
          'dto' => $dto

        ];

        $this->load('set-password');
      } else {
        $this->load('users-not-found');
      }
    } else {
      $this->load('users-not-found');
    }
  }
}
