<?php

namespace App\Util;

/**
 * Class Crypto
 *
 * @package App\Util
 */
class Crypto {

    public static function encrypt( string $data, int $offset ): string {

        return strrev( $data );

    }

    public static function decrypt( string $data, int $offset ): string {

        return strrev( $data );

    }

    public static function encryptName( string $data ): string {

        if ( '__' === substr( $data, 0, 2 ) ) {

            return $data;

        }

        return '__' . static::encrypt( $data, 0 );

    }

    public static function decryptName( string $data ): string {

        if ( '__' !== substr( $data, 0, 2 ) ) {

            return $data;

        }

        return static::decrypt( substr( $data, 2 ), 0 );

    }

    public static function encryptStream( $stream ) {

        return $stream;

        dump( $stream );
        exit( __METHOD__ );

    }

    public static function decryptStream( $stream ) {

        return $stream;

        dump( $stream );
        exit( __METHOD__ );

    }

}
