#!/bin/bash

WORKSPACE_DIRECTORY=~/Development/ttapp
FRONTEND_SERVICE=ttapp-frontend

container_running() {
	CONTAINER_ID=$(docker ps -aqf "name=$1")
	if [ -z "$CONTAINER_ID" ]; then 
		echo "Container $1 doesn't exist, make sure to run: docker-componse up -d"
		exit -1
	fi
	RUNNING=$(docker inspect -f {{.State.Running}} $CONTAINER_ID)
    if [ "$RUNNING" != true ] ; then
        echo "Container $1 is not running. Start it by running: docker-compose up -d"
        exit -1
    fi
}

cd $WORKSPACE_DIRECTORY

# Get latest changes
BRANCH=$(git branch | grep \* | cut -d ' ' -f2)
echo -ne "Pulling latest changes from '\e[34m$BRANCH\e[39m' "
GIT_STATUS=$(git pull)
if [ "$GIT_STATUS" == "Already up-to-date." ] ; then
    echo -e " - \e[32mUp to date\e[39m!"
    exit -1
else
    echo -e "- \e[32mDONE\e[39m"
fi

# Make sure php-fpm container is running
container_running php-fpm

# Running all database migrations
echo -ne "Running all database migrations "
docker-compose exec php-fpm sh -c '../bin/console --no-interaction doctrine:migrations:migrate' > /dev/null
echo -e "- \e[32mDONE\e[39m"

# Switch to frontend folder
echo -n "Stopping '$FRONTEND_SERVICE' service "
sudo service $FRONTEND_SERVICE stop >/dev/null 2>&1
EXIT_CODE=$(echo $?)
if [ "$EXIT_CODE" == "0" ] ; then
    echo -e "- \e[32mDONE\e[39m"
else
    echo -e "- \e[91mFAILED\e[39m"
fi

# Install all nodejs dependencies
echo "Installing frontend dependencies "
echo -e
cd front
npm install -s

echo -e
echo -ne "Starting '$FRONTEND_SERVICE' service "
sudo service $FRONTEND_SERVICE start >/dev/null 2>&1
EXIT_CODE=$(echo $?)
if [ "$EXIT_CODE" == "0" ] ; then
    echo -e "- \e[32mDONE\e[39m"
else
    echo -e "- \e[91mFAILED\e[39m"
fi
