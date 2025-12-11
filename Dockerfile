# ----------------------------------------
# Base image
# ----------------------------------------
FROM php:8.5-cli

# ----------------------------------------
# OCI labels
# ----------------------------------------
LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Quality control for PHP projects"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ----------------------------------------
# Version arguments
# ----------------------------------------
ARG NODE_VERSION=24.12.0
ARG ACTIONLINT_VERSION=1.7.9
ARG MARKDOWNLINT_VERSION=0.20
ARG PHP_CS_FIXER_VERSION=3.91.3
ARG TYPOS_VERSION=1.40.0
ARG HADOLINT_VERSION=2.14.0

# ----------------------------------------
# System dependencies
# ----------------------------------------
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git unzip curl bash fish python3 python3-pip \
        libicu-dev libzip-dev zlib1g-dev libonig-dev \
    && docker-php-ext-install intl zip mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ----------------------------------------
# Node.js (official tar.xz)
# ----------------------------------------
RUN curl -fsSL "https://nodejs.org/dist/v${NODE_VERSION}/node-v${NODE_VERSION}-linux-x64.tar.xz" -o node.tar.xz \
    && tar -xf node.tar.xz -C /usr/local --strip-components=1 \
    && rm node.tar.xz

# ----------------------------------------
# Composer
# ----------------------------------------
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_HOME=/usr/local/composer

RUN mkdir -p /usr/local/composer \
    && curl -sS https://getcomposer.org/download/latest-stable/composer.phar -o composer.phar \
    && curl -sS https://getcomposer.org/download/latest-stable/composer.phar.sha256sum -o composer.phar.sha256sum \
    && sha256sum -c composer.phar.sha256sum \
    && mv composer.phar /usr/local/bin/composer \
    && rm composer.phar.sha256sum \
    && chmod +x /usr/local/bin/composer

ENV PATH="/usr/local/composer/vendor/bin:${PATH}"

# ----------------------------------------
# PHP-CS-Fixer
# ----------------------------------------
RUN composer global require friendsofphp/php-cs-fixer:${PHP_CS_FIXER_VERSION} \
    && ln -s /usr/local/composer/vendor/bin/php-cs-fixer /usr/local/bin/php-cs-fixer \
    && composer clear-cache

RUN mkdir -p /usr/local/piqule
COPY configs/php-cs-fixer.php /usr/local/piqule/php-cs-fixer.base.php

# ----------------------------------------
# actionlint (x86_64 only)
# ----------------------------------------
RUN curl -sSfL -o actionlint.tar.gz \
       "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_amd64.tar.gz" \
    && tar -xzf actionlint.tar.gz \
    && mv actionlint /usr/local/bin/actionlint \
    && chmod +x /usr/local/bin/actionlint \
    && rm -f actionlint.tar.gz

# ----------------------------------------
# markdownlint-cli2 + yamllint
# ----------------------------------------
RUN npm install -g markdownlint-cli2@${MARKDOWNLINT_VERSION} \
    && pip3 install --no-cache-dir --break-system-packages yamllint

# ----------------------------------------
# hadolint (x86_64)
# ----------------------------------------
RUN curl -sSfL -o /usr/local/bin/hadolint \
       "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-Linux-x86_64" \
    && chmod +x /usr/local/bin/hadolint

# ----------------------------------------
# typos (x86_64)
# ----------------------------------------
RUN curl -sSfL -o typos.tar.gz \
       "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-x86_64-unknown-linux-musl.tar.gz" \
    && tar -xzf typos.tar.gz \
    && mv typos /usr/local/bin/typos \
    && chmod +x /usr/local/bin/typos \
    && rm -f typos.tar.gz

# ----------------------------------------
# Entrypoint
# ----------------------------------------
WORKDIR /app

CMD ["bash"]
