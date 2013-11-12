<?php

function getParameter($param, &$value)
{
    if (!array_key_exists($param, $_GET))
    {
        return 400;
    }

    $value = $_GET[$param];
    if (is_array($value))
        foreach($value as &$val) $val = htmlspecialchars($val);
    else
        $value = htmlspecialchars($value);

    return 0;
}
