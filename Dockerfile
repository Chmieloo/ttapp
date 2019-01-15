FROM node:10.15.0-alpine

WORKDIR /usr/app

# Install deps
COPY ./package.json ./
RUN npm install && npm cache clean --force

COPY . .

# Expose ports (for orchestrators and dynamic reverse proxies)
EXPOSE 3000

# Start the app
CMD npm start
