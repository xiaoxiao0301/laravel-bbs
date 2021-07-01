<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EamilVerifiedRule implements Rule
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
        $emailPattern = "/[A-Za-z0-9\x{4e00}-\x{9fa5}]+@(qq.com|163.com|sina.com|gmail.com)/u";
        preg_match($emailPattern, $value, $result);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "格式错误或者邮箱必须是qq,163,gmail,sina之一";
    }
}
