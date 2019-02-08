#!/bin/bash

chmod 775 ttapp-frontend
cp ttapp-frontend /usr/local/bin/ttapp-frontend
cp ttapp-frontend.service /etc/systemd/system/ttapp-frontend.service

