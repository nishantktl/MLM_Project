<?php

use CodeIgniter\Session\SessionInterface;

if (! function_exists('session_service')) {
    /**
     * Returns the current session instance.
     *
     * @return SessionInterface
     */
    function session_service(): SessionInterface
    {
        return session();
    }
}

if (! function_exists('session_set')) {
    /**
     * Set one or more session values.
     *
     * @param string|array<string,mixed> $key
     * @param mixed $value
     * @return void
     */
    function session_set($key, $value = null): void
    {
        if (is_array($key)) {
            session()->set($key);
            return;
        }

        session()->set($key, $value);
    }
}

if (! function_exists('session_get')) {
    /**
     * Get a session value, or all session data when no key is provided.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function session_get(?string $key = null, $default = null)
    {
        if ($key === null) {
            return session()->get();
        }

        $value = session()->get($key);

        return $value === null ? $default : $value;
    }
}

if (! function_exists('session_has')) {
    /**
     * Determine whether a session key exists.
     */
    function session_has(string $key): bool
    {
        return session()->has($key);
    }
}

if (! function_exists('session_remove')) {
    /**
     * Remove one or more session values.
     *
     * @param string|array<string> $key
     * @return void
     */
    function session_remove($key): void
    {
        session()->remove($key);
    }
}

if (! function_exists('session_destroy')) {
    /**
     * Destroy the current session.
     */
    function session_destroy(): bool
    {
        return session()->destroy();
    }
}

if (! function_exists('session_set_flash')) {
    /**
     * Set flashdata for the next request.
     *
     * @param string|array<string,mixed> $key
     * @param mixed $value
     * @return void
     */
    function session_set_flash($key, $value = null): void
    {
        if (is_array($key)) {
            session()->setFlashdata($key);
            return;
        }

        session()->setFlashdata($key, $value);
    }
}

if (! function_exists('session_get_flash')) {
    /**
     * Get flashdata from the current session.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function session_get_flash(?string $key = null, $default = null)
    {
        if ($key === null) {
            return session()->getFlashdata();
        }

        $value = session()->getFlashdata($key);

        return $value === null ? $default : $value;
    }
}

if (! function_exists('session_keep_flash')) {
    /**
     * Keep flashdata for one more request.
     *
     * @param string|string[]|null $key
     * @return void
     */
    function session_keep_flash($key = null): void
    {
        session()->keepFlashdata($key);
    }
}
