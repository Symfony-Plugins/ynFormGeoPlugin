<?php
/**
 * This validator accepts either an array such as
 * array( 'deg' => 12, 'min' => 34, 'sec' => 56, 'dir' => 'N' )
 * or a numeric value such as 12.3456
 */
abstract class ynValidatorLatitudeLongitude extends sfValidatorBase
{
  /**
   * return int
   */
  abstract protected function getMax();
  /**
   * @param string $dir
   * @return int
   */
  abstract protected function getMultiplier( $dir );

  protected function configure($options = array(), $messages = array())
  {
    parent::configure( $options, $messages );

    // for select elements: deg, min, sec, dir
    $this->addOption('empty_sub_value', '');
  }

  protected function doClean( $value )
  {
    if ( is_array( $value ) ) {
      $value = $this->arrayToInt( $value );
    }

    if ( $value === $this->getEmptyValue() ) {
      return $value;
    }

    if ( ! is_numeric( $value ) ) {
      throw new sfValidatorError($this, 'invalid');
    }

    if ( abs( $value ) > $this->getMax() ) {
      throw new sfValidatorError($this, 'invalid');
    }

    return $value;
  }

  protected function arrayToInt( array $value )
  {
    $e = $this->getOption('empty_sub_value');

    $empty_values = array(
      'deg' => $e,
      'min' => $e,
      'sec' => $e,
      'dir' => $e,
    );

    $value = array_merge( $empty_values, $value );

    if ( $value['deg'].$value['min'].$value['sec'] === $e ) {
      return $this->getEmptyValue();
    }

    if (
      $value['deg'] === $e
      || $value['dir'] === $e
      || ($value['sec'] !== $e && $value['min'] === $e)
    ) {
      throw new sfValidatorError($this, 'invalid');
    }

    if ( preg_match( '/\D/', $value['deg'].$value['min'].$value['sec'] ) ) {
      // non-integer component
      throw new sfValidatorError($this, 'invalid');
    }

    $ret = ( $value['deg'] + $value['min']/60 + $value['sec']/60/60 )
           * $this->getMultiplier( $value['dir'] );

    return $ret;
  }
}
