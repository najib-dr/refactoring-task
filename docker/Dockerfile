FROM php:8.4-fpm-alpine

ARG USER=najib
ARG GROUP=${USER}
ARG UID=1000
ARG GID=${UID}
ARG WORKDIR="/app"

WORKDIR ${WORKDIR}

RUN addgroup --gid ${GID} ${GROUP} \
    && adduser --uid ${UID} --home ${WORKDIR} --ingroup ${GROUP} --no-create-home -D ${USER} \
    && chown -R ${USER}:${GROUP} ${WORKDIR}

RUN apk add --no-cache autoconf gcc g++ make libzip-dev yaml-dev \
    && pecl install yaml \
    && docker-php-ext-enable yaml

COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

USER ${USER}:${GROUP}

CMD ["sh", "/app/docker/entrypoint.sh"]
