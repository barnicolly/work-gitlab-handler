#!/bin/bash

# exit when any command fails
set -e

registry='dockerhub.ratnikovmikhail.ru'
image="${registry}/projects/work-gitlab-handler/base_php:8.2.9-fpm-alpine3.18"

docker build --no-cache -t ${image} .
docker push ${image}