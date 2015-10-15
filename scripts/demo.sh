#!/bin/bash

echo "Please make sure you've downloaded the JS Serveros and run scripts/servers.sh"
echo
echo "Running the PHP Consumer against the Node Master/Provider"
php demo/consumerTest.php

php -S localhost:8000 >/dev/null 2>&1 &
echo
echo "Running the PHP Consumer against the Node Master and a PHP Provider:"
echo
php demo/consumerProvider.php

jobs -p | xargs kill
