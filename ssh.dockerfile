FROM jeroenpeeters/docker-ssh

RUN apk --update add bash
RUN apk --update add curl=7.47.0-r0
