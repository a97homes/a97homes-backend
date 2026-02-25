<?php

namespace App\Enums\Attribute;

enum AttributeTypeEnum: string
{
    case NUMBER = 'number';
    case STRING = 'string';
    case BOOLEAN = 'boolean';
    case DATE = 'date';
    case TEXT = 'text';
    case SELECT = 'select';
}
