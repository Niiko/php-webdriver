<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests\Website;

use WebDriver\By;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class BrowserTest extends AbstractTestCase
{
    public function testScreenshot()
    {
        if (!class_exists('Imagick')) {
            $this->markTestSkipped('Imagick is not installed');
        }

        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $data = $browser->screenshot();

        $image = new \Imagick();
        $image->readimageblob($data);

        $this->assertGreaterThan(100, $image->getImageWidth());
        $this->assertGreaterThan(100, $image->getImageHeight());
    }

    public function testExecute()
    {
        $url     = $this->getUrl('index.php');
        $browser = $this->getBrowser();

        $browser->open($url);
        $title = $browser->execute('return document.title;');

        $this->assertEquals('Sample website', $title);
    }

    public function testUrl()
    {
        $url = $this->getUrl('index.php');

        $browser = $this->getBrowser();
        $browser->open($url);

        $this->assertEquals($url, $browser->getUrl());
    }

    public function testBackAndForward()
    {
        $urlA = $this->getUrl('index.php');
        $urlB = $this->getUrl('page.php');

        $browser = $this->getBrowser();
        $browser->open($urlA);
        $browser->open($urlB);
        $browser->back();
        $this->assertRegExp('/index\.php$/', $browser->getUrl());
        $browser->forward();
        $this->assertRegExp('/page\.php$/', $browser->getUrl());
    }

    public function testRefresh()
    {
        $browser = $this->getBrowser();

        $browser->open($this->getUrl('rand.php'));
        $before = $browser->element(By::id('strike'))->text();
        $browser->refresh();
        $after  = $browser->element(By::id('strike'))->text();

        $this->assertTrue($before != $after, "The page was refreshed");
    }

    public function testTitle()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $this->assertEquals('Sample website', $browser->getTitle());
    }

    public function testGetSource()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $source = $browser->getSource();
        $this->assertContains('This comment is only viewable with source code', $source);
        $this->assertContains('<!DOCTYPE html>', $source);
        $this->assertContains('<head>', $source);
        $this->assertContains('<body>', $source);
    }

    public function testElement()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $title = $browser->element(By::css('#danger-zone h2'))->text();

        $this->assertEquals('DANGER ZONE', $title);
    }

    public function testElements()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $elements = $browser->elements(By::css('#pagination a'));

        $this->assertCount(3, $elements);

        $this->assertRegExp('/\?page=1$/', $elements[0]->attribute('href'));
        $this->assertRegExp('/\?page=3$/', $elements[2]->attribute('href'));
    }
}
