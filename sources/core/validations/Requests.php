<?php 

/**
 * @author Gabriel Teixeira
 */

namespace Sources\Core\Validations;

trait Requests{

  static function Get(string $Input){
    return strip_tags(filter_input(INPUT_GET,$Input));
  }

  static function Post(string $Input = null){
    return strip_tags(($Input ? filter_input(INPUT_POST,$Input) : filter_input_array(INPUT_POST)));
  }

}