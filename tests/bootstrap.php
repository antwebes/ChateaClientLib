<?php
/*
 * This file is part of the ChateaClientBundle package.
 *
 * (c) ChateaClientBundle <http://github.com/antwebes/chateaClientBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
$file = __DIR__.'/../vendor/autoload.php';
$file_in_project = __DIR__.'/../../../autoload.php';
if (!file_exists($file)) {
    if (!file_exists($file_in_project)) {
        throw new RuntimeException('Install dependencies to run test suite. Or add the in project symfony2');
    }else{
        $file = $file_in_project;
    }
}
$loader = require $file;

$loader->add('Ant\ChateaClient\Tests', __DIR__);
