<?php

abstract class ynWidgetFormLatitudeLongitude extends sfWidgetForm
{
  /**
   * @param float $deg
   * @return string
   */
  abstract protected function directionFromDecimal( $decimal );
  /**
   * @return int
   */
  abstract protected function getMax();

  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('format', '%deg%&#176; %min%&apos; %sec%&quot; %dir%');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $value = $this->coordinatesFromRaw( $value );

    $fields['%deg%'] = $this->renderDegWidget($name.'[deg]', $value['deg'], array_merge($this->attributes, $attributes));
    $fields['%min%'] = $this->renderMinSecWidget($name.'[min]', $value['min'], array_merge($this->attributes, $attributes));
    $fields['%sec%'] = $this->renderMinSecWidget($name.'[sec]', $value['sec'], array_merge($this->attributes, $attributes));
    $fields['%dir%'] = $this->renderDirWidget($name.'[dir]', $value['dir'], array_merge($this->attributes, $attributes));

    return strtr($this->getOption('format'), $fields);
  }

  protected function coordinatesFromRaw( $value )
  {
    // convert value to an array
    $default = array('deg' => null, 'min' => null, 'sec' => null, 'dir' => null);

    if ( $value === null ) {
      return $default;
    }
    elseif ( is_array( $value ) ) {
      $value = array_merge($default, $value);
    }
    else {
      $old_value = $value;

      $value = array();

      $value['dir'] = $this->directionFromDecimal( $old_value );

      $old_value = abs( $old_value );

      $value['deg'] = floor( $old_value );
      $value['min'] = floor( ( $old_value - $value['deg'] ) * 60 );
      $value['sec'] = floor( ( $old_value - $value['deg'] - $value['min']/60 ) * 60*60 );
    }

    return $value;
  }

  protected function renderDegWidget($name, $value, $attributes)
  {
    $choices = array_combine( range(0,$this->getMax()),  range(0,$this->getMax()) );
    $options['choices'] = array_merge( array( '' => '' ), $choices );

    $widget = new sfWidgetFormSelect($options, $attributes);
    return $widget->render($name, $value);
  }

  protected function renderMinSecWidget($name, $value, $attributes)
  {
    $choices = array_combine( range(0,59),  range(0,59) );
    $options['choices'] = array_merge( array( '' => '' ), $choices );

    $widget = new sfWidgetFormSelect($options, $attributes);
    return $widget->render($name, $value);
  }

  abstract protected function renderDirWidget($name, $value, $attributes);
}
