<?php

/**
 * Apishka test slider slider test
 */

class ApishkaTest_Slider_SliderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get slider
     */

    protected function getSlider()
    {
        return new Apishka_Slider_Slider();
    }

    /**
     * Test url
     *
     * @dataProvider providerTestUrl
     *
     * @param string $url
     * @param int    $page
     * @param string $expected
     */

    public function testUrl($url, $url_options, $page, $expected)
    {
        $slider = $this->getSlider()
            ->setUrl($url)
            ->setUrlOptions($url_options)
        ;

        $this->assertSame(
            $expected,
            $slider->processUrlByPage($page)
        );
    }

    /**
     * Provider test url
     *
     * @return array
     */

    public function providerTestUrl()
    {
        return array(
            array(
                '/some/test',
                array(),
                1,
                '/some/test',
            ),
            array(
                '/some/test',
                array(),
                2,
                '/some/test?page=2',
            ),
            array(
                '/some/test?page=1',
                array(),
                2,
                '/some/test?page=2',
            ),
            array(
                '/some/test?foo=bar',
                array(
                    'foo' => null,
                ),
                2,
                '/some/test?page=2',
            ),
            array(
                '/some/test?foo=bar',
                array(
                    'foo' => 'baz',
                ),
                2,
                '/some/test?foo=baz&page=2',
            ),
        );
    }

    /**
     * Test limit
     *
     * @dataProvider providerTestLimit
     *
     * @param string $url
     * @param int    $limit
     * @param int    $page
     * @param array  $expected
     */

    public function testLimit($url, $limit, $page, $expected)
    {
        $slider = $this->getSlider()
            ->setUrl($url)
            ->setCurrentPage($page)
            ->setTotal($limit)
        ;

        foreach ($expected as $property => $value)
        {
            $this->assertSame(
                $slider->$property,
                $value
            );
        }
    }

    /**
     * Provider test url
     *
     * @return array
     */

    public function providerTestLimit()
    {
        return array(
            array(
                '/example/page',
                100,
                1,
                array(
                    'limit'         => 20,
                    'currentPage'   => 1,
                    'urlFirstPage'  => '/example/page',
                    'hasNextPage'   => true,
                    'urlNextPage'   => '/example/page?page=2',
                    'hasPrevPage'   => false,
                ),
            ),
            array(
                '/example/page',
                100,
                5,
                array(
                    'limit'         => 20,
                    'currentPage'   => 5,
                    'urlFirstPage'  => '/example/page',
                    'hasNextPage'   => false,
                    'hasPrevPage'   => true,
                    'urlPrevPage'   => '/example/page?page=4',
                ),
            ),
            array(
                '/example/page',
                100,
                5,
                array(
                    'limit'         => 20,
                    'currentPage'   => 5,
                    'urlFirstPage'  => '/example/page',
                    'hasNextPage'   => false,
                    'hasPrevPage'   => true,
                    'urlPrevPage'   => '/example/page?page=4',
                ),
            ),
        );
    }
}
