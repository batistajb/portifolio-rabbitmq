FROM node:16.0-alpine3.12

RUN apk add --no-cache bash git curl

RUN touch /home/node/.bashrc | echo "PS1='\w\$ '" >> /home/node/.bashrc

RUN npm install -g npm@8.3.1

RUN npm config set cache /home/node/app/.npm-cache --global

RUN npm install -g @loopback/cli --allow-root

RUN npm install -g nodemon

# Set to a non-root built-in user `node`
USER node

WORKDIR /home/node/app
