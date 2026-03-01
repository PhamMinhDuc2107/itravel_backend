<?php

namespace App\Constants;

class QueryParam
{
    public const string FILTER_KEY = 'filter';
    public const string OPERATOR_EQ = 'eq';
    public const string OPERATOR_NE = 'ne';
    public const string OPERATOR_LIKE = 'like';
    public const string OPERATOR_NOT_LIKE = 'not_like';
    public const string OPERATOR_GT = 'gt';
    public const string OPERATOR_LT = 'lt';
    public const string OPERATOR_GTE = 'gte';
    public const string OPERATOR_LTE = 'lte';
    public const string OPERATOR_IN = 'in';
    public const string OPERATOR_NOT_IN = 'not_in';
    public const string OPERATOR_BETWEEN = 'between';
    public const string OPERATOR_NOT_BETWEEN = 'not_between';

    public const array OPERATORS = [
        self::OPERATOR_EQ,
        self::OPERATOR_NE,
        self::OPERATOR_LIKE,
        self::OPERATOR_NOT_LIKE,
        self::OPERATOR_GT,
        self::OPERATOR_LT,
        self::OPERATOR_GTE,
        self::OPERATOR_LTE,
        self::OPERATOR_IN,
        self::OPERATOR_NOT_IN,
        self::OPERATOR_BETWEEN,
        self::OPERATOR_NOT_BETWEEN,
    ];
}
