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
    return ($Input ?  filter_input(INPUT_POST,$Input) : filter_input_array(INPUT_POST));
  }

  static function File(string $Input){
    return (!empty($_FILES[$Input]['name']) ? $_FILES[$Input] : null);
  }

}