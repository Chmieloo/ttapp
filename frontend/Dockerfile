FROM node:10

WORKDIR /tmp

COPY package.json /tmp/package.json
RUN npm install --silent
COPY . /tmp
RUN npm run build

WORKDIR /app
RUN cp -r /tmp/dist/* /app

RUN npm install -g serve
EXPOSE 8080

CMD serve . -l 8080


