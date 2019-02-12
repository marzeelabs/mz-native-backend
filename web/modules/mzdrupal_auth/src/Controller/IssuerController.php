<?php

namespace Drupal\mzdrupal_auth\Controller;

use Drupal\jwt\Authentication\Provider\JwtAuth;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class IssuerController.
 *
 * @package Drupal\mzdrupal_auth\Controller
 */
class IssuerController extends ControllerBase {

  /**
   * The JWT Auth Service.
   *
   * @var \Drupal\jwt\Authentication\Provider\JwtAuth
   */
  private $auth;

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\jwt\Authentication\Provider\JwtAuth $auth
   *   The JWT auth service.
   */
  public function __construct(JwtAuth $auth) {
    $this->auth = $auth;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $auth = $container->get('jwt.authentication.jwt');
    return new static($auth);
  }

  /**
   * Generate.
   *
   * @return string
   */
  public function tokenResponse() {
    $response = new \stdClass();
    $token = $this->auth->generateToken();
    if ($token === FALSE) {
      $response->error = "Error. Please set a key in the JWT admin page.";
      return new JsonResponse($response, 500);
    }

    $response->token = $token;
    return new JsonResponse($response);
  }

}
