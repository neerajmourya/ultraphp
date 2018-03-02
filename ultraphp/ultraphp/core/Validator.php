<?php

namespace ultraphp\core;
/**
 * Validator class
 * Manages data validation
 * @author Neeraj Mourya <neeraj@egrapes.in>, <neerajmorya@gmail.com>
 * @since 2.0.0
 */
class Validator {

    /**
     * Validates data and return messagebox with errors if validation fails
     * @param array $data
     * @param array $validtionsArr
     * @param array $attributes
     * @return \ultraphp\core\MessageBox
     */
    public static function validate($data = array(), $validtionsArr = array(), $attributes = array()) {
        $errors = array();
        $resource = array();
        foreach ($validtionsArr as $field => $validations) {
            $valExp = explode("|", $validations);
            $fieldAttribute = str_replace('_', ' ', $field);
            if (isset($attributes[$field]) && !empty($attributes[$field])) {
                $fieldAttribute = $attributes[$field];
            }
            foreach ($valExp as $validation) {
                if (!self::validateField($data[$field], $validation)) {
                    if (!isset($errors[$field])) {
                        $errors[$field] = array();
                    }

                    if (!isset($resource) || empty($resource)) {
                        $resource = Resource::getResource('errors');
                    }

//                    $errors[$field][] = Resource::getValue('errors', $validation, $fieldAttribute);


                    if (isset($resource[$validation]) && !empty($resource[$validation])) {
                        $value = $resource[$validation];
                        $params = [$fieldAttribute];
                        if (isset($params) && !empty($params)) {
                            for ($i = 0; $i < sizeof($params); $i++) {
                                $errors[$field][] = str_replace("{" . $i . "}", $params[$i], $value);
                            }
                        }
                    }
//                    print_r($errors[$field]['errors']);
                }
            }
        }

        return new MessageBox($errors);
    }

    /**
     * Validates a value against a validation
     * @param type $value
     * @param string $validation
     * @return boolean returns true if validated else false
     */
    public static function validateField($value, $validation) {
        switch ($validation) {
            case 'required':
                return self::validateRequired($value);
            case 'email':
                return self::validateEmail($value);
            case 'boolean':
                return self::validateBoolean($value);
            case 'float':
                return self::validateFloat($value);
            case 'int':
                return self::validateInt($value);
            case 'ip':
                return self::validateIp($value);
            case 'url':
                return self::validateUrl($value);
            case 'regexp':
                return self::validateRegexp($value);
        }
    }

    /**
     * Validates Required validation
     * @param type $value
     * @return boolean returns true if validated else false
     */
    public static function validateRequired($value) {
        if (isset($value) && !empty($value)) {
            return true;
        }
        return false;
    }

    /**
     * Validates email validation
     * @param string $value
     * @return boolean returns true if validated else false
     */
    public static function validateEmail($value) {
        $value = filter_var($value, FILTER_SANITIZE_EMAIL);
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Validates Boolean validation
     * @param type $value
     * @return boolean returns true if validated else false
     */
    public static function validateBoolean($value) {
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        if (filter_var($value, FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }
        return false;
    }

    /**
     * Validates Float validation
     * @param type $value
     * @return boolean returns true if validated else false
     */
    public static function validateFloat($value) {
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT);
        if (filter_var($value, FILTER_VALIDATE_FLOAT)) {
            return true;
        }
        return false;
    }

    /**
     * Validates integer validation
     * @param type $value
     * @return boolean returns true if validated else false
     */
    public static function validateInt($value) {
        $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        if (filter_var($value, FILTER_VALIDATE_INT)) {
            return true;
        }
        return false;
    }

    /**
     * Validates IP Address validation
     * @param type $value
     * @return boolean returns true if validated else false
     */
    public static function validateIp($value) {
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        if (filter_var($value, FILTER_VALIDATE_IP)) {
            return true;
        }
        return false;
    }

    /**
     * Validates URL validation
     * @param type $value
     * @return boolean returns true if validated else false
     */
    public static function validateUrl($value) {
        $value = filter_var($value, FILTER_SANITIZE_URL);
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }

    /**
     * Validates Regexp validation
     * @param type $value
     * @return boolean returns true if validated else false
     */
    public static function validateRegexp($value) {
        if (filter_var($value, FILTER_VALIDATE_REGEXP)) {
            return true;
        }
        return false;
    }

}

?>