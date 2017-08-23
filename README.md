doctrine-dbal-postgresql
=========================
[![Latest Stable Version](https://poser.pugx.org/opsway/doctrine-dbal-postgresql/v/stable)](https://packagist.org/packages/opsway/doctrine-dbal-postgresql) [![Total Downloads](https://poser.pugx.org/opsway/doctrine-dbal-postgresql/downloads)](https://packagist.org/packages/opsway/doctrine-dbal-postgresql) [![Latest Unstable Version](https://poser.pugx.org/opsway/doctrine-dbal-postgresql/v/unstable)](https://packagist.org/packages/opsway/doctrine-dbal-postgresql) 

This component allows you to manage some native [PostgreSQL](http://www.postgresql.org)
data types, operators and functions with the Doctrine [DBAL](http://www.doctrine-project.org/projects/dbal.html) component.

Usage
-----

Add to composer.json
```bash
php composer.phar require opsway/doctrine-dbal-postgresql:~0.1
```
To use the new types you should register them using the [Custom Mapping Types](https://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#custom-mapping-types) feature.

To use the new functions you should register them using the [DQL User Defined Functions](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/dql-user-defined-functions.html) feature.

#### Custom Types

* Array Integer (integer[])
* Array BigInt (bigint[])
* TsVector (tsvector)



#### Custom DQL functions

* CONTAINS -              'OpsWay\Doctrine\ORM\Query\AST\Functions\Contains'
* CONTAINED -             'OpsWay\Doctrine\ORM\Query\AST\Functions\Contained'
* GET_JSON_FIELD -        'OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonField'
* GET_JSON_FIELD_BY_KEY - 'OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonFieldByKey'
* GET_JSON_OBJECT -       'OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonObject'
* GET_JSON_OBJECT_TEXT -  'OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonObjectText'
* ANY_OP -                'OpsWay\Doctrine\ORM\Query\AST\Functions\Any'
* ALL_OP -                'OpsWay\Doctrine\ORM\Query\AST\Functions\All'
* ARR -                   'OpsWay\Doctrine\ORM\Query\AST\Functions\Arr'
* ARR_APPEND -            'OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayAppend'
* ARR_REPLACE -           'OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayReplace'
* REGEXP_REPLACE -        'OpsWay\Doctrine\ORM\Query\AST\Functions\RegexpReplace'
* ARR_REMOVE -            'OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayRemove'
* ARR_CONTAINS -          'OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayContains'
* TO_TSQUERY -            'OpsWay\Doctrine\ORM\Query\AST\Functions\ToTsquery'
* TO_TSVECTOR -           'OpsWay\Doctrine\ORM\Query\AST\Functions\ToTsvector'
* TS_CONCAT_OP -          'OpsWay\Doctrine\ORM\Query\AST\Functions\TsConcat'
* TS_MATCH_OP -           'OpsWay\Doctrine\ORM\Query\AST\Functions\TsMatch'
* UNNEST -                'OpsWay\Doctrine\ORM\Query\AST\Functions\Unnest'
* JSON_AGG -              'OpsWay\Doctrine\ORM\Query\AST\Functions\JsonAgg'
* JSONB_ARRAY_ELEM_TEXT - 'OpsWay\Doctrine\ORM\Query\AST\Functions\JsonbArrayElementsText'


| Custom Name           | PostgreSql                | Usage in DQL                               | Result in SQL                    |
|-----------------------|:-------------------------:|--------------------------------------------|----------------------------------|
| CONTAINS              |            @>             | CONTAINS(field, :param)                    | (field @> '{value}')             |
| CONTAINED             |            <@             | CONTAINED(field, :param)                   | (field <@ '{value}')             |
| GET_JSON_FIELD        |            ->>            | GET_JSON_FIELD(field, 'json_field')        | (table_field->>'json_field')     |
| GET_JSON_FIELD_BY_KEY |            ->             | GET_JSON_FIELD_BY_KEY(field, 'json_field') | (table_field->'json_field')      |
| GET_JSON_OBJECT       |            #>             | GET_JSON_OBJECT(field, 'json_field')       | (table_field#>'json_field')      |
| GET_JSON_OBJECT_TEXT  |            #>>            | GET_JSON_OBJECT_TEXT(field, 'json_field')  | (table_field#>>'json_field')     |
| ANY_OP                |            ANY            | ANY_OP(field)                              | ANY(field)                       |
| ALL_OP                |            ALL            | ALL_OP(field)                              | ALL(field)                       |
| ARR                   |           ARRAY           | ARR(field)                                 | ARRAY[field]                     |
| ARR_APPEND            |       array_append        | ARR_APPEND(field, :param)                  | array_append(field, param)       |
| ARR_REPLACE           |       array_replace       | ARR_REPLACE(field, :param1, :param2)       | array_replace(field, p1, p2)     |
| REGEXP_REPLACE        |       regexp_replace      | REGEXP_REPLACE(field, :param1, :param2)    | regexp_replace(field, p1, p2)    |
| ARR_REMOVE            |       array_remove        | ARR_REMOVE(field, :param)                  | array_remove(field, param)       |
| ARR_CONTAINS          |            &&             | ARR_CONTAINS(field, :param)                | (field && param)                 |
| TO_TSQUERY            |        to_tsquery         | TO_TSQUERY(:param)                         | to_tsquery('param')              |
| TO_TSVECTOR           |        to_tsvector        | TO_TSVECTOR(field)                         | to_tsvector(field)               |
| TS_MATCH_OP           |            @@             | TS_MATCH_OP(expr1, expr2)                  | expr1 @@ expr2                   |
| TS_CONCAT_OP          |            ||             | TS_CONCAT_OP(expr1, expr2, ....)           | (expr1 || expr2 || ...)          |
| UNNEST                |          UNNEST           | UNNEST(field)                              | UNNEST(field)                    |
| JSON_AGG              |         json_agg          | JSON_AGG(expression)                       | json_agg(expression)             |
| JSONB_ARRAY_ELEM_TEXT | jsonb_array_elements_text | JSONB_ARRAY_ELEM_TEXT(field, 'json_field') | jsonb_array_elements_text(field) |
