FROM node:alpine
EXPOSE 3000
RUN mkdir -p /home/app
WORKDIR /home/app
COPY . /home/app
COPY ./package.json /home/app
RUN npm cache clean --force && npm cache verify && npm install --quiet