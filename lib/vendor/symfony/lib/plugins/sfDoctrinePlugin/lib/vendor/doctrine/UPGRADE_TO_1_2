# Upgrade from 1.1 to 1.2

This document details the changes made to Doctrine 1.2 to make it easier for you
to upgrade your projects to use this version.

## Removed Attribute String Support

The string support of `Doctrine_Configurable::getAttribute()` and 
`Doctrine_Configurable::setAttribute()` have been removed.

The reason is that the performance of this simple convenience feature is 
significant and it is totally unneeded.

The following code is no longer supported.

    [php]
    $connection->setAttribute('use_native_enum', true);

You must now always use the following code.

    [php]
    $connection->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);

## Removed all Deprecated Functions

Removed all functions labeled `@deprecated` in the doc blocks.

Affected Classes

* Doctrine_Query
* Doctrine_Query_Abstract

## Renamed Methods

* Doctrine_Query_Abstract::getSql() to getSqlQuery()
* Doctrine_Search_Query::getSql() to getSqlQuery()
* Doctrine_Query_Abstract::*getCountQuery
* 
* 
* 
* 
* 
() to getCountSqlQuery()
* Doctrine_RawSql::getCountQuery() to getCountSqlQuery()

## Added ability to configure table class

Added ability to specify the base `Doctrine_Table` class to use if no custom 
`UserModelTable` class exists.

    [php]
    $manager->setAttribute(Doctrine_Core::ATTR_TABLE_CLASS, 'MyTable');

Now just make sure the class exists somewhere and is loaded.

    [php]
    class MyTable extends Doctrine_Table
    {

    }

This attribute value will be used for the following new option if you don't 
specify it.

    [php]
    $builder = new Doctrine_Import_Builder();
    $builder->setOption('baseTableClassName', 'MyBaseTable');

## Added ability to configure query class

Before Doctrine 1.2 it was hard coded internally to always use the 
`Doctrine_Query` class whenever you instantiate a new query. Now you can 
configure which class this is to use by setting the `Doctrine_Core::ATTR_QUERY_CLASS` 
attribute.

    [php]
    class MyQuery extends Doctrine_Query
    {
      
    }

    $manager->setAttribute(Doctrine_Core::ATTR_QUERY_CLASS, 'MyQuery');

    $q = Doctrine_Query::create();

    echo get_class($q); // MyQuery

## Changed Doctrine_Parser_Xml::arrayToXml() to be static

Now the `arrayToXml()` method can be called directly because it is static.

    [php]
    $array = array(
      'key1' => 'value',
      'key2' => 'value'
    );
    $xml = Doctrine_Parser_Xml::arrayToXml($array);

## Refactored Migrations to better handle multiple connections

Now when working with `Doctrine_Migration` instance you can specify as the 
second argument to the constructor the connection instance to use for the 
migrations.

Migrations for different databases should be handled with a different set of 
migration classes.

Previous method of finding connection based on the table name is flawed since 
databases could have the same table name multiple times.

    [php]
    $conn = Doctrine_Manager::connection();
    $migration = new Doctrine_Migration('/path/to/migrations', $conn);

## Added option for save cascading

Added a new attribute to control whether cascading save operations are done by 
default. Previous to Doctrine 1.2 they were always cascaded.

As of 1.2 you have the option to disable cascading saves and will only cascade 
if the record is dirty. The cost of this is that you can't cascade and save 
records who are dirty that are more then one level deep in the hierarchy.

See: http://trac.doctrine-project.org/ticket/1623

You can disable cascading saves with the following code.

    [php]
    $manager->setAttribute(Doctrine_Core::ATTR_CASCADE_SAVES, false);

Disabling this will increase performance significantly when saving objects.

## Added Doctrine_Core::setPath()

Now you can specify the path to your Doctrine libraries if Doctrine.php is 
outside of the location of your libraries.

So if `Doctrine.php` is located at `/path/to/Doctrine.php` and the actual 
libraries are at `/path/to/the/doctrine/libs` you would need to do the 
following.

    [php]
    Doctrine_Core::setPath('/path/to/the/doctrine/libs');

## Ability to clear an individual reference

Previously the `Doctrine_Record::clearRelated()` would only allow the clearing 
of ALL references. It will now accept a relationship name and you can clear an 
individual reference.

    [php]
    $user->clearRelated('Phonenumber');

## Check related exists

