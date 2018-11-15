# Demo application

This is an example of my PHP code for recruiters and team leads. You can consider it as a solution for the following task from imaginary customer:

"We need a console application that counts objects in data source. Right now we have to count lines in files. But in future we may need to count records in database table, subscribers in social networks etc.

And one more thing. For now the application can output its progress and results into the console. But I am more than sure that our CTO will want to receive SMS or emails. Or to run another applications. Not sure." 

## Usage

````
php bin/count.php -t=file -e=/etc/hosts
````

Options:
* `-t|--type [required]` Data source type, supported types: `file`
* `-e|--e` Extra data, e.g. file path, table name etc.

## Application features, or What issues have been solved?

### First issue

We don't know what data sources will be used, and how do we count objects in these sources.

### Solution of first issue

Each data source is represented by class that implements `DataSourceInterface`. This interface has two public methods: 
* `__construct($data)`, initializes the object with data provided by `-e` option
* `count()`, returns a number of objects in data source. 

The whole application works with that interface, not with specific classes.

The main problem is a class instantiation. We may need file path, table name, database connection, group ID, social network application token and whatever else.

`Factory method` was chosen as an appropriate pattern for this task. Each data source objects is created by respective factory that implements `DataSourceFactoryInterface`. This interface has one public method `build()` that accepts 1 argument `$data` (provided by `-e` option).

As a result, if a new data source appears, we only need to create 2 new files:
* `src/DataSource/<SourceName>.php` - describes the algorithm of data source initialization (`__construct($data)`) and counting (`count()`)
* `src/DataSource/<SourceName>Factory.php` - describes the algorithm of data source object creation (`build($data)`)

The rest of application will remain untouched.

### Second issue

The customer warned us that in time they may want to complicate the application: sms sending, other scripts execution etc. Adding more and more code to the application main class will make it unmaintainable and untestable.

### Solution of second issue

So, we need to provide extensibility. `Observer` is a great pattern for that task.

Application has a pool of subscribers and broadcasts its events. Each subscriber receives information about each event and decides what to do next: log, send, execute etc.

Thus main application logic is decoupled from additional logic. `Application` class does his job and has no idea what happens in the outside world. Adding new functionality is the matter of creating new subscriber and adding it to application subscribers list.