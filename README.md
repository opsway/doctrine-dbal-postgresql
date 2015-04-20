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
* GET_JSON_FIELD -  'OpsWay\Doctrine\ORM\Query\AST\Functions\GetJsonField'
* ANY_IN -          'OpsWay\Doctrine\ORM\Query\AST\Functions\Any'

| Custom Name    | PostgreSql  | Usage in DQL                        | Result in SQL                |
|----------------|:-----------:|-------------------------------------|------------------------------|
| CONTAINS       |      @>     | CONTAINS(field, :param)             | (field @> '{value}')         |
| GET_JSON_FIELD |      ->>    | GET_JSON_FIELD(field, 'json_field') | (table_field->>'json_field') |
| ANY_IN         |      ANY    | ANY_IN(field)                       | ANY(field)                   |