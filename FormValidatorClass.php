<?php

/**
 * Created: 19/09/2016
 * @author Guy Pensart
 * @link http://guypensart.be my personal site
*/

/**
 * Class helper to split all inputs into objects
 */
class splitToObject
{
    /**
     * splitToObject constructor.
     * @param array
     */
    function __construct($value)
    {
        $this->value = $value;
        $this->error = '';
        $this->valid = FALSE;
    }
}

/**
 * Class FormValidatorClass
 */
class FormValidatorClass
{
    public
    $value,
    $error,
    $valid,
    $nextRule = TRUE,
    $errorsFree = TRUE,
    $input,
    $currentInput;

    private
    $regExAlphabet = '/[^a-zA-Z -]+/',
    $regExText = '/[^\n\ra-zA-Zé@()è!€$£%?.:+_ -]+/';

    /**
     * FormValidatorClass constructor.
     * @param array $post
     */
    function __construct($post)
    {
        foreach ($post as $key => $value):
            $this->input[$key] = new splitToObject(trim($value));
        endforeach;
    }

    /**
     * Output the value of...
     * @param string $name
     * @return string value
     */
    function getValue($name)
    {
        if (isset($this->input[$name]))
            return $this->input[$name]->value;
        return '';
    }

    /**
     * Switch to validate object
     * @return $this
     */
    function setValid()
    {
        if ($this->nextRule)
            $this->currentInput->valid = TRUE;
        return $this;
    }

    /**
     * Output true if the input is validated
     * @param string $name
     * @return bool valid
     */
    function getValid($name)
    {
        if (isset($this->input[$name]))
            return $this->input[$name]->valid;
        return '';
    }

    /**
     * swap errorsFree and nextRule bool
     * @param $error
     * @return $this
     */
    function setError($error)
    {
        $this->currentInput->error = $error;
        $this->errorsFree = FALSE;
        $this->nextRule = FALSE;
        return $this;
    }

    /**
     * Output the error
     * @param $name
     * @return string
     */
    function getError($name)
    {
        if (isset($this->input[$name]))
            return $this->input[$name]->error;
        return '';
    }

    /**
     * set which error message to use
     * @param $errorMsg
     * @param $default
     * @param null $params
     */
    private function setErrorMsg($errorMsg, $default, $params=NULL)
    {
        $this->errorsFree = FALSE;
        ($errorMsg == '')
            ? $this->currentInput->error = sprintf($default, $params)
            : $this->currentInput->error = $errorMsg;
    }

    /**
     * Check if there are no errors
     * @return bool
     */
    function errorsFree()
    {
        return $this->errorsFree;
    }

    /**
     * Clear all inputs
     */
    function clearFields()
    {
        if ($this->errorsFree())
            foreach($this->input as $key=>$value):
                $this->input[$key]->value = '';
            endforeach;
    }

    /**
     * Temporary debugging info function
     * @return string $debug
     */
    function debug()
    {
        $debug = '';
        foreach($this->input as $key=>$value):
            $debug .= '<span class="debug-';
            $debug .= ($this->getValid($key)) ? 'passed' : 'error';
            $debug .= '">';
            $debug .= $key;
            $debug .= '</span>';
        endforeach;
        $debug .= '<span class="debug-';
        $debug .= ($this->errorsFree()) ? 'passed' : 'error';
        $debug .= '">';
        $debug .= 'Completed';
        $debug .= '</span>';
        return $debug;
    }

    //-----------------------------------------------------------
    // Validation rules, always start validating with input($name)
    //-----------------------------------------------------------

    /**
     * Set currentInput before other validations
     * @param string $name
     * @return $this
     */
    function item($name)
    {
        if (!isset($this->input[$name]))
            $this->input[$name] = new splitToObject('');
        $this->nextRule = TRUE;
        $this->currentInput = $this->input[$name];
        return $this;
    }

    /**
     * Mark field as required
     * @param null $errorMsg
     * @return $this
     */
    function required($errorMsg=NULL)
    {
        if ($this->nextRule)
        {
            $this->nextRule = ( $this->currentInput->value != '') ? TRUE : FALSE;
            if (!$this->nextRule)
                $this->setErrorMsg($errorMsg, 'This field is required');
        }
        return $this;
    }

    /**
     * At least $number of characters
     * @param $number
     * @param null $errorMsg
     * @return $this
     */
    function min($number, $errorMsg=NULL)
    {
        if ($this->nextRule && (!empty($this->currentInput->value)))
        {
            $this->nextRule = (strlen($this->currentInput->value) >= $number);
            if (!$this->nextRule)
                $this->setErrorMsg($errorMsg, 'Minimum %s characters', $number);
        }
        return $this;
    }

    /**
     * maximum number of characters allowed
     * @param $number
     * @param null $errorMsg
     * @return $this
     */
    function max($number, $errorMsg=NULL)
    {
        if ($this->nextRule && (!empty($this->currentInput->value)))
        {
            $this->nextRule = (strlen($this->currentInput->value) <= $number);
            if (!$this->nextRule)
                $this->setErrorMsg($errorMsg, 'Maximum %s characters', $number);
        }
        return $this;
    }

    /**
     * Only letters validation
     * @param null $errorMsg
     * @return $this->nextRule
     */
    function alphabet($errorMsg=NULL)
    {
        if ($this->nextRule && (!empty($this->currentInput->value)))
        {
            // Automtic cleaning? just uncomment next line!
            //$this->currentInput->value = preg_replace($this->regExAlphabet, '', $this->currentInput->value);
            $this->nextRule = (!preg_match($this->regExAlphabet, $this->currentInput->value)) ? TRUE : FALSE;
            if (!$this->nextRule)
                $this->setErrorMsg($errorMsg, 'Only alphabetic letters a-z, spaces and (-) characters');
        }
        return $this;
    }

    /**
     * Valid e-mail validation
     * @param null $errorMsg
     * @return $this->nextRule
     */
    function email($errorMsg=NULL)
    {
        if ($this->nextRule && (!empty($this->currentInput->value)))
        {
            $this->currentInput->value = strtolower($this->currentInput->value);
            $this->currentInput->value = filter_var($this->currentInput->value, FILTER_SANITIZE_EMAIL);
            $this->nextRule = (!filter_var($this->currentInput->value, FILTER_VALIDATE_EMAIL) === false) ? TRUE : FALSE;
            if (!$this->nextRule)
                $this->setErrorMsg($errorMsg, 'This is not a valid e-mail address');
        }
        return $this;
    }

    /**
     * Prohibit special characters validation
     * @param null $errorMsg
     * @return $this->nextRule
     */
    function text($errorMsg=NULL)
    {
        if ($this->nextRule && (!empty($this->currentInput->value)))
        {
            // Automatic cleaning? just uncomment following lines!
            //$this->currentInput->value = filter_var($this->currentInput->value, FILTER_SANITIZE_STRIPPED);
            //$this->currentInput->value = preg_replace($this->regExText, '', $this->currentInput->value);
            $this->nextRule = (!preg_match($this->regExText, $this->currentInput->value)) ? TRUE : FALSE;
            if (!$this->nextRule)
                $this->setErrorMsg($errorMsg, 'Remove special characters');
        }
        return $this;
    }
}