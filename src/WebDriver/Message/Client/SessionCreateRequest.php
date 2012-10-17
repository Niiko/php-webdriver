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

use WebDriver\Capabilities;

/**
 * Request message for creating a new session.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionCreateRequest extends Request
{
    /**
     * Constructs the request object
     *
     * @param WebDriver\Capabilities $capabilities Capabilities requirements
     */
    public function __construct(Capabilities $capabilities)
    {
        parent::__construct(Request::METHOD_POST, '/session');
        $this->setContent(json_encode(array('desiredCapabilities' => $capabilities->toArray())));
    }
}