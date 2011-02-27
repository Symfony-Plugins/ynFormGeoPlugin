<?php

class ynWidgetFormLongitude extends ynWidgetFormLatitudeLongitude
{
  protected function directionFromDecimal( $decimal )
  {
    if ( $decimal > 0 ) {
      return 'E';
    }
    else {
      return 'W';
    }
  }

  protected function getMax()
  {
    return 180;
  }

  protected function renderDirWidget($name, $value, $attributes)
  {
    $options['choices'] = array(
      '' => '',
      'E' => 'E',
      'W' => 'W',
    );

    $widget = new sfWidgetFormSelect($options, $attributes);
    return $widget->render($name, $value);
  }
}
