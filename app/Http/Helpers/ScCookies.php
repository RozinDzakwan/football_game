<?php

namespace App\Http\Helpers;

class ScCookies
{

    /**
     * @param $key
     * @param array $value
     * @param null $duration in unix timestamp
     * @param bool $encrypt
     * @return bool
     */
    public static function setCookie($key, array $value, $duration = null, bool $encrypt = true ): bool
    {
        $sessionId = request()->session()->getId();

        if ( empty( $updated = session()->get( $sessionId . '-functionalCookies-updated') ) ) {
            $updated = false;
        }

        if ( !empty( $_COOKIE[ 'Sc-' . $key ] ) || $updated) {

            if ( $updated ) {
                $stored_value = $updated;
            } else {
                $stored_value = $_COOKIE[ 'Sc-' . $key ];
            }

            if ( $encrypt === true && !empty( $stored_value ) ) {
                $stored_value = decrypt( $stored_value );
            }

            $stored_value = json_decode( $stored_value, true );
            $new_value = [];

            foreach ($value as $new_param_key => $new_param_value ) {
                $new_value[$new_param_key] = $new_param_value;
            }

            $new_cookie_value = array_merge( $stored_value, $new_value );

        } else {
            $new_cookie_value = $value;
        }
        if ($duration === null) {
            $duration = time() + (60*60*24*30); //standard expiration in 30 days
        }

        $new_cookie_value = json_encode($new_cookie_value);

        if( $encrypt === true ) {
            $new_cookie_value = encrypt($new_cookie_value);
        }

        session()->put( $sessionId . '-functionalCookies-updated', $new_cookie_value );

        if( setcookie( 'Sc-' . $key, $new_cookie_value, $duration, '/' ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $mainKey
     * @param $subKey
     * @param bool $decrypt
     * @return mixed|null
     */
    public static function getCookie($mainKey, $subKey,  bool $decrypt = true ): mixed
    {

        $value = false;
        if ( !empty( $_COOKIE[ 'Sc-' . $mainKey ] ) && !is_null( $subKey ) ) {
            $value = $_COOKIE[ 'Sc-' . $mainKey ];
            if ( $decrypt === true ) {
                $value = decrypt($value);
            }
            $value = json_decode( $value, true );
            if ( !empty( $value[ $subKey ] )) {
                $value = $value[ $subKey ];
            } else {
                $value = null;
            }

        } elseif ( !empty( $_COOKIE[ 'Sc-' . $mainKey ] ) && is_null( $subKey ) ) {
            if ( $decrypt === true ) {
                $value = decrypt($_COOKIE['Sc-' . $mainKey]);
            }
            $value = json_decode( $value, true );
        } else {
            return false;
        }

        return $value;

    }

    /**
     * @param $mainKey
     * @param string|null $subKey
     * @param null $duration
     * @param bool $encrypt
     * @return mixed|null
     */
    public static function removeCookie($mainKey, string $subKey = null, $duration = null, bool $encrypt = true ): mixed
    {
        //If the subKey is empty we simply delete all the cookie
        if ( $subKey === null ) {
            return setcookie( 'Sc-' . $mainKey, '', -1, '/' );
        }

        $value = false;
        if ( !empty( $_COOKIE[ 'Sc-' . $mainKey ] ) ) {
            $value = $_COOKIE[ 'Sc-' . $mainKey ];

            if ( $encrypt === true ) {
                $value = decrypt( $value );
            }

            $value = json_decode( $value, true );

            if ( !empty( $subKey ) && !empty( $value[$subKey] ) ) {
                unset( $value[$subKey] );
            }

            $value = json_encode( $value );

            if ( $encrypt === true ) {
                $value = encrypt( $value );
            }

        }
        if ($duration == null) {
            $duration = time() + (60*60*24*30); //standard expiration in 30 days
        }
        return setcookie( 'Sc-' . $mainKey, $value, $duration, '/' );

    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function setFunctionalCookie( $key, $value ): bool
    {
        return self::setCookie('functional', [ $key => $value ]);
    }

    /**
     * @param $key if null will get all Optional cookies in an array
     * @return mixed|null
     */
    public static function getFunctionalCookie($key = null ): mixed
    {
        return self::getCookie( 'functional', $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function removeFunctionalCookie($key = null ): mixed
    {
        return self::removeCookie( 'functional', $key);
    }


    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function setOptionalCookie( $key, $value ): bool
    {
        return self::setCookie('optional', [ $key => $value ]);
    }

    /**
     * @param $key if null will get all Optional cookies in an array
     * @return mixed|null
     */
    public static function getOptionalCookie( $key = null ): mixed
    {
        return self::getCookie( 'optional', $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function removeOptionalCookie($key = null ): mixed
    {
        return self::removeCookie( 'optional', $key);
    }

    /**
     * @param $key
     * @param $value
     * @return bool
     */
    public static function setStandAloneCookie( $key, $value ): bool
    {
        return self::setCookie('optional', [ $key => $value ]);
    }

    /**
     * @param $key if null will get all Optional cookies in an array
     * @return mixed|null
     */
    public static function getStandAloneCookie( $key = null ): mixed
    {
        return self::getCookie( 'optional', $key);
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function removeStandAloneCookie($key = null ): mixed
    {
        return self::removeCookie( 'optional', $key);
    }

}
