<?php 

/**
 * @author Gabriel Teixeira
 */

namespace Sources\Classes\Validations;

trait Requests{

  static function Get(string $Input){
    return strip_tags(filter_input(INPUT_GET,$Input));
  }

}