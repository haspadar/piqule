# syntax=docker/dockerfile:1

# ----------------------------------------
# Version arguments (allowed before FROM)
# ----------------------------------------
ARG NODE_VERSION=24.12.0
ARG ACTIONLINT_VERSION=1.7.9
ARG MARKDOWNLINT_VERSION=0.20
ARG YAMLLINT_VERSION=1.35.1
ARG PHP_CS_FIXER_VERSION=3.92.3
ARG TYPOS_VERSION=1.40.0
ARG HADOLINT_VERSION=2.14.0

# ----------------------------------------
# Base image
# ----------------------------------------
FROM php:8.5-cli

# Re-declare ARGs after FROM (Docker scope rule)
ARG NODE_VERSION
ARG ACTIONLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG YAMLLINT_VERSION
ARG PHP_CS_FIXER_VERSION
ARG TYPOS_VERSION
ARG HADOLINT_VERSION

# ----------------------------------------
# OCI labels
# ----------------------------------------
LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Quality control for PHP projects"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

# ----------------------------------------
# Shell
# ----------------------------------------
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ----------------------------------------
# System dependencies + Node.js
# ----------------------------------------
# hadolint ignore=DL3008
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        curl \
        bash \
        fish \
        python3 \
        python3-pip \
        libicu-dev \
        libzip-dev \
        zlib1g-dev \
        libonig-dev \
    && docker-php-ext-install intl zip mbstring \
    && curl -fsSL \
        "https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.xz" \
        -o node.tar.xz \
    && tar -xf node.tar.xz -C /usr/local --strip-components=1 \
    && rm node.tar.xz \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ----------------------------------------
# Composer + PHP-CS-Fixer
# ----------------------------------------
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/usr/local/composer
ENV PATH="/usr/local/composer/vendor/bin:${PATH}"

RUN mkdir -p /usr/local/composer /usr/local/piqule \
    && curl -sS https://getcomposer.org/download/latest-stable/composer.phar \
        -o composer.phar \
    && curl -sS https://getcomposer.org/download/latest-stable/composer.phar.sha256sum \
        -o composer.phar.sha256sum \
    && sha256sum -c composer.phar.sha256sum \
    && mv composer.phar /usr/local/bin/composer \
    && rm composer.phar.sha256sum \
    && chmod +x /usr/local/bin/composer \
    && composer global require "friendsofphp/php-cs-fixer:${PHP_CS_FIXER_VERSION}" \
    && ln -s /usr/local/composer/vendor/bin/php-cs-fixer /usr/local/bin/php-cs-fixer \
    && composer clear-cache

COPY configs/php-cs-fixer.php /usr/local/piqule/php-cs-fixer.base.php

# ----------------------------------------
# Linters & tooling (ONE RUN – required by Sonar)
# ----------------------------------------
RUN curl -sSfL \
        "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_amd64.tar.gz" \
        -o actionlint.tar.gz \
    && tar -xzf actionlint.tar.gz \
    && mv actionlint /usr/local/bin/actionlint \
    && chmod +x /usr/local/bin/actionlint \
    && rm -f actionlint.tar.gz \
    \
    && npm install -g "markdownlint-cli2@${MARKDOWNLINT_VERSION}" \
    && pip3 install --no-cache-dir --break-system-packages \
        "yamllint==${YAMLLINT_VERSION}" \
    \
    && curl -sSfL \
        "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-Linux-x86_64" \
        -o /usr/local/bin/hadolint \
    && chmod +x /usr/local/bin/hadolint \
    \
    && curl -sSfL \
        "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-x86_64-unknown-linux-musl.tar.gz" \
        -o typos.tar.gz \
    && tar -xzf typos.tar.gz \
    && mv typos /usr/local/bin/typos \
    && chmod +x /usr/local/bin/typos \
    && rm -f typos.tar.gz

# ----------------------------------------
# Entrypoint
# ----------------------------------------
WORKDIR /app

CMD ["bash"]
