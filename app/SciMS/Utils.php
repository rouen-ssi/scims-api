<?php

namespace SciMS;


class Utils {
  public static function validate($model) {
    $errors = [];
    if (!$model->validate()) {
      foreach ($model->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }
    }

    return $errors;
  }
}