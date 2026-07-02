<?php

namespace App\Legacy;

/**
 * Representa los die()/exit del legacy que respondían texto plano
 * (Content-Type text/html) con HTTP 200.
 */
class LegacyDieException extends \RuntimeException {}
