#!/bin/bash

cd /home/node/app

if [ ! -f ".env" ]; then
  cp .env.example .env
fi

npm install

nodemon -L
