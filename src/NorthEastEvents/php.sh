#!/bin/bash
export MSYS_NO_PATHCONV=1

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# This script assumes that containers have been started already
COMPOSE_CONFIG="$(readlink -f $SCRIPT_DIR/../../docker-compose.yml)"

# If this is being run on a windows machine, convert into a more readable path.
if [ "$OSTYPE" == "msys" ]; then
	COMPOSE_CONFIG="$(cygpath -m "$COMPOSE_CONFIG")"
fi

CONTAINER_ID="$(docker-compose -f "$COMPOSE_CONFIG" ps -q php)"
EXECUTABLE=$1
shift

dockerPHP () {
	COMMAND="cd /var/www/src/NorthEastEvents; $@"
	docker exec $CONTAINER_ID /bin/bash -c "$COMMAND"
}

dockerPHP "$EXECUTABLE" "$@"
