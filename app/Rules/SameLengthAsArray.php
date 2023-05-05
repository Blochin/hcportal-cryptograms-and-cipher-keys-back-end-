<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SameLengthAsArray implements Rule
{
    protected $array2;
    protected $attributeName;

    public function __construct($array2, $attributeName)
    {
        $this->array2 = $array2;
        $this->attributeName = $attributeName;
    }

    public function passes($attribute, $value)
    {

        //$valueArray = json_decode($value, true);
        if (is_array($this->array2)) {
            $array2Array = $this->array2;
        } else {
            $array2Array = json_decode($this->array2, true);
        }

        if (is_array($value)) {
            $value = $value;
        } else {
            $value = json_decode($value, true);
        }

        return count($value) === count($array2Array);
    }

    public function message()
    {
        return 'The :attribute must have the same length as the given attribute ' . $this->attributeName . '.';
    }
}
