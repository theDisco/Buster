[![Build Status](https://travis-ci.org/theDisco/Buster.svg?branch=master)](https://travis-ci.org/theDisco/Buster)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/theDisco/Buster/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/theDisco/Buster/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/theDisco/Buster/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/theDisco/Buster/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/0225fa62-a96b-454e-a923-7df5ebaf5485/mini.png)](https://insight.sensiolabs.com/projects/0225fa62-a96b-454e-a923-7df5ebaf5485)

.scrutinizer.yml

What is Buster?
===============

Buster aims at automatizing the repeatedly executed tasks in a development flow.
It was build with git flow in mind, therefore it fits perfectly in any project using
git as version control system.

Pre Commit Hook
===============

Available Executors
-------------------

* Lint
* PHP Code Sniffer
* PHP Mess Detector
* PHP Unit

Installation
------------

Create a file called `pre-commit` in the root folder of your project

```
touch pre-commit && chmod +x pre-commit
```

File should have following content

```php
#!/usr/bin/env php
<?php

$workingDir = __DIR__;

require $workingDir . '/vendor/autoload.php';

$manager = new \Buster\Manager(new \Buster\Git\PreCommit, $workingDir);
$manager->addExecutor(new \Buster\Executor\Lint);
$manager->addExecutor(new \Buster\Executor\PhpCodeSniffer($workingDir . '/vendor/bin/phpcs'));
$manager->addExecutor(new \Buster\Executor\PhpMessDetector($workingDir . '/vendor/bin/phpmd'));
$manager->addExecutor(new \Buster\Executor\PhpUnit($workingDir . '/vendor/bin/phpunit'));
$exitCode = $manager->execute();

if ($exitCode > 0) {
    exit($exitCode);
}
```

After adding some files to the git and running `git commit` you should see Buster running
the validation of the files to be commit. If everything goes well, you will be asked to
provide a commit message. If there were any errors, Buster will inform you about it and
ask you to fix them.

Executors
=========

Lint
----

TODO

PHP Code Sniffer
----------------

TODO

PHP Mess Detector
-----------------

TODO

PHP Unit
--------

TODO

TODO
====

* write read me
* provide installation scripts
* provide other executors for git hooks (composer, db migration, ...)
