<?php

class Zend_View_Helper_GetUserInfo
{
    public function getUserInfo($userId, $column)
    {
        $users  = new Application_Model_Users();
        $user   = $users->find($userId);

        return $user->$column;
    }
}