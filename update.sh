#!/bin/bash

sudo -H -u http bash -c 'git pull origin master'
sudo -H -u http bash -c 'git checkout -f master'
