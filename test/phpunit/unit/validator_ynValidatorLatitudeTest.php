<?php
require_once dirname(__FILE__).'/../bootstrap/unit.php';

class unit_validator_ynValidatorLatitudeTest extends sfPHPUnitBaseTestCase
{
  protected function getGoodInputs()
  {
    return array(
      array( 'deg' => 50, 'min' => 12, 'sec' => 14, 'dir' => 'N' ),
      array( 'deg' => 50, 'min' => 12, 'sec' => 14, 'dir' => 'S' ),
      array( 'deg' => 50, 'min' => 12, 'dir' => 'S' ),
      array( 'deg' => 50, 'dir' => 'S' ),
      array( 'deg' => 50, 'min' => '', 'sec' => '', 'dir' => 'N' ),
      50.456789,
      50,
      -50.456789,
      -50
    );
  }

  protected function getBadInputs()
  {
    return array(
      'missing dir' => array( 'deg' => 50, 'min' => 12, 'sec' => 14 ),
      'missing deg' => array( 'min' => 12, 'sec' => 14, 'dir' => 'N' ),
      'missing min' => array( 'deg' => 12, 'sec' => 14, 'dir' => 'N' ),
      'invalid n/s 1' => array( 'deg' => 50, 'min' => 12, 'sec' => 14, 'dir' => 'F' ),
      'invalid n/s 2' => array( 'deg' => 50, 'min' => 12, 'sec' => 14, 'dir' => 'E' ),
      'non-integer member' => array( 'deg' => 50, 'min' => 12, 'sec' => 14.2, 'dir' => 'N' ),
      'out of range' => array( 'deg' => 100, 'min' => 12, 'sec' => 14, 'dir' => 'N' ),
    );
  }

  public function testClean()
  {
    $validator = new ynValidatorLatitude();

    foreach ( $this->getGoodInputs() as $value ) {
      $clean = $validator->clean( $value );
      $this->assertType( PHPUnit_Framework_Constraint_IsType::TYPE_NUMERIC, $clean );

      if ( is_array( $value ) ) {
        switch( $value['dir'] ) {
          case 'N':
            $multiplier = 1;
            break;
          case 'S':
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
