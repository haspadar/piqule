# syntax=docker/dockerfile:1

# ----------------------------------------
# Tool versions (reproducible CI)
# ----------------------------------------
ARG NODE_VERSION=24.12.0
ARG ACTIONLINT_VERSION=1.7.9
ARG MARKDOWNLINT_VERSION=0.20
ARG YAMLLINT_VERSION=1.35.1
ARG PHP_CS_FIXER_VERSION=3.92.3
ARG TYPOS_VERSION=1.40.0
ARG HADOLINT_VERSION=2.14.0

# ----------------------------------------
# System package versions (Debian trixie, arm64)
# ----------------------------------------
ARG GIT_VERSION=1:2.47.3-0+deb13u1
ARG UNZIP_VERSION=6.0-29
ARG CURL_VERSION=8.14.1-2+deb13u2
ARG BASH_VERSION=5.2.37-2+b5
ARG FISH_VERSION=4.0.2-1
ARG PYTHON3_VERSION=3.13.5-1
ARG PYTHON3_PIP_VERSION=25.1.1+dfsg-1
ARG LIBICU_DEV_VERSION=76.1-4
ARG LIBZIP_DEV_VERSION=1.11.3-2
ARG ZLIB1G_DEV_VERSION=1:1.3.dfsg+really1.3.1-1+b1
ARG LIBONIG_DEV_VERSION=6.9.9-1+b1

# ----------------------------------------
# Base image
# ----------------------------------------
FROM php:8.5-cli

# Re-declare ARGs after FROM
ARG NODE_VERSION
ARG ACTIONLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG YAMLLINT_VERSION
ARG PHP_CS_FIXER_VERSION
ARG TYPOS_VERSION
ARG HADOLINT_VERSION

ARG GIT_VERSION
ARG UNZIP_VERSION
ARG CURL_VERSION
ARG BASH_VERSION
ARG FISH_VERSION
ARG PYTHON3_VERSION
ARG PYTHON3_PIP_VERSION
ARG LIBICU_DEV_VERSION
ARG LIBZIP_DEV_VERSION
ARG ZLIB1G_DEV_VERSION
ARG LIBONIG_DEV_VERSION

# ----------------------------------------
# OCI labels
# ----------------------------------------
LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Piqule — PHP Quality Laws"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

# ----------------------------------------
# Shell
# ----------------------------------------
SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ----------------------------------------
# System dependencies + Node.js
# ----------------------------------------
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git=${GIT_VERSION} \
        unzip=${UNZIP_VERSION} \
        curl=${CURL_VERSION} \
        bash=${BASH_VERSION} \
        fish=${FISH_VERSION} \
        python3=${PYTHON3_VERSION} \
        python3-pip=${PYTHON3_PIP_VERSION} \
        libicu-dev=${LIBICU_DEV_VERSION} \
        libzip-dev=${LIBZIP_DEV_VERSION} \
        zlib1g-dev=${ZLIB1G_DEV_VERSION} \
        libonig-dev=${LIBONIG_DEV_VERSION} \
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
