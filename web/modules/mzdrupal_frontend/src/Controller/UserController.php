<?php

namespace Drupal\mzdrupal_frontend\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends ControllerBase {

  private $current_user;

  /**
   * {@inheritdoc}
   */
  public function __construct(AccountProxyInterface $current_user) {
    $this->current_user = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // We assume the user is logged in, as that's a requirement for this route.
    $current_user = $container->get('current_user');
    return new static($current_user);
  }

  /**
   * Generate.
   *
   * @return string
   */
  public function info() {
    $user = User::load($this->current_user->id());

    $response = [
      'name' => $user->getUsername(),
    ];

    return new JsonResponse($response);
  }

}
