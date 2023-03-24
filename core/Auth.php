<?php

    namespace QuickieDox;

    class Auth
    {
        public function canRead(): bool
        {
            // return (null !== variable('user_id', 'session')
            return true;
        }
    }