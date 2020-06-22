# Path set
[![Build Status](https://travis-ci.org/opxcore/path-set.svg?branch=master)](https://travis-ci.org/opxcore/path-set)
[![Coverage Status](https://coveralls.io/repos/github/opxcore/path-set/badge.svg?branch=master)](https://coveralls.io/github/opxcore/path-set?branch=master)
[![Latest Stable Version](https://poser.pugx.org/opxcore/path-set/v)](//packagist.org/packages/opxcore/path-set)
[![Total Downloads](https://poser.pugx.org/opxcore/path-set/downloads)](//packagist.org/packages/opxcore/path-set) 
[![License](https://poser.pugx.org/opxcore/path-set/license)](//packagist.org/packages/opxcore/path-set)

Path set is an abstraction level for defining sets of path collections, e.g. search paths.
Each `Path` is associated with primary path and alternates. In the `PathSet` each path is associated with name. Getting
set for some name will return array of registered paths for this name in backward order without duplicates.
The `'*'` name is a global path set and will be included in all other sets.

##Installing
`composer require opxcore/path-set`

##Examples
Simple usage:
```
$pathSet = new PathSet;
$pathSet->add('name', 'primary', ['alternate_1', 'alternate_2']);
$set = $pathSet->get('name');
// $set = [
//   'alternate_2', 
//   'alternate_1', 
//   'primary'
// ]
```
Defining with a constructor:
```
$pathSet = new PathSet(['name', 'primary', ['alternate_1', 'alternate_2']]);
$set = $pathSet->get('name');
// $set = [
//   'alternate_2', 
//   'alternate_1', 
//   'primary'
// ]
```
Usage with a global name:
```
$pathSet = new PathSet;
$pathSet->add('*', 'global_primary', ['global_alternate_1', 'global_alternate_2']);
$pathSet->add('name', 'primary', ['alternate_1', 'alternate_2']);
$set = $pathSet->get('name');
// $set = [
//   'alternate_2',
//   'alternate_1',
//   'primary',
//   'global_alternate_2',
//   'global_alternate_1',
//   'global_primary'
// ]
```