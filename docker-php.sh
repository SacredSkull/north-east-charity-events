#!/bin/sh

echo $@

# We need to force the bash shell instructions...
eval $(docker-machine.exe env --shell=bash)
GOODBASE="/var/www"

SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do # resolve $SOURCE until the file is no longer a symlink
  DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
  SOURCE="$(readlink "$SOURCE")"
  [[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE" # if $SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done

BADBASE="$( cd -P "$( dirname "$SOURCE" )" && pwd )"

# Handling for PHPSTORM's phpinfo script
ADDITIONAL_PARAMS=""

FIRST_ARGUMENT=$1
REMOTE_PATH=$GOODBASE${PWD#$BADBASE}

# Don't touch it if it begins with a switch/option, like -v
if [[ ! $1 == "-"* ]] && [ $# -ne 0 ]; then
    WIN_PATH=$(cygpath -u \"$1\")
    FIRST_ARGUMENT=${WIN_PATH#$BADBASE}

    # If this is a relative path, make sure it now starts with a slash.
    if ! [[ $FIRST_ARGUMENT == "/"* ]]; then
        FIRST_ARGUMENT=$GOODBASE${PWD#$BADBASE}/$FIRST_ARGUMENT
    else
        FIRST_ARGUMENT=$GOODBASE$FIRST_ARGUMENT
    fi
fi

if [ $1 == "$(cygpath -m $HOME)/AppData/Local/Temp/ide-phpinfo.php" ]; then
    ADDITIONAL_PARAMS="$ADDITIONAL_PARAMS -v $(cygpath -u $HOME)/AppData/Local/Temp/ide-phpinfo.php:/tmp/ide-phpinfo.php"
    FIRST_ARGUMENT="/tmp/ide-phpinfo.php"
    REMOTE_PATH="/tmp"
fi

# We're including the first argument already.
shift

# Avoid https://github.com/docker/docker/issues/12751
MSYS_NO_PATHCONV=1 docker run -i -v $BADBASE:$GOODBASE $ADDITIONAL_PARAMS -w $REMOTE_PATH --rm sacredskull-php bash -c "php $FIRST_ARGUMENT $@"
