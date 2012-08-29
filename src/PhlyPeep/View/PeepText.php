<?php

namespace PhlyPeep\View;

use Zend\View\Helper\AbstractHelper;

class PeepText extends AbstractHelper
{
    protected function filterLinks($text)
    {
        return preg_replace_callback(
            '#(https?://[a-zA-Z0-9_%&~@/\#.-]+)#', 
            array($this, 'markupLink'), 
            $text
        );
    }

    public function markupLink($matches)
    {
        $link = $matches[1];
        return sprintf('<a href="%s" target="_blank">%s</a>', $link, $link);
    }

    public function __invoke($text)
    {
        $text = $this->view->escapeHtml($text);
        $text = $this->filterLinks($text);
        return $text;
    }
}
