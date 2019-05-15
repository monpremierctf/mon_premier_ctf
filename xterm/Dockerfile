FROM node:8

RUN apt-get update && apt-get install -y net-tools

RUN adduser yolo

# Set the working directory
WORKDIR /usr/src/app

# First, install dependencies to improve layer caching
COPY package.json /usr/src/app/
RUN chown -R yolo /usr/src/app/

# Add the code
COPY . /usr/src/app
RUN chown -R yolo /usr/src/app/

USER yolo
RUN echo "cd" >> ~/.bashrc
RUN npm install

EXPOSE 3000

# Run the tests and build, to make sure everything is working nicely
RUN npm run build 
# && npm run test


ENTRYPOINT ["/bin/bash", "-c", "exec \"${@:0}\";"]
CMD ["npm", "run", "start"]