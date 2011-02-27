<?php

class ynWidgetFormLatitude extends ynWidgetFormLatitudeLongitude
{
  protected function directionFromDecimal( $decimal )
  {
    if ( $decimal > 0 ) {
      return 'N';
    }
    else {
      return 'S';
    }
  }

  protected function getMax()
  {
    return 90;
  }

  protected function renderDirWidget($name, $value, $attributes)
  {
    $options['choices'] = array(
      '' => '',
      'N' => 'N',
      'S' => 'S',
    );

    $widget = new sfWidgetFormSelect($options, $attributes);
    return $widget->render($name, $value);
  }
}
