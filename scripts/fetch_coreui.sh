#!/usr/bin/env bash
set -euo pipefail
DEST="public/assets/vendor/coreui"
mkdir -p "$DEST/icons"

COREUI_VER="5.1.0"
ICONS_VER="3.0.0"

curl -L "https://cdn.jsdelivr.net/npm/@coreui/coreui@${COREUI_VER}/dist/css/coreui.min.css"       -o "$DEST/coreui.min.css"
curl -L "https://cdn.jsdelivr.net/npm/@coreui/coreui@${COREUI_VER}/dist/js/coreui.bundle.min.js"  -o "$DEST/coreui.bundle.min.js"
curl -L "https://cdn.jsdelivr.net/npm/@coreui/icons@${ICONS_VER}/css/free.min.css"                -o "$DEST/icons/free.min.css"
curl -L "https://cdn.jsdelivr.net/npm/@coreui/icons@${ICONS_VER}/fonts/free.woff2"                -o "$DEST/icons/free.woff2"
curl -L "https://cdn.jsdelivr.net/npm/@coreui/icons@${ICONS_VER}/fonts/free.woff"                 -o "$DEST/icons/free.woff"
curl -L "https://cdn.jsdelivr.net/npm/@coreui/icons@${ICONS_VER}/fonts/free.ttf"                  -o "$DEST/icons/free.ttf"

echo "Done. Now you can work fully offline."
