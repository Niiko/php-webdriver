<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * This sample goes to a page and displays it URL.
 */

require_once __DIR__.'/../vendor/autoload.php';

$client  = new WebDriver\Client('http://localhost:4444/wd/hub');
$session = $client->createSession(new WebDriver\Capabilities('firefox'));

$session->navigation()->open('http://www.google.fr');

echo sprintf("The current URL is: %s\n", $session->navigation()->getUrl());

$session->close();
