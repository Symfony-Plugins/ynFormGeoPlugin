<?php
/**
 * This validator accepts either an array such as
 * array( 'deg' => 12, 'min' => 34, 'sec' => 56, 'dir' => 'N' )
 * or a numeric value such as 12.3456
 */
class ynValidatorLongitude extends ynValidatorLatitudeLongitude
{
  protected function getMax()
  {
    return 180;
  }

  protected function getMultiplier( $dir )
  {
    switch ( strtolower( $dir ) ) {
      case 'e':
        return 1;

      case 'w':
        return -1;

      default:
        throw new sfValidatorError($this, 'invalid');
    }
  }
}
