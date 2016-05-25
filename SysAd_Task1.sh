#!/bin/bash
mkdir sysad
touch sysad/file{1..100}
truncate -s 10240 sysad/file*
for file in sysad/file*
do
< /dev/urandom tr -dc [:alnum:] | head -c${1:-16} >> $file
touch -d "2 days ago" $file
chmod 444 $file
chattr +i $file
done


