Set of Doctrine Custom Hydrators
====================================

This repository contains custom hydrators wich may be useful in some ordinary cases.

## Usage

TBD

## Custom Array Hydrators

When you are dealing with read-only operations like "get list of products" for example, there is no much profit from
your entities. What you really want if just data model, your domain object's state represented in some form suitable
for view layer. Also you don't need this objects to be stored in Unif-or-work since they should not be modified by 
request.

Simpliest way to achieve our goal will be use of `ArrayHydrator` to get needed data. But there is some problems in case
if you are using embeddable objects. By default doctrine returns embeddable obects inlined into array:

```php
$row = [
    'id' => 42,
    'name' => 'Some Product Name',
    'price.amount' => '3999',
    'price.currency' => 'USD',
];
```

So we can't just throw this into `json_encode` or `render`. We need some kind of post-processing of the result.

`NormalizedArrayHydrator` is doint just this. It post process result of array hydrator and make this example looks like this:

```php
$row = [
    'id' => 42,
    'name' => 'Some Product Name',
    'price' => [
        'amount' => '3999',
        'currency' => 'USD',
    ],
];
```

So now we could use this data to build representation of it for client's needs.
