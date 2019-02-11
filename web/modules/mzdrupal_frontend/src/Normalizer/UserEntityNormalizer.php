<?php

namespace Drupal\mzdrupal_frontend\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;

/**
 * Converts the Drupal entity object structures to a normalized array.
 */
class UserEntityNormalizer extends ContentEntityNormalizer {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var string
   */
  protected $supportedInterfaceOrClass = 'Drupal\user\UserInterface';

  /**
   * {@inheritdoc}
   */
  public function normalize($entity, $format = NULL, array $context = []) {
    $attributes = parent::normalize($entity, $format, $context);

    // Process each user attribute.
    foreach ($attributes as $field => &$field_values) {
      // Process each value on multivalue fields.
      foreach ($field_values as $field_index => &$field_value) {
        // If the only key found is "value", bring it one level up.
        if (!array_diff_key(array_flip([ 'value' ]), $field_value)) {
          $field_value = $field_value['value'];
        }
      }

      // If there are not multiple values, use the first value or FALSE.
      if (count($field_values) < 2) {
        $field_values = reset($field_values);
      }
    }

    return $attributes;
  }
}
