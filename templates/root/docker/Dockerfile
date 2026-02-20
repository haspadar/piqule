# syntax=docker/dockerfile:1

# ============================================================
# Base arguments (available to all stages)
# ============================================================

ARG PHP_VERSION=<< config(docker.php_version)|default(["8.3"])|scalar >>
ARG PHP_IMAGE=php:${PHP_VERSION}-cli-trixie
ARG NODE_IMAGE=node:24-trixie

# Infrastructure tool versions
ARG ACTIONLINT_VERSION=1.7.11
ARG HADOLINT_VERSION=2.14.0
ARG MARKDOWNLINT_VERSION=0.20.0
ARG TYPOS_VERSION=1.43.4
ARG SHELLCHECK_VERSION=0.10.0
ARG JSONLINT_VERSION=17.0.1

# PHP quality tool versions
ARG PSALM_VERSION=6.14
ARG PHPSTAN_VERSION=2.1
ARG PHPUNIT_VERSION=12.0
ARG PHPMD_VERSION=2.15
ARG PHPMETRICS_VERSION=2.9
ARG PHPCS_VERSION=4.0
ARG PHPCSFIXER_VERSION=3.91
ARG INFECTION_VERSION=0.32

# ============================================================
# Node stage
# ============================================================

FROM ${NODE_IMAGE} AS node

# ============================================================
# Composer stage
# ============================================================

FROM composer:2 AS composer

# ============================================================
# Final PHP stage
# ============================================================

FROM ${PHP_IMAGE}

# Re-declare ARGs
ARG ACTIONLINT_VERSION
ARG HADOLINT_VERSION
ARG MARKDOWNLINT_VERSION
ARG TYPOS_VERSION
ARG SHELLCHECK_VERSION
ARG JSONLINT_VERSION

ARG PSALM_VERSION
ARG PHPSTAN_VERSION
ARG PHPUNIT_VERSION
ARG PHPMD_VERSION
ARG PHPMETRICS_VERSION
ARG PHPCS_VERSION
ARG PHPCSFIXER_VERSION
ARG INFECTION_VERSION

LABEL org.opencontainers.image.title="Piqule"
LABEL org.opencontainers.image.description="Piqule — PHP Quality Laws"
LABEL org.opencontainers.image.source="https://github.com/haspadar/piqule"
LABEL org.opencontainers.image.licenses="MIT"

SHELL ["/bin/bash", "-o", "pipefail", "-c"]

# ------------------------------------------------------------
# Copy runtimes
# ------------------------------------------------------------

COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules

RUN ln -sf /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
 && ln -sf /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

# ------------------------------------------------------------
# Composer configuration
# ------------------------------------------------------------

ENV COMPOSER_HOME=/opt/composer
ENV PATH="$COMPOSER_HOME/vendor/bin:/usr/local/bin:/usr/local/lib/node_modules/.bin:/usr/local/sbin:/usr/sbin:/usr/bin:/sbin:/bin"
ENV XDEBUG_MODE=off

# ------------------------------------------------------------
# Install everything in a single layer
# ------------------------------------------------------------
# hadolint ignore=DL3008

RUN set -eux; \
    \
    # --------------------------------------------------------\
    # System dependencies\
    # --------------------------------------------------------\
    apt-get update; \
    apt-get install -y --no-install-recommends \
        ca-certificates \
        bash \
        git \
        curl \
        unzip \
        python3 \
        libyaml-dev \
        libxml2-utils \
        libicu-dev \
        libzip-dev \
        zlib1g-dev \
        libonig-dev \
        yamllint; \
    rm -rf /var/lib/apt/lists/*; \
    \
    docker-php-ext-install intl zip mbstring; \
    pecl install xdebug yaml; \
    docker-php-ext-enable xdebug yaml; \
    \
    # --------------------------------------------------------\
    # Architecture detection\
    # --------------------------------------------------------\
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
    # --------------------------------------------------------\
    # Infrastructure linters (binary releases)\
    # --------------------------------------------------------\
    mkdir -p /tmp/actionlint; \
    curl -sSfL \
      "https://github.com/rhysd/actionlint/releases/download/v${ACTIONLINT_VERSION}/actionlint_${ACTIONLINT_VERSION}_linux_${ACTIONLINT_ARCH}.tar.gz" \
      | tar -xz -C /tmp/actionlint; \
    mv /tmp/actionlint/actionlint /usr/local/bin/actionlint; \
    chmod +x /usr/local/bin/actionlint; \
    rm -rf /tmp/actionlint; \
    \
    curl -sSfL \
      "https://github.com/hadolint/hadolint/releases/download/v${HADOLINT_VERSION}/hadolint-linux-${HADOLINT_ARCH}" \
      -o /usr/local/bin/hadolint; \
    chmod +x /usr/local/bin/hadolint; \
    \
    mkdir -p /tmp/typos; \
    curl -sSfL \
      "https://github.com/crate-ci/typos/releases/download/v${TYPOS_VERSION}/typos-v${TYPOS_VERSION}-${TYPOS_ARCH}-unknown-linux-musl.tar.gz" \
      | tar -xz -C /tmp/typos; \
    mv /tmp/typos/typos /usr/local/bin/typos; \
    chmod +x /usr/local/bin/typos; \
    rm -rf /tmp/typos; \
    \
    curl -sSfL \
      "https://github.com/koalaman/shellcheck/releases/download/v${SHELLCHECK_VERSION}/shellcheck-v${SHELLCHECK_VERSION}.linux.${SHELLCHECK_ARCH}.tar.xz" \
      | tar -xJ; \
    mv "shellcheck-v${SHELLCHECK_VERSION}/shellcheck" /usr/local/bin/shellcheck; \
    chmod +x /usr/local/bin/shellcheck; \
    rm -rf "shellcheck-v${SHELLCHECK_VERSION}"; \
    \
    # --------------------------------------------------------\
    # Node linters\
    # --------------------------------------------------------\
    npm install -g \
        "markdownlint-cli2@${MARKDOWNLINT_VERSION}" \
        "@prantlf/jsonlint@${JSONLINT_VERSION}"; \
    npm cache clean --force; \
    \
    # --------------------------------------------------------\
    # PHP quality tools\
    # --------------------------------------------------------\
    composer global config --no-plugins allow-plugins.infection/extension-installer true; \
    composer global require --no-interaction --no-progress \
        "vimeo/psalm:^${PSALM_VERSION}" \
        "phpstan/phpstan:^${PHPSTAN_VERSION}" \
        "phpunit/phpunit:^${PHPUNIT_VERSION}" \
        "phpmd/phpmd:^${PHPMD_VERSION}" \
        "phpmetrics/phpmetrics:^${PHPMETRICS_VERSION}" \
        "phpcsstandards/php_codesniffer:^${PHPCS_VERSION}" \
        "friendsofphp/php-cs-fixer:^${PHPCSFIXER_VERSION}" \
        "infection/infection:^${INFECTION_VERSION}"; \
    \
    # --------------------------------------------------------\
    # Non-root user\
    # --------------------------------------------------------\
    useradd --uid 1000 --create-home --shell /bin/bash appuser

# ------------------------------------------------------------
# Runtime scripts
# ------------------------------------------------------------

COPY docker/bin /usr/local/lib/piqule
RUN chmod +x /usr/local/lib/piqule/*

ENTRYPOINT ["/usr/local/lib/piqule/entrypoint"]

USER appuser
WORKDIR /project
