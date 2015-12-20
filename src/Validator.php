<?php
/*!
 * Avalon
 * Copyright 2011-2015 Jack P.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Avalon\Validation;

use Respect\Validation\Validator as v;

/**
 * Validation class.
 *
 * @package Avalon\Validation
 * @author Jack P.
 * @since 2.0.0
 */
class Validator
{
    /**
     * Validation errors.
     *
     * @var array
     */
    public $errors = [];

    /**
     * Data object, either a model or array typecasted to an object.
     *
     * @var object
     */
    protected $data;

    /**
     * Validations to run.
     *
     * @var array
     */
    protected $validations;

    /**
     * @param array $validations
     * @param mixed $data
     */
    public function __construct($validations, $data)
    {
        $this->data = (object) $data;
        $this->validations = $validations;

    }

    /**
     * @return boolean
     */
    public function validate()
    {
        foreach ($this->validations as $field => $validations) {
            $this->_errors[$field] = [];

            foreach ($validations as $validation => $options) {
                if (is_numeric($validation)) {
                    $validation = $options;
                    $options = null;
                }

                if (method_exists($this, $validation)) {
                    if (!isset($this->data->{$field})) {
                        $this->data->{$field} = null;
                    }

                    $this->{$validation}($field, $this->data->{$field}, $options);
                }
            }

        }

        return !count($this->errors);
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return boolean true if passed otherwise false
     */
    public function required($field, $value)
    {
        if (!v::notEmpty()->validate($value)) {
            $this->errors[$field][] = 'required';
            return false;
        }

        return true;
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return boolean true if passed otherwise false
     */
    public function minLength($field, $value, $options)
    {
        if (!v::length($options)->validate($value)) {
            $this->errors[$field]['minLength'] = ['minLength' => $options];
            return false;
        }

        return true;
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return boolean true if passed otherwise false
     */
    public function email($field, $value)
    {
        if (!v::email()->validate($value)) {
            $this->errors[$field][] = 'email';
            return false;
        }

        return true;
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return boolean true if passed otherwise false
     */
    public function noWhitespace($field, $value)
    {
        if (!v::noWhitespace()->validate($value)) {
            $this->errors[$field][] = 'noWhitespace';
            return false;
        }

        return true;
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return boolean true if passed otherwise false
     */
    public function integer($field, $value)
    {
        if (!v::intVal()->validate($value)) {
            $this->errors[$field][] = 'integer';
            return false;
        }

        return true;
    }
}
