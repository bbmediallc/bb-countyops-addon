#!/usr/bin/env bash
# Builds dist/bb-countyops-addon.zip from plugin/.
#
#   scripts/build.sh            build
#   scripts/build.sh v1.0.0     build, and fail unless the version is 1.0.0
#
# The zip's top folder must be "bb-countyops-addon": that is the folder installed
# on every site, and a different name would install a second copy instead
# of updating.
set -euo pipefail

cd "$(dirname "$0")/.."

header_version=$(sed -n 's/^ \* Version:[[:space:]]*//p' plugin/bb-countyops-addon.php | tr -d '\r')
constant_version=$(sed -n "s/^define( 'BBCOA_VERSION', '\([^']*\)' );/\1/p" plugin/bb-countyops-addon.php)

if [ -z "$header_version" ] || [ "$header_version" != "$constant_version" ]; then
    echo "Version mismatch. Make both the same:" >&2
    echo "  Version: header in bb-countyops-addon.php  $header_version" >&2
    echo "  BBCOA_VERSION in bb-countyops-addon.php     $constant_version" >&2
    exit 1
fi

if [ $# -gt 0 ] && [ "${1#v}" != "$header_version" ]; then
    echo "Tag $1 does not match plugin version $header_version." >&2
    exit 1
fi

rm -rf dist
mkdir -p dist/build
cp -R plugin dist/build/bb-countyops-addon
find dist/build -name '.DS_Store' -delete

(cd dist/build && zip -qrX ../bb-countyops-addon.zip bb-countyops-addon)
rm -rf dist/build

echo "Built dist/bb-countyops-addon.zip (version $header_version)"
