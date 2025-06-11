<?php

/**
 * Memeriksa apakah pengguna sudah login dengan mengecek token di session.
 *
 * @return bool
 */
function is_user_logged_in(): bool
{
    return session()->has('api_token') &&
        session()->has('user') &&
        session('api_token') !== null &&
        session('user') !== null &&
        !empty(session('api_token')) &&
        !empty(session('user'));
}

/**
 * Alias untuk is_user_logged_in
 */
function session_check(): bool
{
    return is_user_logged_in();
}

/**
 * Mengambil data pengguna yang login dari session.
 * Mengembalikan objek pengguna atau null jika tidak ada.
 *
 * @return object|null
 */
function session_user(): ?object
{
    if (!is_user_logged_in()) {
        return null;
    }

    $user = session('user');

    // PERBAIKAN: Handle array dan object dengan lebih baik
    if (is_array($user)) {
        return (object) $user;
    } elseif (is_object($user)) {
        return $user;
    }

    return null;
}

/**
 * Get user name from session
 *
 * @return string
 */
function session_user_name(): string
{
    if (!is_user_logged_in()) {
        return 'Guest';
    }

    $user = session('user');

    // PERBAIKAN: Handle baik array maupun object
    if (is_array($user)) {
        return $user['name'] ?? 'User';
    } elseif (is_object($user)) {
        return $user->name ?? 'User';
    }

    return 'User';
}

/**
 * Get user token from session
 *
 * @return string|null
 */
function session_token(): ?string
{
    return is_user_logged_in() ? session('api_token') : null;
}

/**
 * Get user data as array from session
 *
 * @return array|null
 */
function session_user_array(): ?array
{
    if (!is_user_logged_in()) {
        return null;
    }

    $user = session('user');

    if (is_array($user)) {
        return $user;
    } elseif (is_object($user)) {
        return (array) $user;
    }

    return null;
}
