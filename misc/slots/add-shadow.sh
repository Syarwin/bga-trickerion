#!/bin/bash

mkdir -p output

for img in *.png; do
  convert "$img" \
    \( +clone -background black -shadow 80x5+5+5 \) \
    +swap -background none -layers merge +repage \
    "output/$img"
done
