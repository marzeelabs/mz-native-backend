<?php

namespace Drupal\mzdrupal_frontend\Controller;

use Drupal\user\Controller\UserController as UserControllerOriginal;

/**
 * Controller routines for user routes.
 */
class UserController extends UserControllerOriginal {

  /**
   * {@inheritdoc}
   */
  public function userPage() {

    $route_parameters = [
      'user' => $this->currentUser()->id(),
    ];

    $options = [
      'query' => \Drupal::request()->query->all(),
    ];

    return $this->redirect('entity.user.canonical', $route_parameters, $options);
  }

}
