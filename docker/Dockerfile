# syntax=docker/dockerfile:1

ARG PHP_IMAGE=php:8.3-cli-trixie
ARG NODE_IMAGE=node:24-trixie

# ============================================================
# Tool versions
# ============================================================
ARG ACTIONLINT_VERSION=1.7.11
ARG HADOLINT_VERSION=2.14.0
ARG MARKDOWNLINT_VERSION=0.20.0
ARG YAMLLINT_VERSION=1.38.0
ARG TYPOS_VERSION=1.43.4
ARG SHELLCHECK_VERSION=0.10.0
ARG JSONLINT_VERSION=17.0.1

# PHP ecosystem versions
ARG PHPSTAN_VERSION=^2.1
ARG PSALM_VERSION=^6.14
ARG PHPUNIT_VERSION=^12
ARG INFECTION_VERSION=^0.32
ARG PHPMD_VERSION=^2.15
ARG PHPMETRICS_VERSION=^2.9
ARG PHPCS_VERSION=^4.0
ARG PHPCSFIXER_VERSION=^3.91

# ============================================================
# Node stage
# ============================================================
FROM ${NODE_IMAGE} AS node

# ============================================================
# PHP stage
# ============================================================
FROM ${PHP_IMAGE}

LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Piqule — PHP Quality Laws"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

ARG ACTIONLINT_VERSION
ARG HADOLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG YAMLLINT_VERSION
ARG TYPOS_VERSION
ARG SHELLCHECK_VERSION
ARG JSONLINT_VERSION

ARG PHPSTAN_VERSION
ARG PSALM_VERSION
ARG PHPUNIT_VERSION
ARG INFECTION_VERSION
ARG PHPMD_VERSION
ARG PHPMETRICS_VERSION
ARG PHPCS_VERSION
ARG PHPCSFIXER_VERSION

# ------------------------------------------------------------
# Copy Node runtime
# ------------------------------------------------------------
COPY --from=node /usr/local /usr/local

ENV PIPX_HOME=/opt/pipx
ENV PIPX_BIN_DIR=/usr/local/bin

# ------------------------------------------------------------
# System dependencies
# ------------------------------------------------------------
# hadolint ignore=DL3008
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        ca-certificates \
        bash \
        git \
        curl \
        unzip \
        fish \
        python3 \
        python3-venv \
        pipx \
        libyaml-dev \
        libxml2-utils \
        libicu-dev \
        libzip-dev \
        zlib1g-dev \
        libonig-dev \
        openjdk-21-jre-headless; \
    rm -rf /var/lib/apt/lists/*; \
    \
    docker-php-ext-install intl zip mbstring; \
    pecl install xdebug yaml; \
    docker-php-ext-enable xdebug yaml

# ------------------------------------------------------------
# Install Composer
# ------------------------------------------------------------
RUN set -eux; \
    EXPECTED_CHECKSUM="$(curl -sS https://composer.github.io/installer.sig)"; \
    curl -sS https://getcomposer.org/installer -o composer-setup.php; \
    ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"; \
    [ "$EXPECTED_CHECKSUM" = "$ACTUAL_CHECKSUM" ]; \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer; \
    rm composer-setup.php

# ------------------------------------------------------------
# Install infrastructure linters
# ------------------------------------------------------------
RUN set -eux; \
    ARCH="$(uname -m)"; \
    case "$ARCH" in \
      x86_64) \
        ACTIONLINT_ARCH="amd64"; \
        HADOLINT_ARCH="x86_64"; \
        TYPOS_ARCH="x86_64"; \
        SHELLCHECK_ARCH="x86_64"; \
        ;; \
      aarch64) \
        ACTIONLINT_ARCH="arm64"; \
        HADOLINT_ARCH="arm64"; \
        TYPOS_ARCH="aarch64"; \
        SHELLCHECK_ARCH="aarch64"; \
        ;; \
      *) echo "Unsupported architecture: $ARCH"; exit 1 ;; \
    esac; \
    \
    curl -sSfL \
      "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_${ACTIONLINT_ARCH}.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/actionlint; \
    \
    curl -sSfL \
      "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-linux-${HADOLINT_ARCH}" \
      -o /usr/local/bin/hadolint; \
    chmod +x /usr/local/bin/hadolint; \
    \
    curl -sSfL \
      "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-${TYPOS_ARCH}-unknown-linux-musl.tar.gz" \
      | tar -xz -C /usr/local/bin; \
    chmod +x /usr/local/bin/typos; \
    \
    curl -sSfL \
      "https://github.com/koalaman/shellcheck/releases/download/v${SHELLCHECK_VERSION}/shellcheck-v${SHELLCHECK_VERSION}.linux.${SHELLCHECK_ARCH}.tar.xz" \
      | tar -xJ; \
    mv "shellcheck-v${SHELLCHECK_VERSION}/shellcheck" /usr/local/bin/shellcheck; \
    chmod +x /usr/local/bin/shellcheck; \
    rm -rf "shellcheck-v${SHELLCHECK_VERSION}"; \
    \
    npm install -g \
        "markdownlint-cli2@${MARKDOWNLINT_VERSION}" \
        "@prantlf/jsonlint@${JSONLINT_VERSION}"; \
    npm cache clean --force; \
    \
    pipx install "yamllint==${YAMLLINT_VERSION}"

# ------------------------------------------------------------
# Install PHP analysis packages globally
# ------------------------------------------------------------
RUN set -eux; \
    composer global config allow-plugins.infection/extension-installer true; \
    composer global require \
        "phpstan/phpstan:${PHPSTAN_VERSION}" \
        "vimeo/psalm:${PSALM_VERSION}" \
        "phpunit/phpunit:${PHPUNIT_VERSION}" \
        "infection/infection:${INFECTION_VERSION}" \
        "phpmd/phpmd:${PHPMD_VERSION}" \
        "phpmetrics/phpmetrics:${PHPMETRICS_VERSION}" \
        "phpcsstandards/php_codesniffer:${PHPCS_VERSION}" \
        "friendsofphp/php-cs-fixer:${PHPCSFIXER_VERSION}"; \
    composer global clear-cache

ENV PATH="/root/.composer/vendor/bin:/usr/local/bin:/usr/local/lib/node_modules/.bin:/usr/local/sbin:/usr/sbin:/usr/bin:/sbin:/bin"

# ------------------------------------------------------------
# Non-root user
# ------------------------------------------------------------
RUN useradd --uid 1000 --create-home --shell /bin/bash appuser

USER appuser
WORKDIR /project
