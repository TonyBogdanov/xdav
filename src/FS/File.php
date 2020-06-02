<?php

namespace App\FS;

use App\Util\Crypto;
use Sabre\DAV\FS\File as SabreFile;

/**
 * Class File
 *
 * @package App\FS
 */
class File extends SabreFile {

    /**
     * @return string|void
     */
    public function getName() {

        return Crypto::decryptName( parent::getName() );

    }

    /**
     * @param string $name
     */
    public function setName( $name ) {

        parent::setName( Crypto::encryptName( $name ) );

    }

    /**
     * @param resource $data
     */
    function put( $data ) {

        file_put_contents( $this->path, Crypto::encryptStream( $data ) );
        clearstatcache( true, $this->path );

    }

    /**
     * @return resource|void
     */
    function get() {

        return Crypto::decryptStream( fopen( $this->path, 'r' ) );

    }

    function delete() {

        unlink( $this->path );

    }

    /**
     * @return false|int
     */
    function getSize() {

        return filesize( $this->path );

    }

    /**
     * @return mixed|string
     */
    function getETag() {

        return sprintf( '"%s"', sha1(

            fileinode( $this->path ) .
            filesize( $this->path ) .
            filemtime( $this->path )

        ) );

    }

    /**
     * @return mixed|null
     */
    function getContentType() {

        return null;

    }

}
