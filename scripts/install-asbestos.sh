#/bin/bash

ASBESTOS_VERSION="master" # replace with a specific commit sha1
INSTALL_PATH="www" # relative install path

set -e
DIR=$(dirname -- "$0")
TMP_DIR=$(mktemp -dt AsbestosPHP.XXXXX)

echo -e "\e[1mCloning AsbestosPHP...\e[0m"

cd "$DIR"
git clone "../AsbestosPHP" "$TMP_DIR"
git -C "$TMP_DIR" checkout -q "$ASBESTOS_VERSION"

# set modified dates from the commit dates
# based on: https://github.com/goncalomb/dotfiles/blob/master/bin/git-mtime
git -C "$TMP_DIR" ls-tree -r -t HEAD --name-only | while IFS= read -r f; do
	t=$(git -C "$TMP_DIR" log -n 1 --format="%ct" -- "$f")
	if [ -n "$t" ]; then
		touch -m -d "@$t" "$TMP_DIR/$f"
	fi
done

echo -e "\e[1mCopying asbestos directory...\e[0m"

cd "$INSTALL_PATH"
rm -rf asbestos
cp -rp "$TMP_DIR/asbestos" .

echo -e "\e[1mCleaning...\e[0m"

rm -rf "$TMP_DIR"
