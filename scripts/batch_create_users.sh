#!/bin/bash

npa=("alpha" "bravo" "charlie" "delta" "echo" "foxtrot" "golf" "hotel" "india" "juliet" "kilo" "lima" "mike" "november" "oscar" "papa" "quebec" "romeo" "sierra" "tango" "uniform" "victor" "whiskey" "xray" "yankee" "zulu")

sleep 3

for i in {0..25}; do
    for j in {1..14}; do
        xdotool key Tab
    done
    xdotool type "${npa[$i]}@nozzato.org"
    xdotool key Tab
    xdotool type "${npa[$i]^}"
    xdotool key Tab
    for j in {1..8}; do
        xdotool type "${npa[$i]::1}"
    done
    xdotool key Tab
    xdotool key Tab
    xdotool key Enter
    sleep 1
done
