<?php

namespace Drupal\mzdrupal_auth\EventSubscriber;

use Drupal\jwt\Authentication\Event\JwtAuthValidateEvent;
use Drupal\jwt\Authentication\Event\JwtAuthValidEvent;
use Drupal\jwt\Authentication\Event\JwtAuthEvents;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\user\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ConsumerSubscriber.
 *
 * @package Drupal\mzdrupal_auth
 */
class ConsumerSubscriber implements EventSubscriberInterface {

  /**
   * A User Interface.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager service.
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[JwtAuthEvents::VALIDATE][] = array('validate');
    $events[JwtAuthEvents::VALID][] = array('loadUser');

    return $events;
  }

  /**
   * Validates that a uid is present in the JWT.
   *
   * This validates the format of the JWT and validate the uid is a
   * valid uid in the system.
   *
   * @param \Drupal\jwt\Authentication\Event\JwtAuthValidateEvent $event
   *   A JwtAuth event.
   */
  public function validate(JwtAuthValidateEvent $event) {
    $token = $event->getToken();

    $uid = $token->getClaim(['drupal', 'uid']);
    if (!$uid) {
      $this->invalidate($event);
    }

    $user = User::load($uid);
    if (!$user) {
      $this->invalidate($event);
    }

    if (!$user->isActive()) {
      $this->invalidate($event);
    }
  }

  private function invalidate(JwtAuthValidateEvent $event) {
    $event->invalidate('Invalid session token.');
  }

  /**
   * Load and set a Drupal user to be authentication based on the JWT's uid.
   *
   * @param \Drupal\jwt\Authentication\Event\JwtAuthValidEvent $event
   *   A JwtAuth event.
   */
  public function loadUser(JwtAuthValidEvent $event) {
    $token = $event->getToken();
    $uid = $token->getClaim([ 'drupal', 'uid' ]);
    $user = User::load($uid);
    $event->setUser($user);
  }

}
