<?php

/**
 * Apishka slider slider
 */

class Apishka_Slider_Slider
{
    /**
     * Traits
     */

    use \Apishka\EasyExtend\Helper\ByClassNameTrait;

    /**
     * Current page
     *
     * @var mixed
     */

    private $_current_page = null;

    /**
     * Items on page
     *
     * @var int
     */

    private $_limit = 20;

    /**
     * Reversed flag
     *
     * @var bool
     */

    private $_reversed = false;

    /**
     * Total
     *
     * @var int
     */

    private $_total = null;

    /**
     * Url
     *
     * @var string
     */

    private $_url = null;

    /**
     * Url options
     *
     * @var array
     */

    private $_url_options = array();

    /**
     * Variable
     *
     * @var string
     */

    private $_variable = 'page';

    /**
     * Magic getter for properties
     *
     * @param string $name
     *
     * @return mixed
     */

    public function __get($name)
    {
        if (method_exists($this, $method = '__get' . $name))
            return $this->$method();

        throw new LogicException('Getter for ' . var_export($name, true) . ' not found');
    }

    /**
     * Get
     *
     * @param mixed $name
     *
     * @return mixed
     */

    public function get($name)
    {
        return $this->__get($name);
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Apishka_Slider_Slider this
     */

    public function setUrl($url)
    {
        $this->_url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */

    protected function __getUrl()
    {
        return $this->_url;
    }

    /**
     * Set url options
     *
     * @param array $options
     *
     * @return Apishka_Slider_Slider this
     */

    public function setUrlOptions(array $options)
    {
        $this->_url_options = $options;

        return $this;
    }

    /**
     * Returns url options
     *
     * @return array
     */

    protected function __getUrlOptions()
    {
        return $this->_url_options;
    }

    /**
     * Set limit
     *
     * @param int $limit
     *
     * @return Apishka_Slider_Slider this
     */

    public function setLimit($limit)
    {
        $this->_limit = $limit;

        return $this;
    }

    /**
     * Get on page
     *
     * @return int
     */

    protected function __getLimit()
    {
        return $this->_limit;
    }

    /**
     * Set variable
     *
     * @param string $variable
     *
     * @return Apishka_Slider_Slider this
     */

    protected function __setVariable($variable)
    {
        $this->_variable = $variable;

        return $this;
    }

    /**
     * Get variable
     *
     * @return string
     */

    protected function __getVariable()
    {
        return $this->_variable;
    }

    /**
     * Set total
     *
     * @param int $total
     *
     * @return Apishka_Slider_Slider this
     */

    public function setTotal($total)
    {
        $this->_total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */

    protected function __getTotal()
    {
        return $this->_total;
    }

    /**
     * Set reversed flag
     *
     * @param bool $reversed
     *
     * @return Apishka_Slider_Slider this
     */

    public function setReversed($reversed)
    {
        $this->_reversed = (bool) $reversed;

        return $this;
    }

    /**
     * Returns reversed flah
     *
     * @return bool
     */

    protected function __getReversed()
    {
        return $this->_reversed;
    }

    /**
     * Set current page
     *
     * @param int $page
     *
     * @return Apishka_Slider_Slider this
     */

    public function setCurrentPage($page)
    {
        $this->_current_page = $page;

        return $this;
    }

    /**
     * Get current page
     *
     * @return int
     */

    protected function __getCurrentPage()
    {
        if ($this->_current_page === null)
            $this->_current_page = $this->processCurrentPage();

        return $this->_current_page;
    }

    /**
     * Returns first page url
     *
     * @return string
     */

    protected function __getUrlFirstPage()
    {
        return $this->processUrlByPage(1);
    }

    /**
     * Returns url by page
     *
     * @param int $page
     *
     * @return string
     */

    public function processUrlByPage($page)
    {
        if ($page == $this->__getFirstPage())
            $page = false;

        return $this->buildLink(
            array(
                $this->buildPageVarName()   => $page,
            )
        );
    }

    /**
     * Returns true if slider has next page
     *
     * @return bool
     */

    protected function __getHasNextPage()
    {
        if ($this->__getCountPages() == 1 || $this->__getCurrentPage() > $this->__getCountPages())
            return false;

        return $this->__getCurrentPage() != $this->__getCountPages();
    }

    /**
     * Returns next page url
     *
     * @return string
     */

    protected function __getUrlNextPage()
    {
        return $this->processUrlByPage($this->__getNextPage());
    }

    /**
     * Returns true if slider has previous page
     *
     * @return bool
     */

    protected function __getHasPrevPage()
    {
        if ($this->__getCountPages() == 1 || $this->__getCurrentPage() < 1)
            return false;

        return $this->__getCurrentPage() != 1;
    }

    /**
     * Returns previous page url
     *
     * @return string
     */

    protected function __getUrlPrevPage()
    {
        return $this->processUrlByPage($this->__getPrevPage());
    }

    /**
     * Returns current page url
     *
     * @return string
     */

    protected function __getUrlCurrPage()
    {
        if ($this->__getCountPages() == 1)
            return $this->__getUrlFirstPage();

        return $this->processUrlByPage($this->__getCurrentPage());
    }

    /**
     * Returns last page url
     *
     * @return string
     */

    protected function __getUrlLastPage()
    {
        if ($this->__getCountPages() == 1)
            return $this->__getUrlFirstPage();

        return $this->processUrlByPage($this->__getCountPages());
    }

    /**
     * Returns pages count
     *
     * @return int
     */

    protected function __getCountPages()
    {
        if ($this->__getTotal() === null)
            return;

        return max(
            ceil($this->__getTotal() / $this->__getLimit()),
            1
        );
    }

    /**
     * Returns first page
     *
     * @return int
     */

    protected function __getFirstPage()
    {
        if ($this->__getReversed())
            return $this->__getCountPages();

        return 1;
    }

    /**
     * Returns previous page
     *
     * @return int
     */

    protected function __getPrevPage()
    {
        if (!$this->__getHasPrevPage())
            throw new LogicException('Cannot construct previous page url');

        return $this->__getCurrentPage() - 1;
    }

    /**
     * Process current page
     *
     * @return int
     */

    protected function processCurrentPage()
    {
        if (array_key_exists($this->buildPageVarName(), $_GET))
            return (int) $_GET[$this->buildPageVarName()];

        return $this->__getReversed()
            ? $this->__getCountPages()
            : 1
        ;
    }

    /**
     * Returns next page
     *
     * @return int
     */

    protected function __getNextPage()
    {
        if (!$this->__getHasNextPage())
            throw new LogicException('Cannot construct next page url');

        return $this->__getCurrentPage() + 1;
    }

    /**
     * Returns last page
     *
     * @return int
     */

    protected function __getLastPage()
    {
        if ($this->__getReversed())
            return 1;

        return $this->__getCountPages();
    }

    /**
     * Build link
     *
     * @param array $options
     *
     * @return string
     */

    protected function buildLink(array $options = array())
    {
        $url = $this->__getUrl() ?? Apishka\Uri\Uri::fromProvider();
        $uri = Apishka\Uri\Uri::fromUri($url);

        $uri->applyQueryParams(
            array_replace_recursive(
                $_GET,
                $uri->getQuery()->__toArray(),
                $this->__getUrlOptions(),
                $options
            )
        );

        return $uri->getRelative();
    }

    /**
     * Returns offset
     *
     * @return int
     */

    protected function __getOffset()
    {
        return (max($this->__getCurrentPage(), 1) - 1) * $this->__getLimit();
    }

    /**
     * Returns begin number
     *
     * @return int
     */

    protected function __getFirst()
    {
        return $this->__getOffset() + 1;
    }

    /**
     * Returns last number
     *
     * @return int
     */

    protected function __getLast()
    {
        if (($this->__getOffset() + $this->__getLimit()) > $this->__getTotal())
            return $this->__getTotal();

        return $this->__getOffset() + $this->__getLimit();
    }

    /**
     * Build page variable name
     *
     * @return string
     */

    protected function buildPageVarName()
    {
        return $this->__getVariable();
    }
}
