<?php

use Phalcon\Forms\Element,
    Phalcon\Forms\Element\Text,
    Phalcon\Forms\Element\Hidden;

/**
 * Class Autocomplete element
 * 
 * Use lib: http://api.jqueryui.com/autocomplete/
 * Requirements:
 *  Include css: //code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css
 *  Include JS: JQuery
 *  Include JS: //code.jquery.com/ui/1.11.4/jquery-ui.js
 *  Include JS: js/autocomplete.js
 */
class Autocomplete extends Element
{
    /**
     * Render div and inputs for auto complete inside it
     *
     * @param null $attributes
     * @return string
     */
    public function render($attributes = null)
    {
        $selector = new Text('selector', $this->getAttribute('selector'));
        $selectorStorage = new Hidden('selectorStorage', $this->getAttribute('selectorStorage'));

        return '<div class="ui-widget">' . $selector->render() . $selectorStorage->render() . '</div>';
    }
}
