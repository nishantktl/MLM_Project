<?php

if (! function_exists('dd'))
{
    /**
     * Dump and die helper.
     *
     * @param mixed $data
     * @return void
     */
    function dd($data)
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;
    }
}

function getuserdata(): ?array
{
    $session = session_data();

    $userId = $session['user_id'] ?? null;

    if (!$userId) {
        return null;
    }

    $userModel = new \App\Models\UserModel();   
    $user = $userModel->getUserById($userId);

    return $user ?: null;
}