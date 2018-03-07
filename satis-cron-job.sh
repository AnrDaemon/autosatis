#!/bin/sh

set -e

_doc_root="$( jq -r '.["output-dir"]' < "$HOME/satis.json" )"
_doc_root="$( readlink -fe "$HOME/${_doc_root#$HOME/}" )"
test "$_doc_root"
test -d "$_doc_root"

if [ "$1" = "-f" ]; then

  "$HOME/bin/satis" build --no-interaction -- "$HOME/satis.json" "$_doc_root" > /dev/null 2>&1

elif [ -f "$HOME/tmp/satis-update" ]; then

  test -s "$HOME/tmp/satis-update" || break

  _tmp="$( mktemp "$HOME/tmp/satis-update.XXXXXXXX" )"
  mv -f "$HOME/tmp/satis-update" "$_tmp"
  sleep 1

  sort -u "$_tmp" | xargs -- "$HOME/bin/satis" build --no-interaction -- "$HOME/satis.json" "$_doc_root" > /dev/null 2>&1 && {
    rm "$_tmp"
  }
fi
