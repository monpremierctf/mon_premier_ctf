FROM node:8

#RUN apt-get update && apt-get install -y net-tools 

RUN adduser yolo --uid 3022 

WORKDIR /usr/src/app
COPY package.json /usr/src/app/
RUN npm install

# Add the code
COPY --chown=yolo:yolo . /usr/src/app
RUN npm run build && npm run test
RUN node demo/compile_webpack.js

RUN chown -R yolo:yolo /usr/src/app/
USER yolo
RUN echo "cd" >> /home/yolo/.bashrc

#COPY --chown=yolo:yolo challenges /home/yolo/challenges
CMD ["npm", "run", "run"]
EXPOSE 3000