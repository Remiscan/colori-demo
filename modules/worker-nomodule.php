<?php
header('Content-Type: application/javascript');

require_once $_SERVER['DOCUMENT_ROOT'].'/_common/php/unmodularize-es-module.php';
unmodularize(
  moduleId: 'interface-worker',
  importMapPath: __DIR__.'/../import-map.json'
);