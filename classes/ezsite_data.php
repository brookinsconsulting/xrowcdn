<?php

/**
 * File containing the eZSiteData class.
 *
 * @copyright Copyright (C) 1999-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPL v2
 * @version //autogentag//
 * @package kernel
 */

/**
 * Class representing a Site Data Key Value pair
 *
 * @version //autogentag//
 * @package kernel
 */
class eZSiteData extends eZPersistentObject
{

    function __construct( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        static $definition = array( 
            'fields' => array( 
                'name' => array( 
                    'name' => 'Name' , 
                    'datatype' => 'string' , 
                    'default' => '' , 
                    'required' => true 
                ) , 
                'value' => array( 
                    'name' => 'Value' , 
                    'datatype' => 'string' , 
                    'default' => '' , 
                    'required' => true 
                ) 
            ) , 
            'keys' => array( 
                'name' 
            ) , 
            'function_attributes' => array() , 
            'increment_key' => 'id' , 
            'sort' => array( 
                'name' => 'asc' 
            ) , 
            'class_name' => 'eZSiteData' , 
            'name' => 'ezsite_data' 
        );
        return $definition;
    }

    /* Gets the value of a key 
     * 
     * @param $name Name of the key
     * @return Returns the value of a key or false of the key doesn`t exists
     */
    static function get( $name )
    {
        $data = eZPersistentObject::fetchObject( eZSiteData::definition(), null, array( 
            'name' => $name 
        ), true );
        if ( $data instanceof eZPersistentObject )
        {
            return $data->attribute( 'value' );
        }
        else
        {
            return false;
        }
    
    }

    /* Sets the value of a key
     * @param $name Name of the key
     * @param $value Value of the key
     * @return True on success
     */
    static function set( $name, $value )
    {
        $value = (string) $value;
        $data = eZPersistentObject::fetchObject( eZSiteData::definition(), null, array( 
            'name' => $name 
        ), true );
        if ( ! $data instanceof eZPersistentObject )
        {
            $row = array( 
                'name' => $name , 
                'value' => $value 
            );
            $data = new eZSiteData( $row );
            return $data->store();
        }
        elseif ( $data instanceof eZPersistentObject and $value !== $data->attribute( 'value' ) )
        {
            $data->setAttribute( 'value', $value );
            return $data->store();
        }
        return true;
    }
}
?>
