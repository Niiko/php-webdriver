<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Message\Client;

use Buzz\Message\Request;

/**
 * Request message for closing a session.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionCloseRequest extends Request
{
    /**
     * Constructs the request object
     *
     * @param string $sessionId A session ID
     */
    public function __construct($sessionId)
    {
        parent::__construct(Request::METHOD_DELETE, sprintf('/session/%s', $sessionId));
    }
}