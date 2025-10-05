#!/usr/bin/env bash
set -e
IMAGE_NAME=taska_cmdinj:latest
cd "$(dirname "$0")/webapp"
docker build -t $IMAGE_NAME .
docker run --rm -p 8080:80 --name taska_cmdinj_container $IMAGE_NAME
