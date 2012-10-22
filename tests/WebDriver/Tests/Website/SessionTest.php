<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests\Website;

/**
 * Verify the session features.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionTest extends AbstractTestCase
{
    /**
     * Screenshots the page and make sure we have an image.
     */
    public function testScreenshot()
    {
        if (!class_exists('Imagick')) {
            $this->markTestSkipped('Imagick is not installed');
        }

        $session = $this->getSession();
        $session->open($this->getUrl('index.php'));

        $data = $session->screenshot();

        $image = new \Imagick();
        $image->readimageblob($data);

        $this->assertGreaterThan(100, $image->getImageWidth());
        $this->assertGreaterThan(100, $image->getImageHeight());
    }

    /**
     * Tests URL getter and setter.
     */
    public function testUrl()
    {
        $url = $this->getUrl('index.php');

        $session = $this->getSession();
        $session->open($url);

        $this->assertEquals($url, $session->getUrl());
    }

    public function testBackAndForward()
    {
        $urlA = $this->getUrl('index.php');
        $urlB = $this->getUrl('page.php');

        $session = $this->getSession();
        $session->open($urlA);
        $session->open($urlB);
        $session->back();
        $this->assertRegExp('/index\.php$/', $session->getUrl());
        $session->forward();
        $this->assertRegExp('/page\.php$/', $session->getUrl());
    }

    /**
     * Test title getter
     */
    public function testTitle()
    {
        $session = $this->getSession();
        $session->open($this->getUrl('index.php'));

        $this->assertEquals('Sample website', $session->getTitle());
    }

    public function testGetSource()
    {
        $session = $this->getSession();
        $session->open($this->getUrl('index.php'));

        $source = $session->getSource();
        $this->assertContains('This comment is only viewable with source code', $source);
        $this->assertContains('<!DOCTYPE html>', $source);
        $this->assertContains('<head>', $source);
        $this->assertContains('<body>', $source);
    }
}