Often you want to check if a relationship exists in the database, but if it 
doesn't exist you get a newly created blank relationship that will try to be 
saved when you call save on the parent record. Use the new `relatedExists()` 
method to check to avoid this behavior.

    [php]
    if ($user->relatedExists('Profile')) {
      // do something if the user has a profile
    }

## Reverse Engineered Columns

If Doctrine does not recognize a column from a database when reverse engineering 
a schema, instead of throwing an exception, it will default to a string.

This allows custom column types or proprietary column types to be reverse 
engineered without stopping the schema from being built completely.

## Oracle Adapter Persistent Connections

The `Doctrine_Adapter_Oracle` now will use persistent connections if specified.

    [php]
    $info = array(
      'oracle:dbname=SID;charset=NLS_CHARACTERSET;persistent=true',
      'usr',
      'pass'
    );

    Doctrine_Manager::connection($info, 'conn_name');

## New Class/File Prefix Option for Model Builder

You can now set a prefix for your generated models and choose to not have the 
generated filename include that prefix as well.

    [php]
    $builder = new Doctrine_Import_Builder();
    $builder->setOption('classPrefixFiles', false);
    $builder->setOption('classPrefix', 'MyClassPrefix_');

Without the first option you'd get a file like `MyClassPrefix_ModelName.php` but 
now you will get `ModelName.php` with a class named `MyClassPrefix_ModelName` 
inside.

## Expanded Magic Finders to Multiple Fields

You can now `findBy` multiple fields and specify conditions between the fields.

    [php]
    $user = $userTable->findOneByUsernameAndPassword('jwage', md5('changeme'));

Or you could do something like the following and find admin users and moderator 
users.

    [php]
    $users = $userTable->findByIsAdminOrIsModerator(true, true);

You can mix the conditions.

    [php]
    $users = $userTable->findByIsAdminAndIsModeratorOrIsSuperAdmin(true, true, true);

> **CAUTION**
> These are very limited magic finders and it is always recommended to expand 
> your queries to be manually written DQL queries. These methods are meant for 
> only quickly accessing single records, no relationships, and are good for 
> prototyping code quickly.

## Custom Collection Class

You can now specify a custom child class to use for all collections inside 
Doctrine.

    [php]
    $manager->setAttribute(Doctrine_Core::ATTR_COLLECTION_CLASS, 'MyCollection');
    
    $phonenumbers = $user->Phonenumbers;
    echo get_class($phonenumbers); // MyCollection

Now define the simple child class.

    [php]
    class MyCollection extends Doctrine_Collection
    {
      
    }

This option can be set at the manager, connection and table levels.

## Custom Hydrators

As of Doctrine 1.2 it is now possible to register your own custom data 
hydrators. The core hydration process has been decoupled to proper drivers and 
now you can register your own to handle the hydration process.

First lets register our custom hydrator class.

    [php]
    $manager->registerHydrator('MyHydrator', 'Doctrine_Hydrator_MyHydrator');

