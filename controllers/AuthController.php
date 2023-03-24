<?php

    namespace QuickieDox\Controllers;

    use QuickieDox\Auth;
    use QuickieDox\Session;

    class AuthController
    {
        public function canRead(): bool
        {
            return (new Auth())->canRead();
        }

        public function validate()
        {
            // here you write code to validate a user or token or something
        }

        public function signOut()
        {
            // clear all session variables and do all your sign out magic
            Session::forget($_SESSION);
        }
    }