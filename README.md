doctrine-dbal-postgresql
=========================

This component allows you to manage some native [PostgreSQL](http://www.postgresql.org)
data types, operators and functions with the Doctrine [DBAL](http://www.doctrine-project.org/projects/dbal.html) component.

Usage
-----

Add to composer.json
```bash
php composer.phar require opsway/doctrine-dbal-postgresql:~0.1
```
To use the new types you should register them using the [Custom Mapping Types](https://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#custom-mapping-types) feature.

To use the new types you should register them using the [DQL User Defined Functions](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/cookbook/dql-user-defined-functions.html) feature.

#### Custom Types

* ArrayInt (integer[])



#### Custom DQL functions

* CONTAINS -        'OpsWay\Doctrine\ORM\Query\AST\Functions\Contains'
* CONTAINED -       'OpsWay\Doctrine\ORM\Query\AST\Functions\Contained'
* GET_JSON_FIELD -  'OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonField'
* GET_JSON_OBJECT - 'OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonObject'
* ANY_OP -          'OpsWay\Doctrine\ORM\Query\AST\Functions\Any'
* ALL_OP -          'OpsWay\Doctrine\ORM\Query\AST\Functions\All'
* ARR -             'OpsWay\Doctrine\ORM\Query\AST\Functions\Arr'
* ARR_APPEND -      'OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayAppend'
* ARR_REPLACE -     'OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayReplace'
* ARR_REMOVE -      'OpsWay\Doctrine\ORM\Query\AST\Functions\ArrayRemove'

| Custom Name     | PostgreSql    | Usage in DQL                         | Result in SQL                |
|-----------------|:-------------:|--------------------------------------|------------------------------|
| CONTAINS        |      @>       | CONTAINS(field, :param)              | (field @> '{value}')         |
| CONTAINED       |      <@       | CONTAINED(field, :param)             | (field <@ '{value}')         |
| GET_JSON_FIELD  |      ->>      | GET_JSON_FIELD(field, 'json_field')  | (table_field->>'json_field') |
| GET_JSON_OBJECT |      #>       | GET_JSON_OBJECT(field, 'json_field') | (table_field#>'json_field')  |
| ANY_OP          |      ANY      | ANY_OP(field)                        | ANY(field)                   |
| ALL_OP          |      ALL      | ALL_OP(field)                        | ALL(field)                   |
| ARR             |     ARRAY     | ARR(field)                           | ARRAY[field]                 |
| ARR_APPEND      | array_append  | ARR_APPEND(field, :param)            | array_append(field, param)   |
| ARR_REPLACE     | array_replace | ARR_REPLACE(field, :param1, :param2) | array_replace(field, p1, p2) |
| ARR_REMOVE      | array_remove  | ARR_REMOVE(field, :param)            | array_remove(field, param)   |