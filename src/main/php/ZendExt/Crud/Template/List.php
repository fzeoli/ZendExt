<?php
/**
 * List crud template.
 *
 * @category  ZendExt
 * @package   ZendExt_Crud_Template
 * @copyright 2010 Monits
 * @license   Copyright (C) 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.monits.com/
 * @since     1.0.0
 */

/**
 * List crud template.
 *
 * @category  ZendExt
 * @package   ZendExt_Crud_Template
 * @author    itirabasso <itirabasso@monits.com>
 * @copyright 2010 Monits
 * @license   Copyright 2010. All rights reserved.
 * @version   Release: 1.0.0
 * @link      http://www.monits.com/
 * @since     1.0.0
 */
class ZendExt_Crud_Template_List extends ZendExt_Crud_TemplateAbstract
{
    protected $_view;

    /**
     * Crud template construct.
     *
     * @param Zend_View $view
     */
    public function __construct(Zend_View $view)
    {
        $this->_view = $view;
    }

    /**
 	 *
 	 * Render the list.
 	 *
 	 * @param Zend_Paginator $paginator
 	 * @param int $page The page to be rendered.
 	 * @param int $ipp Items per page.
 	*/
    public function render($title = null)
    {
        if (null !== $title) {
            $this->setTitle($title);
        }

        $this->header();

        $this->_renderList();

        $this->footer();
    }

    /**
     * Renders the list.
     *
     * @returns void
     */
    private function _renderList()
    {
        $this->_renderPageBar();

        $items = $this->_view->paginator->getCurrentItems();
        $i = 0;
        echo "<table>";

        $arrCols = $items->offsetGet(1)->toArray();

        foreach ($arrCols as $col => $c) {
            echo "<th>" . $col . "</th>";
        }

        foreach ($items as $item) {
            $arrCols = $item->toArray();
            echo "<div class='item>'";
            echo "<tr>";
            foreach ($arrCols as $col => $c) {
                echo "<td>";
                echo "<span class='" . $col .
                    "' style='display:table-row'>" . $c . "</span>";
                echo "</td>";
            }
            echo "</tr>";
            echo "</div>";
        }
        echo "</table>";

        $this->_renderPageBar();
    }

    /**
     * Renders the page bar.
     *
     * @return void
     */
    private function _renderPageBar()
    {
        $paginator = $this->_view->paginator;

        $first =  1;
        $previous = $this->_view->paginator->getCurrentPageNumber() - 1;
        $current = $this->_view->paginator->getCurrentPageNumber();
        $next = $this->_view->paginator->getCurrentPageNumber() + 1;
        $last = ceil(
            $this->_view->paginator->getTotalItemCount() /
            $this->_view->paginator->getItemCountPerPage()
        );

        echo "<div class='pageBar'>";

        if ($first != $current) {
            echo "<span class='page'><a href='/?page=" . $first .
                    "'> First </a></span>";
            echo "<span class='page'><a href='/?page=" . $previous .
                    "'> Previous </a></span>";
        }

        echo "<span class='page'>Current</span>";

        if ($last != $current) {
            echo "<span class='page'><a href='/?page=" . $next .
                    " '> Next </a></span>";
            echo "<span class='page'><a href='/?page=" . $last .
                    "'> Last </a></span>";
        }

        echo "</div>";
    }
}