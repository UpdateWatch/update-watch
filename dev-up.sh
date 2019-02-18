#!/usr/bin/env bash

docker-compose -f docker-compose.dev.yml up -d

echo ""
echo ""

echo "Web: http://127.0.0.1/"

# echo "PHPMyAdmin: http://127.0.0.1:12345/"
echo "Adminer: http://127.0.0.1:12344/"