<?php

namespace EasyPost;

/**
 * @package EasyPost
 * @property string $id
 * @property string $object
 * @property string $mode
 * @property string $street1
 * @property string $street2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property bool $residential
 * @property string $carrier_facility
 * @property string $name
 * @property string $company
 * @property string $phone
 * @property string $email
 * @property string $federal_tax_id
 * @property string $state_tax_id
 * @property Verifications $verifications
 */
class Address extends EasypostResource
{
    /**
     * Retrieve an address.
     *
     * @param string $id
     * @param string $apiKey
     * @return mixed
     */
    public static function retrieve($id, $apiKey = null)
    {
        return self::_retrieve(get_class(), $id, $apiKey);
    }

    /**
     * Retrieve all addresses.
     *
     * @param mixed $params
     * @param string $apiKey
     * @return mixed
     */
    public static function all($params = null, $apiKey = null)
    {
        return self::_all(get_class(), $params, $apiKey);
    }

    /**
     * Create an address.
     *
     * @param mixed $params
     * @param string $apiKey
     * @return mixed
     */
    public static function create($params = null, $apiKey = null)
    {
        $urlMod = "";

        if ((isset($params['verify']) && is_array($params['verify'])) || (isset($params['verify_strict']) && is_array($params['verify_strict']))) {
            $verify = "";
            if (isset($params['verify'])) {
                $verify = $params['verify'];
                unset($params['verify']);
            }

            $verify_strict = "";
            if (isset($params['verify_strict'])) {
                $verify_strict = $params['verify_strict'];
                unset($params['verify_strict']);
            }

            $urlMod = "?";

            if (is_array($verify)) {
                foreach ($verify as $verification) {
                    $urlMod .= "verify[]=" . $verification . "&";
                }
            }

            if (is_array($verify_strict)) {
                foreach ($verify_strict as $verification_strict) {
                    $urlMod .= "verify_strict[]=" . $verification_strict . "&";
                }
            }
        }

        if (!isset($params['address']) || !is_array($params['address'])) {
            $clone = $params;
            unset($params);
            $params['address'] = $clone;
        }

        return self::_create(get_class(), $params, $apiKey, $urlMod);
    }

    /**
     * Create and verify an address.
     *
     * @param mixed $params
     * @param string $apiKey
     * @return mixed
     */
    public static function create_and_verify($params = null, $apiKey = null)
    {
        $class = get_class();
        if (!isset($params['address']) || !is_array($params['address'])) {
            $clone = $params;
            unset($params);
            $params['address'] = $clone;
        }

        $requestor = new Requestor($apiKey);
        $url = self::classUrl($class);
        list($response, $apiKey) = $requestor->request('post', $url . '/create_and_verify', $params);

        if (isset($response['address'])) {
            $verified_address = Util::convertToEasyPostObject($response['address'], $apiKey);
            if (!empty($response['message'])) {
                $verified_address->message = $response['message'];
                $verified_address->_immutableValues[] = 'message';
            }

            return $verified_address;
        } else {
            return Util::convertToEasyPostObject($response, $apiKey);
        }
    }

    /**
     * Verify an address.
     *
     * @return mixed
     * @throws \EasyPost\Error
     */
    public function verify()
    {
        $requestor = new Requestor($this->_apiKey);
        $url = $this->instanceUrl() . '/verify';
        list($response, $apiKey) = $requestor->request('get', $url, null);

        if (isset($response['address'])) {
            $verified_address = Util::convertToEasyPostObject($response['address'], $apiKey);
            if (!empty($response['message'])) {
                $verified_address->message = $response['message'];
                $verified_address->_immutableValues[] = 'message';
            }

            return $verified_address;
        } else {
            return Util::convertToEasyPostObject($response, $apiKey);
        }
    }
}
