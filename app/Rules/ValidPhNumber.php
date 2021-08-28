<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidPhNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $patterns = ["/^[+]63{1}+[9]{1}+[0-9]{9}$/", "/^63{1}+[9]{1}+[0-9]{9}$/", "/^0{1}+[9]{1}+[0-9]{9}$/", "/^[9]{1}+[0-9]{9}$/"];

		for ($i=0; $i < count($patterns); $i++)
		{
			if ( preg_match($patterns[$i], $value) == "1" )
			{
				return true;
			}
		}

		return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Contact number must be a Philippine mobile number format.';
    }
}
