<?php

namespace App\FS;

use App\Util\Crypto;
use FilesystemIterator;
use Sabre\DAV\Exception\NotFound;
use Sabre\DAV\FS\Directory as SabreDirectory;
use Sabre\DAV\INode;

/**
 * Class Directory
 *
 * @package App\FS
 */
class Directory extends SabreDirectory {

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
     * @param string $name
     * @param null $data
     *
     * @return string|void|null
     */
    function createFile( $name, $data = null ) {

        $name = Crypto::encryptName( $name );
        $path = $this->path . '/' . $name;

        file_put_contents( $path, isset( $data ) ? Crypto::encryptStream( $data ) : null );
        clearstatcache( true, $path );

    }

    /**
     * @param string $name
     */
    function createDirectory( $name ) {

        $name = Crypto::encryptName( $name );
        $path = $this->path . '/' . $name;

        mkdir( $path );
        clearstatcache( true, $path );

    }

    /**
     * @param string $name
     *
     * @return Directory|File|INode
     * @throws NotFound
     */
    function getChild( $name ) {

        $name = Crypto::encryptName( $name );
        $path = $this->path . '/' . $name;

        if ( ! file_exists( $path ) ) {

            throw new NotFound( 'File could not be located' );

        }

        if ( is_dir( $path ) ) {

            return new self( $path );

        } else {

            return new File( $path );

        }

    }

    /**
     * @return array|INode[]
     * @throws NotFound
     */
    public function getChildren() {

        $nodes = [];
        $iterator = new FilesystemIterator(

            $this->path,
            FilesystemIterator::CURRENT_AS_SELF | FilesystemIterator::SKIP_DOTS

        );

        foreach ( $iterator as $entry ) {

            $nodes[] = $this->getChild( $entry->getFilename() );

        }

        return $nodes;

    }

    /**
     * @param string $name
     *
     * @return bool
     */
    function childExists( $name ) {

        $name = Crypto::encryptName( $name );
        return file_exists( $this->path . '/' . $name );

    }

    /**
     * @throws NotFound
     */
    function delete() {

        foreach ( $this->getChildren() as $child ) {

            $child->delete();

        }

        rmdir( $this->path );

    }

    /**
     * @return array
     */
    function getQuotaInfo() {

        return [ 1, 1 ];

    }

}
