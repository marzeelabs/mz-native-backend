<?php

namespace Drupal\mzdrupal_frontend\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events and restrict access to user.pass route.
 */
class RouteSubscriber extends RouteSubscriberBase {
  /**
   * {@inheritdoc}
   */
  public function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('user.page')) {
      $route->setDefault('_controller', '\Drupal\mzdrupal_frontend\Controller\UserController::userPage');
    }
  }
}
