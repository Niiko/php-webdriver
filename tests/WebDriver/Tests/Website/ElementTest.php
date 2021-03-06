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
 * Tests the elements API.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class ElementTest extends AbstractTestCase
{
    public function testElementValues()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $title = $browser->element(By::css('#danger-zone h2'));
        $zone = $browser->element(By::css('#danger-zone'));

        $this->assertEquals('DANGER ZONE', $title->getText());
        $this->assertEquals('h2', $title->getTagName());
        $this->assertEquals('danger-zone', $zone->getAttribute('id'));
    }

    public function testType()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('form.php'));

        $textField = $browser->element(By::id('text'))->type('foo');
        $browser->element(By::id('submit'))->click();
    }

    public function testUpload()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('form.php'));
        $tmpFile = tempnam(sys_get_temp_dir(), 'wdtest_');
        file_put_contents($tmpFile, 'foobarbaz');

        $browser->element(By::id('file'))->upload($tmpFile);
        $browser->element(By::id('submit'))->click();

        $this->assertEquals('9', $browser->element(By::id('post-size'))->getText());

        unlink($tmpFile);
    }

    public function testNesting()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $zone = $browser->element(By::id('my-home'));

        $this->assertCount(2, $zone->elements(By::css('.floor')), "Two floors");
        $this->assertCount(2, $zone->elements(By::css('.bathroom')));
        $this->assertCount(1, $zone->element(By::css('.first-floor'))->elements(By::css('.bathroom')));
    }

    public function testElements()
    {
        $browser = $this->getBrowser();
        $browser->open($this->getUrl('index.php'));

        $elements = $browser->elements(By::css('#pagination a'));

        $this->assertCount(3, $elements);

        $this->assertRegExp('/\?page=1$/', $elements[0]->getAttribute('href'));
        $this->assertRegExp('/\?page=3$/', $elements[2]->getAttribute('href'));
    }
}
