#! /usr/bin/bash
process=`lsof -t -i :8000 -s TCP:LISTEN`
#echo $process
kill -9 $process
