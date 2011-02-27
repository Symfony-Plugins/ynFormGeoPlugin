<?php
require_once dirname(__FILE__).'/../bootstrap/unit.php';

class unit_validator_ynValidatorLongitudeTest extends sfPHPUnitBaseTestCase
{
  protected function getGoodInputs()
  {
    return array(
      array( 'deg' => 150, 'min' => 12, 'sec' => 14, 'dir' => 'E' ),
      array( 'deg' => 150, 'min' => 12, 'sec' => 14, 'dir' => 'W' ),
      array( 'deg' => 150, 'min' => 12, 'dir' => 'E' ),
      array( 'deg' => 150, 'dir' => 'E' ),
      array( 'deg' => 150, 'min' => '', 'sec' => '', 'dir' => 'E' ),
      50.456789,
      50,
      -50.456789,
      -50
    );
  }

  protected function getBadInputs()
  {
    return array(
      'missing dir' => array( 'deg' => 150, 'min' => 12, 'sec' => 14 ),
      'missing deg' => array( 'min' => 12, 'sec' => 14, 'dir' => 'E' ),
      'missing min' => array( 'deg' => 12, 'sec' => 14, 'dir' => 'E' ),
      'invalid e/w 1' => array( 'deg' => 150, 'min' => 12, 'sec' => 14, 'dir' => 'F' ),
      'invalid e/w 2' => array( 'deg' => 150, 'min' => 12, 'sec' => 14, 'dir' => 'N' ),
      'non-integer member' => array( 'deg' => 150, 'min' => 12, 'sec' => 14.2, 'dir' => 'E' ),
      'out of range' => array( 'deg' => 200, 'min' => 12, 'sec' => 14, 'dir' => 'E' ),
    );
  }

  public function testClean()
  {
    $validator = new ynValidatorLongitude();

    foreach ( $this->getGoodInputs() as $value ) {
      $clean = $validator->clean( $value );
      $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_NUMERIC, $clean );

      if ( is_array( $value ) ) {
        switch( $value['dir'] ) {
          case 'E':
            $multiplier = 1;
            break;
          case 'W':
            $multiplier = -1;
            break;
        }

        $this->assertGreaterThanOrEqual( $value['deg'], abs($clean), 'value makes sense' );
        $this->assertLessThanOrEqual( $value['deg']+1, abs($clean), 'value makes sense' );
        $this->assertGreaterThanOrEqual( 0, $clean * $multiplier, 'direction correct' );
      }
    }

    foreach ( $this->getBadInputs() as $message => $value ) {
      try {
        $validator->clean( $value );
        $this->fail( $message );
      }
      catch( sfValidatorError $e ) {
        // ignore
      }
    }
  }
}