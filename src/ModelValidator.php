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

use Avalon\Validation\Validator;

/**
 * Model validation class.
 *
 * @package Avalon\Validation
 * @author Jack P.
 * @since 2.0.0
 */
class ModelValidator extends Validator
{
    /**
     * @var object
     */
    protected $model;

    /**
     * @param array  $validations
     * @param object $model
     */
    public function __construct($validations, $model)
    {
        if (!is_object($model)) {
            throw new InvalidArgumentException("Argument 2 of ModelValidator::__construct() must be an object");
        }

        parent::__construct($validations, $model);
        $this->model = get_class($model);
    }

    /**
     * @param string $field
     * @param mixed  $value
     *
     * @return boolean true if passed otherwise false
     */
    public function unique($field, $value)
    {
        $model = $this->model;
        if ($model::find($field, $value)) {
            $this->errors[$field][] = 'unique';
            return false;
        }

        return true;
    }
}
