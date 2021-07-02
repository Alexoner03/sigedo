<?php

namespace App\Traits;

trait RequestTrait
{
    public function flashMessages(Array $messages)
    {
        foreach ($messages as $key => $value) {
            request()->session()->flash($key,$value);
        }
    }

    public function flashSuccess(String $message)
    {
        request()->session()->flash('flash.banner', $message);
        request()->session()->flash('flash.bannerStyle', "success");
    }

    public function flashError(String $message)
    {
        request()->session()->flash('flash.banner', $message);
        request()->session()->flash('flash.bannerStyle', "danger");
    }
}