<?php

/**
 * Merges two arrays.
 * Differs from `array_merge_recursive` when trying to resolve scalar conflicts.
 */
function array_merge_deep($left, $right) {
  if (is_array($left) && is_array($right)) {
    $result = [];

    $keys = array_merge(array_keys($left), array_keys($right));

    foreach ($keys as $key) {
      if (isset($left[$key]) && isset($right[$key])) {
        $value = array_merge_deep($left[$key], $right[$key]);
      } else if (isset($left[$key])) {
        $value = $left[$key];
      } else {
        $value = $right[$key];
      }

      $result[$key] = $value;
    }

    return $result;
  }

  return $right;
}


