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

namespace Avalon\Validation\Model;

use Avalon\Language;
use Avalon\Validation\ModelValidator;

/**
 * Model validation trait to easily validate and get translated error messages.
 *
 * @package Avalon\Validation
 * @author Jack P.
 * @since 2.0.0
 */
trait Validatable
{
    /**
     * @return boolean
     */
    public function validate()
    {
        $validator = new ModelValidator(static::$validations, $this);

        if (!$validator->validate()) {
            $this->errors = array_merge_recursive($this->errors, $validator->errors);
        }

        return !count($this->errors);
    }

    /**
     * Get the translated error messages.
     *
     * @return array.
     */
    public function getErrorMessages()
    {
        $messages = [];
        foreach ($this->errors as $field => $errors) {
            $messages[$field] = [];

            foreach ($errors as $error => $options) {
                if (is_numeric($error)) {
                    $error = $options;
                    $options = [];
                }

                $name = Language::translate($field);
                $messages[$field][] = Language::translate("errors.validations.{$error}", ['field' => $name] + $options);
            }
        }

        return $messages;
    }
}
