<?php

namespace App\Auth;

use Sabre\DAV\Auth\Backend\BasicCallBack;

/**
 * Class Digest
 *
 * @package App\Auth
 */
class Digest extends BasicCallBack {

    /**
     * @var string
     */
    static public string $key;

    /**
     * Digest constructor.
     */
    public function __construct() {

        parent::__construct( [ $this, 'validate' ] );

    }

    /**
     * @param $username
     * @param $password
     *
     * @return bool
     */
    protected function validate( $username, $password ) {

        static::$key = password_hash( $username . ':' . $password, PASSWORD_BCRYPT );
        return true;

    }

}
