#!/bin/bash
curl --location 'http://localhost/api/login' \
--header 'Content-Type: application/json' \
--data-raw '{
    "email" : "a10801@csmiguel.pt",
    "password" : "tmonky"
}'
