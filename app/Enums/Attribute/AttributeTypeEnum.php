<?php

namespace App\Enums\Attribute;

enum AttributeTypeEnum: string
{
    case Number = 'number';
    case String = 'string';
    case Boolean = 'boolean';
    case Date = 'date';
    case Text = 'text';
    case Select = 'select';
}
