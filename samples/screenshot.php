<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This sample will capture a screenshot of google and output it to a file
 */

require_once __DIR__.'/../vendor/autoload.php';

$target = __DIR__.'/screenshot.png';

$client  = new WebDriver\Client('http://localhost:4444/wd/hub');
$session = $client->createSession(new WebDriver\Capabilities('firefox'));

$session->navigation()->open('http://google.fr');

file_put_contents($target, $session->screenshot());

$session->close();