
This represents the attempt to provide a solution to The Task.

1. Layout of the project.

1.1 - Configuration
The only file which should require significant editing by the user is:
Y_CONFIG/database.php

The user will need to populate this with the expected database details; and with the API KEY which has been removed to hide it from public repository.

LATE-BREAKING.
The URL of the API has been obfuscated to remove an organisation's name.  The user will need to restore it by
replacing the strings PERSONSNAME and SERVERNAME with the appropriate values in

testimport.php
line 66


1.2 - Rationale.

The specification requests that no framework be used; and infers that a functioning application is not the object of the exercise.  The material is therefore presented as a collection of PHP files, with no attempt to impose an overarching application on top of them.

Each 'function' is therfore represented by a single PHP file, which makes the finished object awkward and ugly to operate but does not require the reviewer to struggle with application layout.  We may in fact consider each 'file'(aka ''module') to be a Controller.

1.3 - Components and Modernity.

Where useful, 3rd party components have been used.

i. Eloquent
This is an ORM , also used in laravel, which provides database abstraction and persistance.
The use of an ORM means that we are not concerned with SQL injection; moreover, the simple database operation required by this project can be easily and intuitively executed in a modern an object-oriented manner.

ii. Guzzle
This is an HHTP client once again often included in Laravel projects.  Its advantage over a standard PHP HTTP call is that Guzzle can be made to execute sunchronous queries (of which more later)

1.4 - Style.

For ease of reading and to remove accusation of overcomplication, relatively few classes are used in this project.  This is justfied as follows:

i. There is nothing intrinsically 'good' about object-orientation sugar when there is no convincing reason for it.  The project, for instance, consumes only one API, and such would not benefit from the API functionality being abstracted for ease of overloading or subclassing  A plain-old PHP function wrapped in a class and now called a 'method' does nothing that a PHP function cannot do.  We have already established that this project is a concept demonstrator and not intended as a deliverable application; if it were to be so, it is a much easier task to factor the PHP files into classes and methods than it would be to disentangle a cosmetic class hieracy.

ii. OO Where Necessary.  The use of Eloquent provides adequate chances to show that OO principles are understood. 

iii. Overengineered is not Better.  


2. Running the Demonstrator.


2.1 - Setup
Once the repository has been pulled to a known location, the user should open a terminal in that location and install the necessary libraries by executing

composer install.

This will populate the VENDORS directory.

The user should then edit Y_CONFIG/database.php, filling in the necessary details including the api key.

Next, the user should ensure that the named database exists on the named server, and the appropriate user has access.

The user should then execute a test-server instruction which does the same job as:

php -S 127.0.0.1:5678 (port at users' discretion)


2.1 - Operations
The  user may then move to a browser, and copy-paste this URL into the bar:

http://127.0.0.1:5678/maketables.php

The user may then check that the table //properties// has been made in the database.


The user should then use the following function:
http://127.0.0.1:5678/testimport.php

to pull records from the API into the local data store.

This function may be run as frequently as necessary.  It works as follows:
1. Make a list of the GUIDs of records already stored in the database.
2. Loop through all the API records, 100 records per request.  If an API with a GUID not present in the database is found, this record is added the THE END OF THE DATABASE; in other words, its position in the list will change.
3. SOFTWARE DESIGN ISSUE: During this first submission, records deleted from the database will BE RESTORED FROM THE API when the testimport function is run.
4. SOFTWARE DESIGN ISSUE: Records deleted from the API will NOT be purged from the database on update.


2.3 - Browsing

The user may use the URL:
http://127.0.0.1:8090/listing.php

To run the very simple database browser.

The records will paginate at 100 records / page.  Trivial navigation is provided.

A record may be deleted from the database with the self-explanatory link.

2.4 - Templating

A very primitive 'template system' has been implemented for this browser.
The three files 
partial-view-footer.html
partial-view-header.html
partial-view-row.html

are supplied which contain tokenised HTML.  In theory, these files alone can be passed to a designer or UX specialist for the purposes of beautifying the browser.




CONCLUSION.

KNOWN ISSUES.

1. No application security.

No 'login screen' or 'registration' form was required by the specification.  None has been attempted for the following reason:

APPLICATION SECURITY IS HARD TO WRITE, HARD TO TEST AND HARD TO IMPLEMENT and is also covered by numerous peer-reviewed Framework Plugins.  Bad application security with its attendant false-security failings is worse than none.

This means that there is no way to prevent any user from reading, writing, deleting or updating any record.  This is most definately a Known Issue within the scope of this demonstrator.

2. No way to keep track of which records have been deleted.

With more time, it would be advisable to make a table / file of GUID's which have been deleted from the database such that they are not re-imported from the API when updates (for new, required records) are requested.

3. No way to delete a record which has been deleted from the API.

With more time, it should be possible to purge from the database historical records no longer present in the API.

4. No check-before record deletion.

5. No caching of off-server images.

The thumbnail images take a very long time to download from their 3rd party hosting.  Given more time, it may be good to implement a caching solution (but does this violate owners' rights...?)

6. Database Normalisation.

Currently, each API record had a 'Type Description' tagged on as a sub-item.  The 'description' field of this sub-item has been interpreted as belonging to the PROPERTY TYPE field in the specified database (the API TYPE field has been understood as mapping on to the SALE/RENT specified field)

It would be far more correct to leverage the API record's TYPE ID field, and make separated table of all of the distinct TYPE descritions (house, terrace, flat) and join them onto their parent record by

Property.typeID = Types.ID


POINTS OF INTEREST.

API consumption is a central feature of almost all modern web applications; as is CRUD.  Both of these things are greatly assisted by modern web application frameworks.

This project specified that a framework not be used.  For this reason there has been no attempt to replicate the Things that can be taken as read to come as part and parcel of any modern framework.

For instance:

Laravel ships with BLADE, and many users like to use TWIG as an alternative.  A tiny and token attempt at 'a template engine' has been iplemented here to demonstrate that the spirit of 'MVC' is observed.

Laravel ships with Eloquent.  In fact, Eloquent has been used in this project; for convenience; and to futher demonstrate that MVC is understood in concept (the Models are represented by wholly encapsulated entities throughout; except that the Models happen to 1-line static function calls!); and also for data-level security.  Because pass-through SQL queries are never used, the application is invulnerable to SQL injection (in fct, because of the level of abstration offered by Eloquent, it is by all means possible to use a No-SQL Document Store, or even a flat-file storage system in the back)

All modern frameworks ship with an auth / login component.  There is no value in re-inventing this wheel (and a greal deal of wasted time and potential harm)

All modern frameworks ship with a tokenising system to prevent direct impingement on HTTP endpoints.  Utilising these is well-understood; implementing them is complex, unrewarding and whilst relatively interesting from the POV of engineering reasearch, does not demonstrate anything that anyone may be reasonably asked to implement on a real project.


TODO.

The project stands at about 60% complete.  Outstanding are the Search, Edit and Update sections.

It is hoped that the work as-is demonstrates the following:

Baseline competence with consuming an API by HTTP
Baseline competence with using an object-realtional means of querying and updating a database
Working knowledge of user intefaces
Ability to acknowledge lacunae and room-for-improvement areas
Ability to produce human-readable documentation.


AFTERWARD.

API IMPORTS

There is a potentially problematic bug in the conception of the import module: it makes the assumption that 
the import will complete before the web server times out.  In this example, only ~3000 records are used, so the 
timeout does  not occur.  But if the record count were 25000 or more, this condition might be reached.

SOLUTIONS.

Most obviously, run the import as timed job.  The PHP module as-is will run happily from the command line
and therefore any scheduling too.

A more subtle solution may be to launch several asynchronous processes.  The guzzle library possesses this 
ability:

$promise = $client->requestAsync('GET', 'http://api-url');
$promise->then(
    function (ResponseInterface $res) {
    	...
    },
    function (RequestException $e) {
    	...
	}
);

In this way, the web server should not itself report a timeout.  The asynch process(es) will take their own time to 
finish, at which point they could conceivably broadcast a message to the client or just log a success / fail message.