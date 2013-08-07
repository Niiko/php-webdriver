<?php

namespace WebDriver\Behat;

use Behat\Behat\Context\BehatContext;
use WebDriver\By;
use WebDriver\Element;
use WebDriver\Exception\ExceptionInterface;
use WebDriver\Exception\NoSuchElementException;

abstract class AbstractWebDriverContext extends BehatContext
{
    protected $baseUrl;
    protected $browserReference;
    protected $browser;

    public function setBrowserInformations($browserReference, $baseUrl)
    {
        if (!is_callable($browserReference)) {
            throw new \InvalidArgumentException(sprintf('Expected a callable, got a "%s".', is_object($browserReference) ? get_class($browserReference) : gettype($browserReference)));
        }

        $this->baseUrl = rtrim($baseUrl, '/');
        $this->browserReference = $browserReference;
        $this->browser = null;
    }

    /**
     * @return WebDriver\Element
     */
    public function getElement(By $by, Element $element = null)
    {
        try {
            $obj = null === $element ? $this->getBrowser() : $element;

            return $obj->element($by);
        } catch (NoSuchElementException $e) {
            throw new \RuntimeException(sprintf('Element "%s" not found in page (visible text: "%s").', $by->toString(), $this->getBrowserVisibleText()));
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException(sprintf('Error while searching for element "%s" : %s (visible text: "%s").', $by->toString(), $e->getMessage(), $this->getBrowserVisibleText()));
        }
    }

    /**
     * Proxy to browser, catch errors to display body on error.
     *
     * @return array
     */
    public function getElements(By $by, Element $element = null)
    {
        try {
            $obj = null === $element ? $this->getBrowser() : $element;

            return $obj->elements($by);
        } catch (ExceptionInterface $e) {
            throw new \RuntimeException(sprintf('Error while searching for element "%s" : %s (visible text: "%s").', $by->toString(), $e->getMessage(), $this->getBrowserVisibleText()));
        }
    }

    /**
     * @return string
     */
    public function getBrowserVisibleText()
    {
        try {
            return $this->getBrowser()->element(By::tag('body'))->getText();
        } catch (NoSuchElementException $e) {
            throw new \RuntimeException(sprintf('Page does not contain a body.'));
        }
    }

    public function getBrowser()
    {
        if (null === $this->browser) {
            if (null === $this->browserReference) {
                throw new \RuntimeException('Browser reference is missing from context.');
            }

            $this->browser = call_user_func($this->browserReference);
        }

        return $this->browser;
    }

    protected function getUrl($url)
    {
        if (!preg_match('#^https?://#', $url)) {
            $url = $this->baseUrl . '/'.ltrim($url, '/');
        }

        return $url;
    }
}
