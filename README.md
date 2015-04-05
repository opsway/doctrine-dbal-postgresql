doctrine-dbal-postgresql
=========================

This component allows you to manage some native [PostgreSQL](http://www.postgresql.org)
data types with the Doctrine [DBAL](http://www.doctrine-project.org/projects/dbal.html) component.

Usage
-----

Add to composer.json
```bash
php composer.phar require opsway/doctrine-dbal-postgresql:~0.1
```
To use the new types you should register them using the [Custom Mapping Types](https://doctrine-dbal.readthedocs.org/en/latest/reference/types.html#custom-mapping-types) feature.


#### Custom Types

* ArrayInt (integer[])

#### Custom functions

* CONTAINS(field, value) - 'field @> value' 