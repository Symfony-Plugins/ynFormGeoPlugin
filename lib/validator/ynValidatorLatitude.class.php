<?php
/**
 * This validator accepts either an array such as
 * array( 'deg' => 12, 'min' => 34, 'sec' => 56, 'dir' => 'N' )
 * or a numeric value such as 12.3456
 */
class ynValidatorLatitude extends ynValidatorLatitudeLongitude
{
  protected function getMax()
  {
    return 90;
  }

  protected function getMultiplier( $dir )
  {
    switch ( strtolower( $dir ) ) {
      case 'n':
        return 1;

      case 's':
        return -1;

      default:
        throw new sfValidatorError($this, 'invalid');
    }
  }
}