So now we need to define a hydrator class named `MyHydrator` and it must 
implement a method named `hydrateResultSet($stmt)` method which accepts a 
query statement object.

    [php]
    class Doctrine_Hydrator_MyHydrator extends Doctrine_Hydrator_Abstract
    {
        public function hydrateResultSet($stmt)
        {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

Now you can run a query like the following which would use the new `MyHydrator`.

    [php]
    $q->execute(array(), 'MyHydrator');

## Custom Connections

You can now write and register your own custom Doctrine connection drivers and 
adapters.

    [php]
    class Doctrine_Connection_Test extends Doctrine_Connection_Common
    {
    }

    class Doctrine_Adapter_Test implements Doctrine_Adapter_Interface
    {
      // ... all the methods defined in the interface
    }

Now we can register this with Doctrine so we can utilize it as our connection.

    [php]
    $manager->registerConnectionDriver('test', 'Doctrine_Connection_Test');

Now you can utilize that type of connection in your DSN when connecting.

    [php]
    $conn = $manager->openConnection('test://username:password@localhost/dbname');

Now if we were to check what classes are used for the connection you will notice
that they are the classes we defined above.

    [php]
    echo get_class($conn); // Doctrine_Connection_Test
    echo get_class($conn->getDbh()); // Doctrine_Adapter_Test

## Doctrine Extensions

Doctrine now has support for creating, loading and testing extensions in to your 
projects.

First we need to simply tell `Doctrine` where the extensions are being loaded 
from.

    [php]
    Doctrine_Core::setExtensionsPath('/path/to/extensions');

Now we can check out one of the first available extensions in to our extensions 
directory and then register it.

    $ svn co http://svn.doctrine-project.org/extensions/Sortable/branches/1.2-1.0/ /path/to/extensions/Sortable

The directory structure of this extension looks like the following.

    Sortable/
    	lib/
    		Doctrine/
    			Template/
    				Listener/
    					Sortable.php
    				Sortable.php
    	tests/
    		run.php
    		Template/
    			SortableTestCase.php

You can even run the tests that come bundled with it. We just need to tell your 
CLI where your Doctrine code is.

    $ export DOCTRINE_DIR=/path/to/doctrine

> **NOTE**
> The above path to Doctrine must be the path to the main folder, not just the 
> lib folder. In order to run the tests it must have access to the `tests` 
> directory included with Doctrine.

Now you can run the tests included.

    $ cd /path/to/extensions/Sortable/tests
    $ php run.php

It should output something like the following.

    Doctrine Unit Tests
    ===================
    Doctrine_Template_Sortable_TestCase.............................................passed

    Tested: 1 test cases.
    Successes: 26 passes.
    Failures: 0 fails.
    Number of new Failures: 0 
    Number of fixed Failures: 0 

    Tests ran in 1 seconds and used 13024.9414062 KB of memory

Now if you want to use the extension in your project you will need register the 
extension with Doctrine and setup the extension autoloading mechanism.

First lets setup the extension autoloading.

    [php]
    spl_autoload_register(array('Doctrine', 'extensionsAutoload'));

Now you can register the extension and the classes inside that extension will be 
autoloaded.

    [php]
    $manager->registerExtension('Sortable');

> **NOTE**
> If you need to register an extension from a different location you can specify 
> the full path to the extension directory as the second argument to the 
> `registerExtension()` method.

## Generator Cascading Delete Configuration

It is now possible to configure the cascading delete operation of a 
`Doctrine_Record_Generator`. For example you can now configure the `I18n` 
behavior to use app level cascade deletes instead of database level.

    [yml]
    Article:
      actAs:
        I18n:
          fields: [title, body]
          appLevelDelete: true
      columns:
        title: string(255)
        body: clob

You can also completely disable cascading deletes by using the `cascadeDelete` 
option and setting it to `false.`

## Column Aggregation Key Column

The column aggregation key column is now automatically indexed.

    [yml]
    User:
      tableName: users
      columns:
        username: string(255)
        password: string(255)

    Employee:
      inheritance:
        extends: User
        type: column_aggregation

The above schema would add a `type` column to the `User` model. This is a flag 
tell Doctrine which subclass each record in the database belongs to. This column
is now automatically indexed where before it was not.

    [sql]
    CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, username VARCHAR(255), password VARCHAR(255), type VARCHAR(255));
    CREATE INDEX type_idx ON users (type);

## On Demand Hydration

You can now utilize a new hydration mode which utilizes much less memory. It only
hydrates one object in to memory at a time. So it uses less memory and is faster
for larger results.

    [php]
    // Returns instance of Doctrine_Collection_OnDemand
    $result = $q->execute(array(), Doctrine_Core::HYDRATE_ON_DEMAND);
    foreach ($result as $obj) {
        // ...
    }

`Doctrine_Collection_OnDemand` hydrates each object one at a time as you iterate
over it.

## Aggregate Values Hydration

Now aggregate/calculated values can only be found in the root component of your 
queries. Previously they could be found in both the root component and the 
relationship. This causes lots of problems, for example this query.

    [php]
    $master = Doctrine_Query::create()
			  ->select('m.*, s.bar AS joe')
			  ->from('Ticket_DC24_Master m')
			  ->innerJoin('m.Ticket_DC24_Servant s')
			  ->where('m.id = 1')
			  ->fetchOne();

This produces a data structure like this.

    Array
    (
        [id] => 1
        [foo] => 6
        [servant_id] =>
        [Ticket_DC24_Servant] => Array
            (
                [id] =>
                [bar] =>
                [joe] => 6
            )

        [joe] => 6
    )

Now we have a blank `Ticket_DC24_Servant` instance and if we were to try and call
`save()` like below we would get an error.

    [php]
    $master->some_field = 'test';
    $master->save();

Now as of 1.2 the structure will only look like this.

    Array
    (
        [id] => 1
        [foo] => 6
        [servant_id] => 1
        [joe] => 6
    )

## PEAR Style Model Loading and Generation

Doctrine 1.2 now has the ability to generate PEAR style naming conventions for your
models and can autoload them.

First we have a new method for setting the path to our models for the autoloader.

    [php]
    Doctrine_Core::setModelsDirectory('/path/to/my/models');

Make sure you have registered the `modelsAutoload()` method:

    [php]
    spl_autoload_register(array('Doctrine', 'modelsAutoload'));

Now when you ask for a class named `My_Test_Model` it will try and autoload it 
from `/path/to/my/models/My/Test/Model.php`.

So now you can autoload PEAR style models, but what about generating them? For this
we had to add a new option to the model builder called `pearStyle`. When this option
is enabled it will string replace any `_` in the path to the file and replace it with the directory
separator `/` right before creating the directories and writing the file.

Here I'll show an example how you can use the options to convert this schema and
what it would result in.

    [yml]
    Article:
      columns:
        title: string(255)
        content: clob

    Article_Category:
      columns:
        name: string(255)

Now if we configure a schema importer like the following.

    [php]
    $import = new Doctrine_Import_Schema();
    $import->setOptions(array(
        'pearStyle' => true,
        'baseClassesDirectory' => null,
        'baseClassPrefix' => 'Base_',
        'classPrefix' => 'MyProject_Models_',
        'classPrefixFiles' => true
    ));
    $import->importSchema('schema.yml', 'yml', 'lib');

This will result in some files and directories like the following.

    lib/
      MyProject/
        Models/
          Article/
            Category.php
          Article.php
          Base/
            Article/
              Category.php
            Article.php

So now you can easily use our autoloader or your own to load our models with the
PEAR style naming convention.

    [php]
    spl_autoload_register(array('Doctrine', 'autoload'));
    Doctrine_Core::setModelsDirectory('lib');

Now if we use this code.

    [php]
    $article = MyProject_Models_Article();

It will autoload the file from `lib/MyProject/Models/Article.php`

## Customizing Column Validators

Often when you generate models from a database, you want to customize and improve
your models by overriding the `setTableDefinition()` and tweaking things. To make
this easier we added a new method to customize column options but not completely
override it.

    [php]
    class User extends BaseUser
    {
        public function setTableDefinition()
        {
            parent::setTableDefinition();

            $this->setColumnOptions('username', array('unique' => true));
        }
    }

## Resetting Manager Instances

Sometimes when performing unit tests using Doctrine, you will want to reset
the static manager instance held in `Doctrine_Manager`.

We've added a static method `resetInstance()` and a public method `reset()`
to help you with this.

    [php]
    $manager1 = Doctrine_Manager::getInstance();
    Doctrine_Manager::resetInstance();
    $manager2 = Doctrine_Manager::getInstance();
    
    // $manager1 !== $manager2

You can also simply reset an instance back to the state when it was first created.

    [php]
    $manager->reset();

## Registering Custom CLI Tasks

Thanks to Dan Bettles, he contributed some refactorings to the Doctrine 1 CLI
to allow us to register custom tasks with the `registerTask()` method.

    [php]
    $cli = new Doctrine_Cli($config);
    $cli->registerTask('/path/to/MyCustomTask', 'my-task');
    $cli->run($_SERVER['argv']);

Now you can execute:

    $ php doctrine my-task

Be sure to define the class like this.

    [php]
    class MyCustomTask extends Doctrine_Task
    {
        public $description       =   'My custom task',
               $requiredArguments =   array('arg1' => 'Required first argument.'),
               $optionalArguments =   array('arg2' => 'Optional second argument.');

        public function execute()
        {
            $arg1 = $this->getArgument('arg1');
            $arg2 = $this->getArgument('arg2');
        }
    }

## Doctrine Nested Set Hierarchy Structure

When working with the `NestedSet` behavior in Doctrine 1.2 it has some very nice
features that make working with hierarchical data very easy. One of the things
it has always missed is a way to hydrate that data in to a hierarchical structure.
With 1.2 this is now possible with some new hydration types.

    [php]
    $categories = Doctrine_Core::getTable('Category')
        ->createQuery('c')
        ->execute(array(), Doctrine_Core::HYDRATE_RECORD_HIERARCHY);

Now you can access the children of a record by accessing the mapped value property
named `__children`. It is named with the underscores prefixed to avoid any conflicts.

    [php]
    foreach ($categories->getFirst()->get('__children') as $child) {
        // ...
    }

You can also execute this structure using array hydration and get back the same
structure except as an array.

    [php]
    $results = Doctrine_Core::getTable('NestedSetTest_SingleRootNode')
        ->createQuery('n')
        ->execute(array(), Doctrine_Core::HYDRATE_ARRAY_HIERARCHY);

If you have an existing `Doctrine_Colletion` instance you can convert that to a
hierarchy as well.

    [php]
    $hierarchy = $coll->toHierarchy();

The hierarchy a collection can be converted to an array as well.

    [php]
    print_r($hierarchy->toArray());

## Moved Doctrine to Doctrine_Core

For integration purposes we have deprecated the `Doctrine` class and moved it to
`Doctrine_Core`. The old class still exists and extends `Doctrine_Core` for BC.

## Specify Relationship Foreign Key Name

Up until now, Doctrine would always try and generate a foreign key name for you
for your relationships. Sometimes you may want to customize this name or Doctrine
generates a name that is too long for you. You can customize the foreign key name
now with the `foreignKeyName` option.

    [php]
    public function setUp()
    {
        $this->hasOne('User', array(
            'local' => 'user_id',
            'foreign' => 'id',
            'foreignKeyName' => 'user_id_fk'
        ));
    }

Or in YAML you can do the following.

    [yml]
    Profile:
      columns:
      # ...
        user_id: integer
      relations:
        User:
          foreignKeyName: user_id_fk

## Sluggable Provider Option

You can now use a provider option to the `Sluggable` behavior so you can customize
the PHP code that is used to generate a slug for a record.

    [yml]
    Article:
    # ...
      actAs:
        Sluggable:
          provider: [MyClass, provideSlug]

Now you must have some PHP code like this.

    [php]
    class MyClass
    {
        public static function provideSlug(Doctrine_Record $record)
        {
            // return something
        }
    }

## Migrations Primary Key Convenience Methods

To ease the process of creating and dropping primary keys in Doctrine when using
migrations we've implemented two convenience methods named `createPrimaryKey()` 
and `dropPrimaryKey()`.

You can use create primary keys like the following.

    [php]
    $columns = array(
        'id' => array(
            'type' => 'integer',
            'autoincrement' => true
         )
    );
    $this->createPrimaryKey('my_table', $columns);

If you want to drop the primary key you can do the following.

    [php]
    $this->dropPrimaryKey('my_table', array('id'));

You can also use the automation helper with these methods.

    [php]
    class MyMigration extends Doctrine_Migration_Base
    {
        public function migrate($direction)
        {
            $columns = array(
                'id' => array(
                    'type' => 'integer',
                    'autoincrement' => true
                 )
            );
            $this->primaryKey('my_table', $columns);
        }
    }

The above migration will create the primary key when migrating up and drop it when
migrating down.

## Fixed changeColumn() Argument Order in Migrations

Previously the order of `changeColumn()` was not in the order you would expect.

    [php]
    public function changeColumn($tableName, $columnName, $length = null, $type = null, array $options = array())

Notice how the `$length` is before `$type`. Everywhere else in Doctrine that's 
how it is. So for this version we have fixed that.

    [php]
    public function changeColumn($tableName, $columnName, $type = null, $length = null, array $options = array())

## Ordering Relationships

It is now possible to set a default order by for your relationships. The order by
is automatically included in the final SQL when writing DQL queries or lazily
fetching relationships.

    [yml]
    User:
      columns:
        username: string(255)
        password: string(255)
      relations:
        Articles:
          class: Article
          local: id
          foreign: user_id
          type: many
          foreignType: one
          orderBy: title ASC
    
    Article:
      columns:
        title: string(255)
        content: clob
        user_id: integer

Now if we were to do the following PHP we'll get the SQL with an order by.

    [php]
    $q = Doctrine::getTable('User')
        ->createQuery('u')
        ->leftJoin('u.Articles a');

    echo $q->getSqlQuery() . "\n\n";

Now you should see this SQL query.

    [sql]
    SELECT u.id AS u__id, u.username AS u__username, u.password AS u__password, a.id AS a__id, a.title AS a__title, a.content AS a__content, a.user_id AS a__user_id FROM user u LEFT JOIN article a ON u.id = a.user_id ORDER BY a.title ASC

Or if you lazily fetch the `Articles` they will be lazily loaded with the order by.

    [php]
    $user = Doctrine::getTable('User')->find(1);
    $articles = $user->Articles;

This would execute the following SQL query.

    [sql]
    SELECT a.id AS a__id, a.title AS a__title, a.content AS a__content, a.user_id AS a__user_id FROM article a WHERE (a.user_id IN (?)) ORDER BY a.title ASC

You can also specify the default order by for a model itself instead of on the relationship.

    [yml]
    Article:
      options:
        orderBy: title ASC
      columns:
        title: string(255)

Now any query involving the `Article` model will have that order by.

## Result Cache Improvements

In Doctrine when you use result caching, it stores the results of a query in the
cache driver the first time it executes and retrieves it from the cache for all
subsequent requests. The key for this cache entry in the driver is automatically
generated so it is hard for you to identify a single entry and clear it manually.

In Doctrine 1.2 we added the ability to set the result cache hash/key used.

    [php]
    $q = Doctrine_Query::create()
        ->from('User u')
        ->useResultCache(true, 3600, 'user_list');

If you want to manually clear the item from the query object you can do.

    [php]
    $q->clearResultCache();

Or if you have the cache driver you could delete it by the key.

    [php]
    $cacheDriver->delete('user_list');

You can also use the `setResultCacheHash()` to set the key used to store the cache
entry.

    [php]
    $q = Doctrine_Query::create()
        ->from('User u')
        ->useResultCache(true, 3600);

    $q->setResultCacheHash('user_list');

You can also now delete cache entries from the cache drivers using PHP regular
expressions.

    [php]
    $cacheDriver = new Doctrine_Cache_Apc();

    $cacheDriver->save('my_cache_one');
    $cacheDriver->save('my_cache_two');
    $cacheDriver->save('my_cache_three');

    echo $cacheDriver->deleteByRegex('/my_cache_.*/'); // 3

If you're not a regular expression master and just want to simply delete with 
some wild cards then you can use the `*` character with the normal `delete()`
method and we'll build the regular expression for you and call `deleteByRegex()`.

    [php]
    echo $cacheDriver->delete('my_cache_*');

Since `preg_match()` is pretty slow the above example would not perform well
with lots of cache keys to compare to the regular expression so you can
alternatively use the `deleteByPrefix()` and `deleteBySuffix()` method if
that is sufficient enough for you.

    [php]
    echo $cacheDriver->deleteByPrefix('my_cache_');

## BLOB and File Handle Resources

It is now possible to provide the contents for a blob column through a file
handle resource.

    [php]
    $file = new File();
    $file->binary_data = file('/path/to/file');
    $file->save();

## Symfony sfYaml External

Doctrine is now using the Symfony Component `sfYaml` for our YAML parsing as an 
SVN external. So it will always be up to date and receive bug fixes from Symfony.

## Better replace() Support

The `Doctrine_Record::replace()` had a lot of issues previously as it did not 
behave the same as if you were to call `save()`, `update()`, or `insert()`. 
Now when you call `replace()` all the same events will be triggered and the
saving of the graph will be triggered just as if you were using `save()`.

    [php]
    $user = new User();
    $user->username = 'jonwage';
    $user->password = 'changeme2';
    $user->replace();

## Added hardDelete() method to SoftDelete

Something missing from the `SoftDelete` behavior was the ability to force the 
deletion of a record in the database. For this we have added a new `hardDelete()`
method.

    [php]
    $user = new User();
    $user->username = 'jwage';
    $user->password = 'changeme';
    $user->save();
    $user->delete() // Doesn't actually delete, sets deleted_at flag
    
    $user->hardDelete(); // Will actually delete the record from the database.

## Added MySQL SET Support

Just like the `enum` type for MySQL we also now support the `set` type. It basically
very similar to `enum` but instead of being able to store only one value you can
store multiple of the possible values.

    [yml]
    User:
      columns:
        username: string(255)
        password: string(255)
        permissions:
          type: set
          values: [admin, member, moderator, banned]

Now you can do the following:

    [php]
    $user = new User();
    $user->username = 'jwage';
    $user->password = 'changeme';
    $user->permissions = array('admin', 'member');

## Models Autoloading

In Doctrine 1.2 the models autoloading was moved to a custom autoloader for more
flexibility and not forcing the models autoloading on users if they don't want to
use it. If you still wish to use the Doctrine model autoloading then you must
use the following code:

    [php]
    spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));