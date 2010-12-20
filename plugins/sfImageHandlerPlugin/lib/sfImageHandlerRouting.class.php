<?php
class sfImageHandlerRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();

    $routing->prependRoute('image_nodefaults',
      new sfRoute(
        '/image/*',
        array('module' => 'default', 'action' => 'error')
      )
    );

    $routing->prependRoute('image',
      new sfRoute(
        '/cache/img/:format/:width_:height/:filename.:noice',
        array(
          'module' => 'image',
          'action' => 'index',
          'width'  => 'w',
          'height' => 'h',
        ),
        array(
          'filename' => '^[\w\d_\.\-]+$',
          'format'   => '^(jpg|png|gif)$',
          'width'    => '^w[0-9]*$',
          'height'   => '^h[0-9]*$',
          'noice'   => '^(jpg|png|gif)$',
        ),
        array(
          'segment_separators' => array('_', '/', '.'),
          'variable_regex' => '[a-zA-Z0-9]+',
        )
    ));
  }
}
