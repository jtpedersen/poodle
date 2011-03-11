#!/bin/sh
dropdb poodle -Upoodle
createdb -U poodle poodle
psql -U poodle < createdb.sql
