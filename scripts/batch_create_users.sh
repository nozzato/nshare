#!/bin/bash

sleep 3

for i in {a..t}; do
    for j in {1..14}; do
        xdotool key Tab
    done
    xdotool type "user$i@nozzato.com"
    xdotool key Tab
    xdotool type "User ${i^^}"
    xdotool key Tab
    xdotool type "$i$i$i$i$i$i$i$i"
    xdotool key Tab
    xdotool key Tab
    xdotool key Enter
    sleep 1
done
